<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Teams table
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->unique();
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->foreignId('leader_id')->constrained('users')->onDelete('cascade');
            $table->integer('member_count')->default(1);
            $table->integer('total_points')->default(0);
            $table->integer('weekly_points')->default(0);
            $table->boolean('is_public')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Team members table
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['leader', 'officer', 'member'])->default('member');
            $table->integer('contribution_points')->default(0);
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);
        });

        // Team messages table (chat)
        Schema::create('team_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_announcement')->default(false);
            $table->timestamps();
        });

        // Team invites table
        Schema::create('team_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('invited_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_invites');
        Schema::dropIfExists('team_messages');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('teams');
    }
};
