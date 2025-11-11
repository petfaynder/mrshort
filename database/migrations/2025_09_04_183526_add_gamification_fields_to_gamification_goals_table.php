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
        Schema::table('gamification_goals', function (Blueprint $table) {
            $table->foreignId('reward_id')->nullable()->constrained('gamification_rewards')->onDelete('set null');
            $table->integer('points')->default(0);
            $table->integer('coins')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gamification_goals', function (Blueprint $table) {
            $table->dropForeign(['reward_id']);
            $table->dropColumn(['reward_id', 'points', 'coins']);
        });
    }
};
