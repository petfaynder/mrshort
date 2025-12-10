<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // VIP levels table
        Schema::create('vip_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Bronze, Silver, Gold, Platinum, Diamond
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->decimal('min_earnings', 10, 2)->default(0);
            $table->decimal('max_earnings', 10, 2)->nullable();
            $table->integer('cpm_bonus_percent')->default(0); // +5%, +10%, etc.
            $table->integer('spin_extra')->default(0);
            $table->json('benefits')->nullable(); // Additional benefits as JSON
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // User VIP history table
        Schema::create('user_vip_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('month', 7); // Format: 2025-12
            $table->decimal('earnings', 10, 2)->default(0);
            $table->foreignId('vip_level_id')->nullable()->constrained('vip_levels')->onDelete('set null');
            $table->timestamps();

            $table->unique(['user_id', 'month']);
        });

        // Add current VIP level to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('vip_level_id')->nullable()->after('gamification_points')->constrained('vip_levels')->onDelete('set null');
            $table->decimal('monthly_earnings', 10, 2)->default(0)->after('vip_level_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['vip_level_id']);
            $table->dropColumn(['vip_level_id', 'monthly_earnings']);
        });

        Schema::dropIfExists('user_vip_history');
        Schema::dropIfExists('vip_levels');
    }
};
