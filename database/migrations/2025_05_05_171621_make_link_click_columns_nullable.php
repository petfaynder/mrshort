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
        Schema::table('link_clicks', function (Blueprint $table) {
            $table->string('device_type')->nullable()->change();
            $table->string('os')->nullable()->change();
            $table->string('browser')->nullable()->change();
            $table->string('referrer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_clicks', function (Blueprint $table) {
            $table->string('device_type')->nullable(false)->change();
            $table->string('os')->nullable(false)->change();
            $table->string('browser')->nullable(false)->change();
            $table->string('referrer')->nullable(false)->change();
        });
    }
};
