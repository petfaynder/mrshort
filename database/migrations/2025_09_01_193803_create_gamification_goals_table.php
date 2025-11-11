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
        Schema::create('gamification_goals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // e.g., 'shorten_links', 'clicks', 'shares'
            $table->integer('target_value');
            $table->string('difficulty_level')->nullable(); // e.g., 'easy', 'medium', 'hard'
            $table->string('category')->nullable(); // e.g., 'daily', 'weekly', 'milestone'
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Eğer hedefler kullanıcıya özel ise
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gamification_goals');
    }
};
