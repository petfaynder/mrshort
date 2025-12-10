<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailySpinPrize;
use App\Models\GamificationSetting;

class DailySpinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add gamification settings for daily spin
        GamificationSetting::updateOrCreate(
            ['setting_key' => 'daily_spin_enabled'],
            ['setting_value' => '1', 'description' => 'Günlük şans çarkı aktif mi?']
        );

        GamificationSetting::updateOrCreate(
            ['setting_key' => 'spin_cooldown_hours'],
            ['setting_value' => '24', 'description' => 'Çark döndürme bekleme süresi (saat)']
        );

        GamificationSetting::updateOrCreate(
            ['setting_key' => 'vip_extra_spins'],
            ['setting_value' => '1', 'description' => 'VIP kullanıcılar için extra spin sayısı']
        );

        // Create default spin prizes
        $prizes = [
            [
                'name' => '10 Puan',
                'type' => 'points',
                'value' => 10,
                'probability' => 30,
                'color' => '#6B7280', // Gray
                'is_jackpot' => false,
                'sort_order' => 1,
            ],
            [
                'name' => '25 Puan',
                'type' => 'points',
                'value' => 25,
                'probability' => 25,
                'color' => '#3B82F6', // Blue
                'is_jackpot' => false,
                'sort_order' => 2,
            ],
            [
                'name' => '50 Puan',
                'type' => 'points',
                'value' => 50,
                'probability' => 20,
                'color' => '#10B981', // Green
                'is_jackpot' => false,
                'sort_order' => 3,
            ],
            [
                'name' => '100 Puan',
                'type' => 'points',
                'value' => 100,
                'probability' => 12,
                'color' => '#8B5CF6', // Purple
                'is_jackpot' => false,
                'sort_order' => 4,
            ],
            [
                'name' => '150 Puan',
                'type' => 'points',
                'value' => 150,
                'probability' => 8,
                'color' => '#F97316', // Orange
                'is_jackpot' => false,
                'sort_order' => 5,
            ],
            [
                'name' => '250 Puan',
                'type' => 'points',
                'value' => 250,
                'probability' => 4,
                'color' => '#EAB308', // Yellow/Gold
                'is_jackpot' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Streak Freeze',
                'type' => 'streak_freeze',
                'value' => 1,
                'probability' => 1,
                'color' => '#EF4444', // Red
                'is_jackpot' => false,
                'sort_order' => 7,
            ],
        ];

        foreach ($prizes as $prize) {
            DailySpinPrize::updateOrCreate(
                ['name' => $prize['name']],
                $prize
            );
        }
    }
}
