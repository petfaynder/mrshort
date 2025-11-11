<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(20)->create()->each(function ($user) {
            $user->update([
                'gamification_points' => rand(100, 10000)
            ]);
        });
    }
}