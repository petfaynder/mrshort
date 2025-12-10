<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Seasons table
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('theme')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->integer('premium_price_points')->default(5000);
            $table->decimal('premium_price_money', 10, 2)->default(50.00);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Season rewards table
        Schema::create('season_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained()->onDelete('cascade');
            $table->integer('level');
            $table->boolean('is_premium')->default(false);
            $table->string('reward_type'); // points, mystery_box, badge, avatar_frame, profile_theme, xp_boost
            $table->string('reward_value');
            $table->string('reward_name');
            $table->string('reward_icon')->nullable();
            $table->timestamps();
        });

        // User season progress table
        Schema::create('user_season_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('season_id')->constrained()->onDelete('cascade');
            $table->integer('xp')->default(0);
            $table->integer('current_level')->default(0);
            $table->boolean('has_premium')->default(false);
            $table->json('claimed_rewards')->nullable(); // Array of reward_ids that have been claimed
            $table->timestamps();

            $table->unique(['user_id', 'season_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_season_progress');
        Schema::dropIfExists('season_rewards');
        Schema::dropIfExists('seasons');
    }
};
