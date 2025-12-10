<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Yarışmalar tablosu
        if (!Schema::hasTable('competitions')) {
            Schema::create('competitions', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('type'); // clicks, links, referrals, earnings
                $table->dateTime('start_date');
                $table->dateTime('end_date');
                $table->json('prize_structure'); // [{"rank": 1, "points": 10000}, {"rank": 2, "points": 5000}...]
                $table->foreignId('badge_reward_id')->nullable()->constrained('gamification_rewards')->nullOnDelete();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['start_date', 'end_date', 'is_active']);
            });
        }

        // Yarışma katılımları
        if (!Schema::hasTable('competition_entries')) {
            Schema::create('competition_entries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('competition_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('score')->default(0);
                $table->integer('rank')->nullable();
                $table->boolean('reward_claimed')->default(false);
                $table->timestamps();

                $table->unique(['competition_id', 'user_id']);
                $table->index(['competition_id', 'score']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_entries');
        Schema::dropIfExists('competitions');
    }
};
