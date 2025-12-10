<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mystery box types
        if (!Schema::hasTable('mystery_boxes')) {
            Schema::create('mystery_boxes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('tier'); // bronze, silver, gold, diamond
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->string('color')->default('#6b7280');
                $table->json('contents'); // [{type: 'points', min: 100, max: 500, probability: 80}, {type: 'reward_id', value: 5, probability: 20}]
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // User mystery boxes (inventory)
        if (!Schema::hasTable('user_mystery_boxes')) {
            Schema::create('user_mystery_boxes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('mystery_box_id')->constrained()->onDelete('cascade');
                $table->string('source'); // links_milestone, clicks_milestone, weekly_challenge, monthly_top10
                $table->boolean('is_opened')->default(false);
                $table->json('won_contents')->nullable();
                $table->timestamp('opened_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'is_opened']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_mystery_boxes');
        Schema::dropIfExists('mystery_boxes');
    }
};
