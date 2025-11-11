<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
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

        // Tüm zamanların liderlik tablosunu önbelleğe al
        $allTimeLeaderboard = User::orderByDesc('gamification_points')
                                ->limit(100) // İlk 100 kullanıcıyı önbelleğe al
                                ->get();
        Cache::put('leaderboard_all_time', $allTimeLeaderboard, now()->addHours(1)); // 1 saat önbellekte tut

        // Aylık liderlik tablosunu önbelleğe al (örnek)
        // Gerçek bir uygulama için, aylık puanları takip eden bir mekanizma olmalı
        $monthlyLeaderboard = User::orderByDesc('gamification_points') // Geçici olarak genel puanı kullanıyoruz
                                ->limit(100)
                                ->get();
        Cache::put('leaderboard_monthly', $monthlyLeaderboard, now()->addHours(1));

        // Haftalık liderlik tablosunu önbelleğe al (örnek)
        $weeklyLeaderboard = User::orderByDesc('gamification_points') // Geçici olarak genel puanı kullanıyoruz
                                ->limit(100)
                                ->get();
        Cache::put('leaderboard_weekly', $weeklyLeaderboard, now()->addHours(1));

        Log::info('LeaderboardCacheJob completed.');
    }
}