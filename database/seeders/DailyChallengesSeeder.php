<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyChallengePool;
use App\Models\GamificationSetting;

class DailyChallengesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add gamification settings
        GamificationSetting::updateOrCreate(
            ['setting_key' => 'daily_challenges_enabled'],
            ['setting_value' => '1', 'description' => 'Günlük görevler aktif mi?']
        );

        GamificationSetting::updateOrCreate(
            ['setting_key' => 'daily_challenge_count'],
            ['setting_value' => '3', 'description' => 'Günde kaç görev verilecek?']
        );

        GamificationSetting::updateOrCreate(
            ['setting_key' => 'daily_challenge_bonus'],
            ['setting_value' => '150', 'description' => 'Tüm görevleri tamamlama bonusu']
        );

        // Clear old challenges and create new ones
        DailyChallengePool::truncate();

        // Create default challenges - each type has only ONE challenge
        // This ensures no duplicate types in daily selection
        $challenges = [
            // Link kısaltma görevleri (EASY)
            [
                'title' => '3 Link Kısalt',
                'description' => 'Bugün 3 yeni link kısalt',
                'type' => 'shorten_links_easy',
                'target_value' => 3,
                'difficulty' => 'easy',
                'points_reward' => 30,
            ],
            // Link kısaltma görevleri (MEDIUM)
            [
                'title' => '5 Link Kısalt',
                'description' => 'Bugün 5 yeni link kısalt',
                'type' => 'shorten_links_medium',
                'target_value' => 5,
                'difficulty' => 'medium',
                'points_reward' => 60,
            ],
            // Tıklama görevleri (EASY)
            [
                'title' => '50 Tıklama Al',
                'description' => 'Linklerinden 50 tıklama al',
                'type' => 'get_clicks_easy',
                'target_value' => 50,
                'difficulty' => 'easy',
                'points_reward' => 40,
            ],
            // Tıklama görevleri (MEDIUM)
            [
                'title' => '100 Tıklama Al',
                'description' => 'Linklerinden 100 tıklama al',
                'type' => 'get_clicks_medium',
                'target_value' => 100,
                'difficulty' => 'medium',
                'points_reward' => 80,
            ],
            // Farklı ülke görevi (HARD)
            [
                'title' => '5 Farklı Ülke',
                'description' => '5 farklı ülkeden tıklama al',
                'type' => 'different_countries',
                'target_value' => 5,
                'difficulty' => 'hard',
                'points_reward' => 150,
            ],
            // Sosyal paylaşım (EASY)
            [
                'title' => 'Sosyal Paylaşım',
                'description' => '1 link sosyal medyada paylaş',
                'type' => 'share_links',
                'target_value' => 1,
                'difficulty' => 'easy',
                'points_reward' => 25,
            ],
        ];

        foreach ($challenges as $challenge) {
            DailyChallengePool::create($challenge);
        }
    }
}
