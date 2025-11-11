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
        Schema::table('campaign_template_steps', function (Blueprint $table) {
            $table->dropForeign(['popup_ad_campaign_id']);
            $table->dropColumn('popup_ad_campaign_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_template_steps', function (Blueprint $table) {
            $table->foreignId('popup_ad_campaign_id')->nullable()->constrained('ad_campaigns')->onDelete('set null');
        });
    }
};
