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
        Schema::table('users', function (Blueprint $table) {
            // Telegram Traffic Bonus System
            $table->boolean('telegram_bonus_enabled')->default(false)->after('deactivated_at');
            $table->timestamp('telegram_bonus_enabled_at')->nullable()->after('telegram_bonus_enabled');
            $table->timestamp('telegram_bonus_verified_at')->nullable()->after('telegram_bonus_enabled_at');
            $table->timestamp('telegram_bonus_failed_at')->nullable()->after('telegram_bonus_verified_at');
            $table->unsignedInteger('telegram_verification_clicks')->default(0)->after('telegram_bonus_failed_at');
            $table->decimal('telegram_referrer_match_rate', 5, 2)->nullable()->after('telegram_verification_clicks');
            $table->boolean('telegram_bonus_decision_made')->default(false)->after('telegram_referrer_match_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'telegram_bonus_enabled',
                'telegram_bonus_enabled_at',
                'telegram_bonus_verified_at',
                'telegram_bonus_failed_at',
                'telegram_verification_clicks',
                'telegram_referrer_match_rate',
                'telegram_bonus_decision_made',
            ]);
        });
    }
};
