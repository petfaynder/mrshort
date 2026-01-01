<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add "Other Countries" as a fallback country
        DB::table('countries')->insert([
            'name' => 'Other Countries',
            'iso_code' => 'XX',
            'cpm_tier_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Get the newly created country
        $otherCountry = DB::table('countries')->where('iso_code', 'XX')->first();

        if ($otherCountry) {
            // Add default CPM rate for "Other Countries" (low Tier 3 rates)
            DB::table('cpm_rates')->insert([
                'country_id' => $otherCountry->id,
                'cpm_tier_id' => null,
                'publisher_rate' => 1.50, // Basic rate for undefined countries
                'advertiser_rate' => 0.50, // Conservative advertiser rate
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove CPM rate first (foreign key constraint)
        $otherCountry = DB::table('countries')->where('iso_code', 'XX')->first();
        
        if ($otherCountry) {
            DB::table('cpm_rates')->where('country_id', $otherCountry->id)->delete();
        }

        // Remove the country
        DB::table('countries')->where('iso_code', 'XX')->delete();
    }
};
