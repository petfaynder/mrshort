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
        Schema::create('gamification_rewards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // e.g., 'badge', 'points', 'virtual_currency', 'special_content', 'avatar_item'
            $table->integer('value')->nullable(); // Puan veya sanal para birimi değeri
            $table->string('image_path')->nullable(); // Rozet veya avatar öğesi için resim yolu
            $table->foreignId('goal_id')->nullable()->constrained('gamification_goals')->onDelete('set null'); // Hangi hedefin tamamlanmasıyla verildiği
            $table->boolean('is_level_reward')->default(false); // Seviye atlama ödülü olup olmadığı
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gamification_rewards');
    }
};
