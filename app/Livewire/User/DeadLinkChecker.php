<?php

namespace App\Livewire\User;

use App\Jobs\CheckDeadLinksJob;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class DeadLinkChecker extends Component
{
    protected $layout = 'components.user-dashboard-layout';

    public $links     = [];
    public $results   = [];
    public $isScanning = false;
    public $progress  = 0;

    /** Cache key used to share results with the dispatched job. */
    public string $cacheKey = '';

    /** Polling interval in ms while a job is running (every 2 s). */
    protected $polling = null;

    public function mount(): void
    {
        $this->links = Link::where('user_id', Auth::id())
            ->latest()
            ->take(20)
            ->get();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Scan actions
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Dispatch the async job for the currently loaded links (≤20).
     */
    public function startScan(): void
    {
        $this->dispatchJob($this->links->pluck('id')->all());
    }

    /**
     * Load the latest 100 links then dispatch the async job.
     */
    public function scanAllLinks(): void
    {
        $this->links = Link::where('user_id', Auth::id())
            ->latest()
            ->take(100)
            ->get();

        $this->dispatchJob($this->links->pluck('id')->all());
    }

    /**
     * Livewire polls this method every 2 s while a scan is in progress.
     * Once the job writes its results to the cache, polling is stopped.
     */
    public function pollJobResult(): void
    {
        if (!$this->isScanning || !$this->cacheKey) {
            return;
        }

        $cached = Cache::get($this->cacheKey);

        if (!$cached) {
            // Job hasn't completed yet — keep polling
            return;
        }

        if ($cached['status'] === 'done') {
            $this->results   = $cached['results'];
            $this->progress  = 100;
        } elseif ($cached['status'] === 'failed') {
            // Surface the error; let the user retry
            session()->flash('error', 'Tarama başarısız oldu: ' . ($cached['error'] ?? 'Bilinmeyen hata'));
        }

        $this->isScanning = false;
        Cache::forget($this->cacheKey);
        $this->cacheKey = '';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CSV export (unchanged)
    // ─────────────────────────────────────────────────────────────────────────

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=links-report.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $links   = $this->links;
        $results = $this->results;

        $callback = static function () use ($links, $results) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Short Link', 'Original URL', 'Created At', 'Status']);

            foreach ($links as $link) {
                $statusData = $results[$link->id] ?? null;
                $statusText = 'Not Scanned';

                if ($statusData) {
                    $statusText = match ($statusData['status']) {
                        'alive'   => 'Alive',
                        'dead'    => 'Dead',
                        default   => 'Warning',
                    };
                    $statusText .= " ({$statusData['code']})";
                }

                fputcsv($file, [
                    route('shortlink.redirect', $link->code),
                    $link->original_url,
                    $link->created_at->format('Y-m-d H:i:s'),
                    $statusText,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function dispatchJob(array $linkIds): void
    {
        if (empty($linkIds)) {
            return;
        }

        // Unique cache key scoped to this user & session
        $this->cacheKey  = 'dead_link_scan_' . Auth::id() . '_' . uniqid();
        $this->isScanning = true;
        $this->results   = [];
        $this->progress  = 0;

        // Pre-write a "pending" marker so the job knows the key is reserved
        Cache::put($this->cacheKey, ['status' => 'pending'], now()->addMinutes(10));

        CheckDeadLinksJob::dispatch(Auth::id(), $linkIds, $this->cacheKey);
    }

    public function render()
    {
        return view('livewire.user.dead-link-checker');
    }
}
