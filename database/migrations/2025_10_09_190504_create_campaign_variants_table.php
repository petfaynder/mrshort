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
        Schema::create('campaign_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('ad_campaigns')->onDelete('cascade');
            $table->string('name');
            $table->string('variant_key')->unique();
            $table->integer('traffic_split')->default(50); // Trafik yüzdesi
            $table->boolean('is_control')->default(false); // Kontrol grubu mu?
            $table->json('settings')->nullable(); // Varyasyon ayarları
            $table->string('status')->default('active'); // active, paused, completed
            $table->json('performance_data')->nullable(); // Performans metrikleri
            $table->timestamps();

            // Indexler
            $table->index(['campaign_id', 'status']);
            $table->index(['is_control', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_variants');
    }
};
