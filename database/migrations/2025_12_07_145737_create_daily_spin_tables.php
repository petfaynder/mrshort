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
        // Daily Spin Prizes - Çark dilimleri ve ödülleri
        Schema::create('daily_spin_prizes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ödül adı
            $table->enum('type', ['points', 'reward_id', 'streak_freeze', 'xp_multiplier'])->default('points');
            $table->integer('value')->default(0); // Puan miktarı veya reward_id
            $table->decimal('probability', 5, 2)->default(0); // Olasılık (0-100)
            $table->string('color')->default('#6B7280'); // Dilim rengi
            $table->string('icon')->nullable(); // İkon (opsiyonel)
            $table->boolean('is_jackpot')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // User Spins - Kullanıcı spin geçmişi
        Schema::create('user_spins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('prize_id')->constrained('daily_spin_prizes')->onDelete('cascade');
            $table->integer('prize_value')->default(0); // Kazanılan değer
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_spins');
        Schema::dropIfExists('daily_spin_prizes');
    }
};
