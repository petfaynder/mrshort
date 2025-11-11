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
        Schema::table('cpm_tiers', function (Blueprint $table) {
            $table->renameColumn('default_cpm_rate', 'publisher_cpm_rate');
            $table->decimal('advertiser_cpm_rate', 8, 4)->default(0.0000)->after('publisher_cpm_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cpm_tiers', function (Blueprint $table) {
            $table->dropColumn('default_advertiser_cpm_rate');
            $table->renameColumn('default_publisher_cpm_rate', 'default_cpm_rate');
        });
    }
};
