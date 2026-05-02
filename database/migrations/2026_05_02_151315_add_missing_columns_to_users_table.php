<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds columns referenced by UserResource.php in the Filament admin panel
     * that were missing from the users table schema.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Subscription plan name (e.g., 'free', 'basic', 'pro', 'vip')
            if (!Schema::hasColumn('users', 'plan')) {
                $table->string('plan', 50)->nullable()->default('free');
            }

            // Subscription expiration date
            if (!Schema::hasColumn('users', 'expiration')) {
                $table->timestamp('expiration')->nullable();
            }

            // IP address at the time of registration (for fraud detection)
            if (!Schema::hasColumn('users', 'register_ip')) {
                $table->string('register_ip', 45)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumnIfExists('plan');
            $table->dropColumnIfExists('expiration');
            $table->dropColumnIfExists('register_ip');
        });
    }
};
