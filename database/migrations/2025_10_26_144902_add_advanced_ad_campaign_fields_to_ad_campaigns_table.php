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
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->unsignedInteger('daily_click_limit')->nullable();
            $table->unsignedInteger('frequency_cap')->nullable();
            $table->enum('frequency_cap_unit', ['hour', 'day', 'week', 'month'])->nullable();
            $table->unsignedBigInteger('estimated_traffic')->default(0);
            $table->unsignedBigInteger('available_traffic')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'start_date',
                'end_date',
                'daily_click_limit',
                'frequency_cap',
                'frequency_cap_unit',
                'estimated_traffic',
                'available_traffic',
            ]);
        });
    }
};
