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
        Schema::table('cpm_rates', function (Blueprint $table) {
            $table->renameColumn('rate', 'publisher_rate');
            $table->decimal('advertiser_rate', 8, 4)->after('publisher_rate')->default(0.0000);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cpm_rates', function (Blueprint $table) {
            $table->dropColumn('advertiser_rate');
        });
    }
};
