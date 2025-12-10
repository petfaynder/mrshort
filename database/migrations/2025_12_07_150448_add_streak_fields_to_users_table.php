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
        // Add streak columns to users if not exist
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'current_streak')) {
                $table->integer('current_streak')->default(0)->after('virtual_currency');
            }
            if (!Schema::hasColumn('users', 'longest_streak')) {
                $table->integer('longest_streak')->default(0)->after('current_streak');
            }
            if (!Schema::hasColumn('users', 'last_streak_date')) {
                $table->date('last_streak_date')->nullable()->after('longest_streak');
            }
            if (!Schema::hasColumn('users', 'streak_freeze_available')) {
                $table->integer('streak_freeze_available')->default(0)->after('last_streak_date');
            }
        });

        // Create streak milestones table for admin customization
        if (!Schema::hasTable('streak_milestones')) {
            Schema::create('streak_milestones', function (Blueprint $table) {
                $table->id();
                $table->integer('days_required');
                $table->integer('points_reward')->default(0);
                $table->foreignId('badge_reward_id')->nullable()->constrained('gamification_rewards')->nullOnDelete();
                $table->string('bonus_type')->nullable();
                $table->integer('bonus_value')->nullable();
                $table->integer('bonus_duration_hours')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique('days_required');
            });
        }

        // Track claimed streak milestones
        if (!Schema::hasTable('user_streak_milestones')) {
            Schema::create('user_streak_milestones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('milestone_id')->constrained('streak_milestones')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['user_id', 'milestone_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_streak_milestones');
        Schema::dropIfExists('streak_milestones');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['current_streak', 'longest_streak', 'last_streak_date', 'streak_freeze_available']);
        });
    }
};
