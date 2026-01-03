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
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('action', 100)->after('user_id')->index();
            $table->text('description')->nullable()->after('action');
            $table->string('model_type')->nullable()->after('description');
            $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            $table->json('old_values')->nullable()->after('model_id');
            $table->json('new_values')->nullable()->after('old_values');
            $table->string('ip_address', 45)->nullable()->after('new_values');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->string('url')->nullable()->after('user_agent');
            $table->string('method', 10)->nullable()->after('url');

            // Index for date-based queries
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['action']);
            $table->dropIndex(['created_at']);
            $table->dropColumn([
                'user_id', 'action', 'description', 'model_type', 'model_id',
                'old_values', 'new_values', 'ip_address', 'user_agent', 'url', 'method'
            ]);
        });
    }
};
