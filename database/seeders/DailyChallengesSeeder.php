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
            ['setting_value' => '1', 'description' => 'Are daily challenges enabled?']
        );

        GamificationSetting::updateOrCreate(
            ['setting_key' => 'daily_challenge_count'],
            ['setting_value' => '3', 'description' => 'How many challenges per day?']
        );

        GamificationSetting::updateOrCreate(
            ['setting_key' => 'daily_challenge_bonus'],
            ['setting_value' => '150', 'description' => 'Bonus for completing all challenges']
        );

        // Clear old challenges and create new ones
        DailyChallengePool::truncate();

        // Create default challenges - each type has only ONE challenge
        // This ensures no duplicate types in daily selection
        $challenges = [
            // Link shortening challenges (EASY)
            [
                'title' => 'Shorten 3 Links',
                'description' => 'Shorten 3 new links today',
                'type' => 'shorten_links_easy',
                'target_value' => 3,
                'difficulty' => 'easy',
                'points_reward' => 30,
            ],
            // Link shortening challenges (MEDIUM)
            [
                'title' => 'Shorten 5 Links',
                'description' => 'Shorten 5 new links today',
                'type' => 'shorten_links_medium',
                'target_value' => 5,
                'difficulty' => 'medium',
                'points_reward' => 60,
            ],
            // Click challenges (EASY)
            [
                'title' => 'Get 50 Clicks',
                'description' => 'Get 50 clicks on your links',
                'type' => 'get_clicks_easy',
                'target_value' => 50,
                'difficulty' => 'easy',
                'points_reward' => 40,
            ],
            // Click challenges (MEDIUM)
            [
                'title' => 'Get 100 Clicks',
                'description' => 'Get 100 clicks on your links',
                'type' => 'get_clicks_medium',
                'target_value' => 100,
                'difficulty' => 'medium',
                'points_reward' => 80,
            ],
            // Different countries challenge (HARD)
            [
                'title' => '5 Different Countries',
                'description' => 'Get clicks from 5 different countries',
                'type' => 'different_countries',
                'target_value' => 5,
                'difficulty' => 'hard',
                'points_reward' => 150,
            ],
            // Social sharing (EASY)
            [
                'title' => 'Social Share',
                'description' => 'Share 1 link on social media',
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
