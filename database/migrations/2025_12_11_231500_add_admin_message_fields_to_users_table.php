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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_admin_message')->default(false)->after('is_admin');
            $table->unsignedBigInteger('admin_message_ticket_id')->nullable()->after('has_admin_message');
            
            $table->foreign('admin_message_ticket_id')->references('id')->on('tickets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['admin_message_ticket_id']);
            $table->dropColumn(['has_admin_message', 'admin_message_ticket_id']);
        });
    }
};
