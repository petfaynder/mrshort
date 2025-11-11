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
        Schema::create('campaign_template_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_template_step_id')->constrained('campaign_template_steps')->onDelete('cascade');
            $table->enum('ad_type', ['banner', 'popup', 'html', 'third_party']);
            $table->json('ad_data')->nullable(); // Bu JSON, Filament formları aracılığıyla doldurulacak.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_template_ads');
    }
};
