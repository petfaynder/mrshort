<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('campaign_templates', function (Blueprint $table) {
            $table->json('targeting_countries')->nullable();
            $table->json('targeting_devices')->nullable();
            $table->json('targeting_os')->nullable();
            $table->json('targeting_ages')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('daily_click_limit')->nullable()->default(0);
            $table->integer('frequency_cap')->nullable()->default(0);
            $table->string('frequency_cap_unit')->nullable();
            $table->json('campaign_schedule')->nullable();
            $table->integer('estimated_traffic')->default(0);
            $table->integer('available_traffic')->default(0);
            $table->decimal('estimated_ctr', 8, 2)->default(0.00);
            $table->decimal('estimated_cpc', 8, 2)->default(0.00);
            $table->integer('estimated_reach')->default(0);
            $table->integer('estimated_conversions')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_templates', function (Blueprint $table) {
            $table->dropColumn([
                'targeting_countries',
                'targeting_devices',
                'targeting_os',
                'targeting_ages',
                'start_date',
                'end_date',
                'daily_click_limit',
                'frequency_cap',
                'frequency_cap_unit',
                'campaign_schedule',
                'estimated_traffic',
                'available_traffic',
                'estimated_ctr',
                'estimated_cpc',
                'estimated_reach',
                'estimated_conversions',
            ]);
        });
    }
};
