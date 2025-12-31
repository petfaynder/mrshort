<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LeaderboardCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('LeaderboardCacheJob started.');

        // Get cache TTL from settings (default 1 hour = 3600 seconds)
        $ttlSeconds = SiteSetting::getCacheTtl('leaderboard');

        // Tüm zamanların liderlik tablosunu önbelleğe al
        $allTimeLeaderboard = User::orderByDesc('gamification_points')
                                ->limit(100)
                                ->get();
        Cache::put('leaderboard_all_time', $allTimeLeaderboard, now()->addSeconds($ttlSeconds));

        // Aylık liderlik tablosunu önbelleğe al
        $monthlyLeaderboard = User::orderByDesc('gamification_points')
                                ->limit(100)
                                ->get();
        Cache::put('leaderboard_monthly', $monthlyLeaderboard, now()->addSeconds($ttlSeconds));

        // Haftalık liderlik tablosunu önbelleğe al
        $weeklyLeaderboard = User::orderByDesc('gamification_points')
                                ->limit(100)
                                ->get();
        Cache::put('leaderboard_weekly', $weeklyLeaderboard, now()->addSeconds($ttlSeconds));

        Log::info('LeaderboardCacheJob completed. TTL: ' . $ttlSeconds . ' seconds.');
    }
}
