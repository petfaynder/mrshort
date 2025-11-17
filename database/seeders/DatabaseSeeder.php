<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            CpmTierSeeder::class,
            CpmRateSeeder::class,
            GamificationSeeder::class,
            LeaderboardSeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        User::firstOrCreate(
            ['email' => 'akartolga0@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('170104894'),
                'is_admin' => true
            ]
        );
    }
}
