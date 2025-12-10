<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VipLevel;

class VipLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Bronze',
                'icon' => '🥉',
                'color' => '#CD7F32',
                'min_earnings' => 0,
                'max_earnings' => 24.99,
                'cpm_bonus_percent' => 0,
                'spin_extra' => 0,
                'benefits' => ['Standart özellikler'],
                'order' => 1,
            ],
            [
                'name' => 'Silver',
                'icon' => '🥈',
                'color' => '#C0C0C0',
                'min_earnings' => 25,
                'max_earnings' => 99.99,
                'cpm_bonus_percent' => 5,
                'spin_extra' => 1,
                'benefits' => ['+5% CPM Bonus', '+1 Günlük Spin'],
                'order' => 2,
            ],
            [
                'name' => 'Gold',
                'icon' => '🥇',
                'color' => '#FFD700',
                'min_earnings' => 100,
                'max_earnings' => 249.99,
                'cpm_bonus_percent' => 10,
                'spin_extra' => 2,
                'benefits' => ['+10% CPM Bonus', '+2 Günlük Spin', 'Hızlı para çekme'],
                'order' => 3,
            ],
            [
                'name' => 'Platinum',
                'icon' => '💎',
                'color' => '#E5E4E2',
                'min_earnings' => 250,
                'max_earnings' => 499.99,
                'cpm_bonus_percent' => 15,
                'spin_extra' => 3,
                'benefits' => ['+15% CPM Bonus', '+3 Günlük Spin', 'Hızlı para çekme', 'Öncelikli destek'],
                'order' => 4,
            ],
            [
                'name' => 'Diamond',
                'icon' => '👑',
                'color' => '#B9F2FF',
                'min_earnings' => 500,
                'max_earnings' => null,
                'cpm_bonus_percent' => 20,
                'spin_extra' => 5,
                'benefits' => ['+20% CPM Bonus', '+5 Günlük Spin', 'Hızlı para çekme', 'Öncelikli destek', 'Özel Diamond rozeti', 'VIP sohbet erişimi'],
                'order' => 5,
            ],
        ];

        foreach ($levels as $level) {
            VipLevel::updateOrCreate(
                ['name' => $level['name']],
                $level
            );
        }
    }
}
