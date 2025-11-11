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
        Schema::create('user_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reward_id')->constrained('gamification_rewards')->onDelete('cascade');
            $table->foreignId('achievement_id')->nullable()->constrained('user_achievements')->onDelete('set null'); // Hangi başarıdan kazanıldığı
            $table->timestamp('awarded_at')->useCurrent();
            $table->boolean('is_claimed')->default(false); // Ödülün kullanıcı tarafından talep edilip edilmediği
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rewards');
    }
};
