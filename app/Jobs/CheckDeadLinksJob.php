<?php

namespace App\Jobs;

use App\Models\Link;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckDeadLinksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum execution time in seconds (5 minutes).
     * Prevents workers from getting stuck on large link lists.
     */
    public int $timeout = 300;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 1;

    /**
     * Create a new job instance.
     *
     * @param int   $userId   The user whose links should be checked.
     * @param array $linkIds  Specific link IDs to check (empty = all recent 100).
     * @param string $cacheKey Unique key so the Livewire component can poll results.
     */
    public function __construct(
        public readonly int    $userId,
        public readonly array  $linkIds,
        public readonly string $cacheKey,
    ) {}

    /**
     * Execute the job — check each URL and cache the result map.
     */
    public function handle(): void
    {
        Log::info('CheckDeadLinksJob started', [
            'user_id'  => $this->userId,
            'link_count' => count($this->linkIds),
        ]);

        $links = Link::whereIn('id', $this->linkIds)
            ->where('user_id', $this->userId)
            ->get();

        $results = [];
        foreach ($links as $link) {
            $results[$link->id] = $this->checkUrl($link->original_url);
        }

        // Store results in cache for 10 minutes so Livewire can retrieve them
        Cache::put($this->cacheKey, [
            'status'    => 'done',
            'results'   => $results,
            'completed' => now()->toIso8601String(),
        ], now()->addMinutes(10));

        Log::info('CheckDeadLinksJob completed', [
            'user_id'  => $this->userId,
            'checked'  => count($results),
            'cache_key' => $this->cacheKey,
        ]);
    }

    /**
     * Handle a job failure — store an error state in the cache so the UI
     * can surface a friendly message instead of spinning forever.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('CheckDeadLinksJob failed', [
            'user_id' => $this->userId,
            'error'   => $exception->getMessage(),
        ]);

        Cache::put($this->cacheKey, [
            'status' => 'failed',
            'error'  => $exception->getMessage(),
        ], now()->addMinutes(5));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function checkUrl(string $url): array
    {
        try {
            $request = Http::timeout(10)->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ]);

            // Only disable SSL verification in non-production environments
            if (!app()->isProduction()) {
                $request = $request->withoutVerifying();
            }

            $response = $request->get($url);

            if ($response->successful() || $response->redirect()) {
                return ['status' => 'alive', 'code' => $response->status()];
            } elseif ($response->clientError()) {
                return ['status' => 'dead', 'code' => $response->status()];
            } elseif ($response->serverError()) {
                return ['status' => 'dead', 'code' => $response->status()];
            }

            return ['status' => 'warning', 'code' => $response->status()];
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = match (true) {
                str_contains($message, 'cURL error 28') => 'Timeout',
                str_contains($message, 'cURL error 6')  => 'DNS Error',
                default => substr($message, 0, 30) . '...',
            };

            return ['status' => 'dead', 'code' => $error];
        }
    }
}
