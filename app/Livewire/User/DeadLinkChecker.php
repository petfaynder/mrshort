<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Link;

class DeadLinkChecker extends Component
{
    public $links = [];
    public $results = [];
    public $isScanning = false;
    public $progress = 0;

    public function mount()
    {
        // Load latest 20 links for initial display/checking to avoid performance issues
        $this->links = Link::where('user_id', Auth::id())
            ->latest()
            ->take(20)
            ->get();
    }

    public function startScan()
    {
        $this->isScanning = true;
        $this->results = [];
        $this->progress = 0;
        
        // This process might be slow, usually handled by jobs, 
        // but for a simple tool we'll do it synchronously or via polling.
        // For better UX in Livewire, we can't easily stream updates in a single request without polling.
        // So we will perform the scan for the visible links.
        
        $total = $this->links->count();
        $processed = 0;

        foreach ($this->links as $link) {
            $status = $this->checkUrl($link->original_url);
            $this->results[$link->id] = $status;
            
            $processed++;
            if ($total > 0) {
                $this->progress = intval(($processed / $total) * 100);
            }
        }

        $this->isScanning = false;
    }

    public function scanAllLinks()
    {
        set_time_limit(300); // Increase execution time to 5 minutes
        
        $this->links = Link::where('user_id', Auth::id())
            ->latest()
            ->take(100) // Limit to 100 to prevent browser timeouts
            ->get();

        $this->startScan();
    }

    public function exportCsv()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=links-report.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Short Link', 'Original URL', 'Created At', 'Status'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($this->links as $link) {
                $statusData = isset($this->results[$link->id]) ? $this->results[$link->id] : null;
                $statusText = 'Not Scanned';
                
                if ($statusData) {
                    $statusText = $statusData['status'] === 'alive' ? 'Alive' : ($statusData['status'] === 'dead' ? 'Dead' : 'Warning');
                    $statusText .= " ({$statusData['code']})";
                }

                fputcsv($file, [
                    route('shortlink.redirect', $link->code),
                    $link->original_url,
                    $link->created_at->format('Y-m-d H:i:s'),
                    $statusText
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function checkUrl($url)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ])
                ->withoutVerifying() // Disable SSL verification for development
                ->get($url);
            
            if ($response->successful() || $response->redirect()) {
                return ['status' => 'alive', 'code' => $response->status()];
            } elseif ($response->clientError()) { // 400 status codes
                return ['status' => 'dead', 'code' => $response->status()];
            } elseif ($response->serverError()) { // 500 status codes
                return ['status' => 'dead', 'code' => $response->status()];
            } else {
                return ['status' => 'warning', 'code' => $response->status()];
            }
        } catch (\Exception $e) {
            // Get a short error message
            $message = $e->getMessage();
            if (str_contains($message, 'cURL error 28')) {
                $error = 'Timeout';
            } elseif (str_contains($message, 'cURL error 6')) {
                $error = 'DNS Error';
            } else {
                // Limit message length
                $error = substr($message, 0, 30) . '...';
            }
            return ['status' => 'dead', 'code' => $error];
        }
    }

    public function render()
    {
        return view('livewire.user.dead-link-checker');
    }
}
