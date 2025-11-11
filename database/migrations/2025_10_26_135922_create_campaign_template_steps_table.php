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
        Schema::create('campaign_template_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_template_id')->constrained('campaign_templates')->onDelete('cascade');
            $table->integer('step_number');
            $table->enum('step_type', ['interstitial', 'banner_page']);
            $table->integer('wait_time')->default(5);
            $table->boolean('show_popup')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_template_steps');
    }
};
