<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\GamificationSetting;
use Carbon\Carbon;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        // Add gamification settings
        GamificationSetting::updateOrCreate(
            ['setting_key' => 'competitions_enabled'],
            ['setting_value' => '1', 'description' => 'Haftalık yarışmalar aktif mi?']
        );

        // Create a sample weekly competition - starts now, ends in 7 days
        Competition::updateOrCreate(
            ['title' => 'Haftalık Tıklama Yarışması'],
            [
                'description' => 'Bu hafta en çok tıklama alan kazanır!',
                'type' => 'clicks',
                'start_date' => Carbon::now()->startOfWeek(),
                'end_date' => Carbon::now()->endOfWeek(),
                'prize_structure' => [
                    ['rank' => 1, 'rank_to' => null, 'points' => 10000],
                    ['rank' => 2, 'rank_to' => null, 'points' => 5000],
                    ['rank' => 3, 'rank_to' => null, 'points' => 2500],
                    ['rank' => 4, 'rank_to' => 10, 'points' => 1000],
                    ['rank' => 11, 'rank_to' => 50, 'points' => 500],
                    ['rank' => 51, 'rank_to' => 100, 'points' => 250],
                ],
                'is_active' => true,
            ]
        );

        // Create a links competition
        Competition::updateOrCreate(
            ['title' => 'Link Ustası'],
            [
                'description' => 'En çok link kısaltan yarışmacı!',
                'type' => 'links',
                'start_date' => Carbon::now()->startOfWeek(),
                'end_date' => Carbon::now()->endOfWeek(),
                'prize_structure' => [
                    ['rank' => 1, 'rank_to' => null, 'points' => 5000],
                    ['rank' => 2, 'rank_to' => null, 'points' => 2500],
                    ['rank' => 3, 'rank_to' => null, 'points' => 1000],
                    ['rank' => 4, 'rank_to' => 10, 'points' => 500],
                ],
                'is_active' => true,
            ]
        );
    }
}
