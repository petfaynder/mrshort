<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Creates the referral_transactions table to track per-click referral commissions.
     *
     * Each row represents a single commission earned by a referrer (user A) when
     * their referred user (user B) generates a paid click. This enables:
     *  - Accurate daily/monthly breakdown of referral income in the dashboard
     *  - Full auditability (which click triggered the commission)
     *  - Easy charting without approximate distributing of referral_earnings total
     */
    public function up(): void
    {
        // Guard: if the table already exists (e.g. after a partial failed migration),
        // drop it cleanly so we can rebuild with the correct schema.
        Schema::dropIfExists('referral_transactions');

        Schema::create('referral_transactions', function (Blueprint $table) {
            $table->id();

            // The user who EARNED the commission (the referrer)
            $table->foreignId('referrer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // The referred user whose click triggered the commission
            $table->foreignId('referred_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // The specific click that triggered this commission (nullable for future manual credits).
            // Stored as a plain column (no FK) for cross-engine compatibility —
            // avoids MySQL error 1824 when link_clicks uses MyISAM or a different collation.
            $table->unsignedBigInteger('link_click_id')->nullable();

            // The base earning of the referred click (before the commission rate is applied)
            $table->decimal('base_click_earning', 14, 10)->default(0);

            // The actual commission amount credited to the referrer
            $table->decimal('amount', 14, 10)->default(0);

            // Commission rate snapshot at the time of the transaction (e.g., 0.15 for 15%)
            $table->decimal('commission_rate', 5, 4)->default(0);

            $table->timestamps();

            // Fast lookup for dashboard charts
            $table->index(['referrer_id', 'created_at']);
            // Index for reverse lookup (which clicks generated commissions)
            $table->index('link_click_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_transactions');
    }
};
