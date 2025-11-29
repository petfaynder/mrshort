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
            $table->string('payment_status')->default('unpaid')->after('is_active'); // unpaid, paid, failed, pending
            $table->string('payment_provider')->nullable()->after('payment_status'); // balance, cryptomus
            $table->string('external_payment_id')->nullable()->after('payment_provider'); // for cryptomus order id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_provider', 'external_payment_id']);
        });
    }
};
