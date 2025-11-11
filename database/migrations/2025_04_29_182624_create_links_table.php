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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Foreign key to users table
            $table->text('original_url'); // Original long URL
            $table->string('code')->unique(); // Unique short code (slug)
            $table->unsignedInteger('clicks')->default(0); // Click counter
            $table->string('title')->nullable(); // Optional title for the link
            $table->timestamp('expires_at')->nullable(); // Optional expiration date/time
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
