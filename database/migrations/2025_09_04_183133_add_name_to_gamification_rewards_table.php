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
        Schema::table('gamification_rewards', function (Blueprint $table) {
            $table->string('name')->after('id'); // 'id' sütunundan sonra 'name' sütununu ekle
            $table->dropColumn('title'); // 'title' sütununu kaldır
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gamification_rewards', function (Blueprint $table) {
            $table->string('title')->after('id'); // 'title' sütununu geri ekle
            $table->dropColumn('name'); // 'name' sütununu kaldır
        });
    }
};
