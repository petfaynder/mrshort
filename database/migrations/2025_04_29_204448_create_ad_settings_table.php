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
        Schema::create('ad_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->integer('admin_campaign_weight')->default(70);
            $table->integer('user_campaign_weight')->default(30);
            $table->integer('admin_popup_weight')->default(60);
            $table->integer('user_popup_weight')->default(40);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_settings', function (Blueprint $table) {
            $table->dropColumn(['admin_campaign_weight', 'user_campaign_weight', 'admin_popup_weight', 'user_popup_weight']);
        });
        // Eğer tablo tamamen silinecekse aşağıdaki satırı kullanın:
        // Schema::dropIfExists('ad_settings');
    }
};
