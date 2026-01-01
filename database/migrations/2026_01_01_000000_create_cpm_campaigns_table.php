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
        Schema::create('cpm_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Campaign identifier (e.g., "New Year 2X Promotion")');
            $table->decimal('multiplier', 5, 2)->default(2.00)->comment('Rate multiplier to apply');
            $table->timestamp('start_date')->comment('Campaign start date/time');
            $table->timestamp('end_date')->comment('Campaign end date/time');
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->json('original_rates_backup')->comment('JSON backup of all CPM rates before campaign');
            $table->timestamps();

            // Index for efficient querying of active campaigns
            $table->index(['status', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpm_campaigns');
    }
};
