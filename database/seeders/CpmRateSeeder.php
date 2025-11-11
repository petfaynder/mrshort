<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CpmRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Varsayılan katmanı bul
        $defaultTier = \App\Models\CpmTier::where('name', 'Default')->first();

        if ($defaultTier) {
            // Varsayılan katman için örnek bir CPM oranı ekle
            \App\Models\CpmRate::firstOrCreate(
                ['cpm_tier_id' => $defaultTier->id, 'country_id' => null],
                ['publisher_rate' => $defaultTier->publisher_cpm_rate, 'advertiser_rate' => $defaultTier->advertiser_cpm_rate]
            );
        }

        // Diğer katmanlar veya ülkelere özel oranlar buraya eklenebilir.
        // Örneğin:
        // $usCountry = \App\Models\Country::where('iso_code', 'US')->first();
        // if ($usCountry) {
        //     \App\Models\CpmRate::firstOrCreate(
        //         ['country_id' => $usCountry->id],
        //         ['rate' => 10.0000]
        //     );
        // }
    }
}
