<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Season;
use App\Models\SeasonReward;
use Carbon\Carbon;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {
        // Create a sample season
        $season = Season::create([
            'name' => 'Sezon 1: Kış Şampiyonları',
            'theme' => 'Kış Teması',
            'description' => 'İlk sezon başlıyor! Link kısaltarak ve tıklama alarak XP kazanın, özel ödüllerin kilidini açın.',
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addMonths(3),
            'premium_price_points' => 5000,
            'premium_price_money' => 50.00,
            'is_active' => true,
        ]);

        // Free Pass rewards
        $freeRewards = [
            ['level' => 1, 'type' => 'points', 'value' => '50', 'name' => '50 Puan', 'icon' => '💰'],
            ['level' => 3, 'type' => 'points', 'value' => '100', 'name' => '100 Puan', 'icon' => '💰'],
            ['level' => 5, 'type' => 'points', 'value' => '200', 'name' => '200 Puan', 'icon' => '💰'],
            ['level' => 7, 'type' => 'mystery_box', 'value' => 'bronze', 'name' => 'Bronze Kutu', 'icon' => '📦'],
            ['level' => 10, 'type' => 'points', 'value' => '400', 'name' => '400 Puan', 'icon' => '💰'],
            ['level' => 10, 'type' => 'badge', 'value' => 'season_traveler', 'name' => 'Sezon Yolcusu Rozeti', 'icon' => '🏅'],
            ['level' => 15, 'type' => 'mystery_box', 'value' => 'silver', 'name' => 'Silver Kutu', 'icon' => '📦'],
            ['level' => 20, 'type' => 'points', 'value' => '750', 'name' => '750 Puan', 'icon' => '💰'],
            ['level' => 20, 'type' => 'mystery_box', 'value' => 'gold', 'name' => 'Gold Kutu', 'icon' => '📦'],
            ['level' => 25, 'type' => 'points', 'value' => '1000', 'name' => '1000 Puan', 'icon' => '💰'],
            ['level' => 30, 'type' => 'points', 'value' => '2000', 'name' => '2000 Puan', 'icon' => '💰'],
            ['level' => 30, 'type' => 'badge', 'value' => 'season_master', 'name' => 'Sezon Ustası Rozeti', 'icon' => '🏆'],
        ];

        // Premium Pass rewards
        $premiumRewards = [
            ['level' => 1, 'type' => 'points', 'value' => '50', 'name' => '+50 Puan', 'icon' => '⭐'],
            ['level' => 5, 'type' => 'points', 'value' => '200', 'name' => '+200 Puan', 'icon' => '⭐'],
            ['level' => 5, 'type' => 'avatar_frame', 'value' => 'winter_frame', 'name' => 'Kış Çerçevesi', 'icon' => '🖼️'],
            ['level' => 10, 'type' => 'points', 'value' => '400', 'name' => '+400 Puan', 'icon' => '⭐'],
            ['level' => 10, 'type' => 'badge', 'value' => 'premium_pioneer', 'name' => 'Premium Öncü Rozeti', 'icon' => '🎖️'],
            ['level' => 15, 'type' => 'mystery_box', 'value' => 'gold', 'name' => 'Gold Kutu', 'icon' => '📦'],
            ['level' => 20, 'type' => 'points', 'value' => '1000', 'name' => '+1000 Puan', 'icon' => '⭐'],
            ['level' => 20, 'type' => 'profile_theme', 'value' => 'winter_theme', 'name' => 'Kış Teması', 'icon' => '🎨'],
            ['level' => 25, 'type' => 'mystery_box', 'value' => 'diamond', 'name' => 'Diamond Kutu', 'icon' => '💎'],
            ['level' => 30, 'type' => 'points', 'value' => '3000', 'name' => '+3000 Puan', 'icon' => '⭐'],
            ['level' => 30, 'type' => 'badge', 'value' => 'legendary_champion', 'name' => 'Efsane Şampiyon Rozeti', 'icon' => '👑'],
        ];

        foreach ($freeRewards as $reward) {
            SeasonReward::create([
                'season_id' => $season->id,
                'level' => $reward['level'],
                'is_premium' => false,
                'reward_type' => $reward['type'],
                'reward_value' => $reward['value'],
                'reward_name' => $reward['name'],
                'reward_icon' => $reward['icon'],
            ]);
        }

        foreach ($premiumRewards as $reward) {
            SeasonReward::create([
                'season_id' => $season->id,
                'level' => $reward['level'],
                'is_premium' => true,
                'reward_type' => $reward['type'],
                'reward_value' => $reward['value'],
                'reward_name' => $reward['name'],
                'reward_icon' => $reward['icon'],
            ]);
        }
    }
}
