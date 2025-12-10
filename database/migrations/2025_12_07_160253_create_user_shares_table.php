<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_shares')) {
            Schema::create('user_shares', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('share_type'); // weekly_stats, milestone, competition
                $table->string('platform'); // twitter, facebook, instagram, linkedin, whatsapp, telegram
                $table->timestamps();

                $table->index(['user_id', 'share_type', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_shares');
    }
};
