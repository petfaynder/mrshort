<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StreakMilestone;
use App\Models\GamificationSetting;

class StreakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add gamification settings for streak
        GamificationSetting::updateOrCreate(
            ['setting_key' => 'streak_enabled'],
            ['setting_value' => '1', 'description' => 'Streak sistemi aktif mi?']
        );

        GamificationSetting::updateOrCreate(
            ['setting_key' => 'streak_reset_hour'],
            ['setting_value' => '0', 'description' => 'Streak sıfırlama saati (0-23)']
        );

        // Create default streak milestones
        $milestones = [
            [
                'days_required' => 3,
                'points_reward' => 100,
                'bonus_type' => null,
                'bonus_value' => null,
            ],
            [
                'days_required' => 7,
                'points_reward' => 300,
                'bonus_type' => 'xp_boost',
                'bonus_value' => 5,
                'bonus_duration_hours' => 24,
            ],
            [
                'days_required' => 14,
                'points_reward' => 600,
                'bonus_type' => 'xp_boost',
                'bonus_value' => 10,
                'bonus_duration_hours' => 24,
            ],
            [
                'days_required' => 30,
                'points_reward' => 1500,
                'bonus_type' => 'streak_freeze',
                'bonus_value' => 2,
            ],
            [
                'days_required' => 60,
                'points_reward' => 3000,
                'bonus_type' => 'xp_boost',
                'bonus_value' => 15,
                'bonus_duration_hours' => 48,
            ],
            [
                'days_required' => 100,
                'points_reward' => 5000,
                'bonus_type' => 'streak_freeze',
                'bonus_value' => 5,
            ],
            [
                'days_required' => 365,
                'points_reward' => 25000,
                'bonus_type' => 'xp_boost',
                'bonus_value' => 10,
                'bonus_duration_hours' => null, // kalıcı
            ],
        ];

        foreach ($milestones as $milestone) {
            StreakMilestone::updateOrCreate(
                ['days_required' => $milestone['days_required']],
                $milestone
            );
        }
    }
}
