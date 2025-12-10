<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MysteryBox;
use App\Models\GamificationSetting;

class MysteryBoxSeeder extends Seeder
{
    public function run(): void
    {
        // Add gamification settings
        GamificationSetting::updateOrCreate(
            ['setting_key' => 'mystery_boxes_enabled'],
            ['setting_value' => '1', 'description' => 'Gizem kutuları aktif mi?']
        );

        // Bronze Box - every 50 links
        MysteryBox::updateOrCreate(
            ['tier' => 'bronze'],
            [
                'name' => 'Bronz Kutu',
                'description' => 'Temel ödüller içerir',
                'icon' => '📦',
                'color' => '#cd7f32',
                'contents' => [
                    ['type' => 'points', 'min' => 50, 'max' => 200, 'probability' => 70],
                    ['type' => 'points', 'min' => 200, 'max' => 500, 'probability' => 25],
                    ['type' => 'streak_freeze', 'value' => 1, 'probability' => 5],
                ],
                'is_active' => true,
            ]
        );

        // Silver Box - every 1000 clicks
        MysteryBox::updateOrCreate(
            ['tier' => 'silver'],
            [
                'name' => 'Gümüş Kutu',
                'description' => 'Orta seviye ödüller',
                'icon' => '🎁',
                'color' => '#c0c0c0',
                'contents' => [
                    ['type' => 'points', 'min' => 200, 'max' => 500, 'probability' => 60],
                    ['type' => 'points', 'min' => 500, 'max' => 1000, 'probability' => 30],
                    ['type' => 'streak_freeze', 'value' => 2, 'probability' => 10],
                ],
                'is_active' => true,
            ]
        );

        // Gold Box - weekly all challenges
        MysteryBox::updateOrCreate(
            ['tier' => 'gold'],
            [
                'name' => 'Altın Kutu',
                'description' => 'Değerli ödüller',
                'icon' => '✨',
                'color' => '#ffd700',
                'contents' => [
                    ['type' => 'points', 'min' => 500, 'max' => 1500, 'probability' => 50],
                    ['type' => 'points', 'min' => 1500, 'max' => 3000, 'probability' => 35],
                    ['type' => 'streak_freeze', 'value' => 3, 'probability' => 15],
                ],
                'is_active' => true,
            ]
        );

        // Diamond Box - monthly top 10
        MysteryBox::updateOrCreate(
            ['tier' => 'diamond'],
            [
                'name' => 'Elmas Kutu',
                'description' => 'En değerli ödüller',
                'icon' => '💎',
                'color' => '#b9f2ff',
                'contents' => [
                    ['type' => 'points', 'min' => 2000, 'max' => 5000, 'probability' => 50],
                    ['type' => 'points', 'min' => 5000, 'max' => 10000, 'probability' => 35],
                    ['type' => 'streak_freeze', 'value' => 5, 'probability' => 15],
                ],
                'is_active' => true,
            ]
        );
    }
}
