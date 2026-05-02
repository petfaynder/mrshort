<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds bonus_amount to link_clicks so that EarningsChart can accurately
     * reflect per-click bonus earnings (VIP + Telegram) separately from base CPM.
     */
    public function up(): void
    {
        Schema::table('link_clicks', function (Blueprint $table) {
            // Stores the total bonus amount earned for this click (VIP + Telegram bonus).
            // Stored as actual dollar amount, NOT as CPM rate.
            // Example: base earning = 0.001, VIP +20% = 0.0002, telegram +10% = 0.00012
            // bonus_amount = 0.00032
            $table->decimal('bonus_amount', 14, 10)->default(0)->after('cpm_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_clicks', function (Blueprint $table) {
            $table->dropColumn('bonus_amount');
        });
    }
};
