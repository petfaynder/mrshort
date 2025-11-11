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
        Schema::create('level_configurations', function (Blueprint $table) {
            $table->id();
            $table->integer('level')->unique();
            $table->integer('required_experience')->default(0);
            $table->text('rewards_description')->nullable(); // Bu seviyede kazanılacak ödüllerin açıklaması
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_configurations');
    }
};
