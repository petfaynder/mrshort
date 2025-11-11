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
        Schema::create('campaign_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category')->default('custom');
            $table->boolean('is_active')->default(true);
            $table->json('steps')->nullable(); // Kampanya adımları şablonu
            $table->json('targeting_rules')->nullable(); // Varsayılan hedefleme kuralları
            $table->decimal('default_budget', 10, 2)->default(100.00); // Varsayılan günlük bütçe
            $table->json('estimated_performance')->nullable(); // Tahmini performans metrikleri
            $table->integer('sort_order')->default(0); // Sıralama için
            $table->integer('usage_count')->default(0); // Kullanım sayısı
            $table->timestamps();

            // Indexler
            $table->index(['category', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_templates');
    }
};
