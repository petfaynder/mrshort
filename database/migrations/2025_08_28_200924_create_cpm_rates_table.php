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
        Schema::create('cpm_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpm_tier_id')->nullable()->constrained('cpm_tiers')->onDelete('cascade');
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade');
            $table->decimal('rate', 8, 4);
            $table->timestamps();

            $table->unique(['cpm_tier_id', 'country_id']); // Bir katman veya ülke için sadece bir oran olabilir
        });

        // countries tablosuna foreign key ekle
        Schema::table('countries', function (Blueprint $table) {
            $table->foreign('cpm_tier_id')->references('id')->on('cpm_tiers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpm_rates');
    }
};
