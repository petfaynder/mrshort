<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CpmTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            ['name' => 'Tier 1', 'publisher_cpm_rate' => 5.0000, 'advertiser_cpm_rate' => 6.0000],
            ['name' => 'Tier 2', 'publisher_cpm_rate' => 3.0000, 'advertiser_cpm_rate' => 4.0000],
            ['name' => 'Tier 3', 'publisher_cpm_rate' => 1.0000, 'advertiser_cpm_rate' => 2.0000],
            ['name' => 'Default', 'publisher_cpm_rate' => 0.5000, 'advertiser_cpm_rate' => 1.0000],
        ];

        foreach ($tiers as $tier) {
            \App\Models\CpmTier::firstOrCreate(['name' => $tier['name']], $tier);
        }
    }
}
