<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 50)->default('lightbulb');
            $table->string('color', 20)->default('blue');
            $table->string('title', 255);
            $table->text('text');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_suggestions');
    }
};
