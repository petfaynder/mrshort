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
        // Daily challenge pool - admin tarafından yönetilen görev havuzu
        Schema::create('daily_challenge_pool', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // shorten_links, get_clicks, different_countries, share_links
            $table->integer('target_value');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('points_reward')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // User daily challenges - her kullanıcının günlük görevleri
        Schema::create('user_daily_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('challenge_date');
            $table->json('challenge_ids'); // Günün görev ID'leri
            $table->json('progress')->nullable(); // Her görev için ilerleme
            $table->json('completed_ids')->nullable(); // Tamamlanan görev ID'leri
            $table->boolean('bonus_claimed')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'challenge_date']);
            $table->index('challenge_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_daily_challenges');
        Schema::dropIfExists('daily_challenge_pool');
    }
};
