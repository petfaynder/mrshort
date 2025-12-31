<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * These indexes are critical for performance optimization on high-traffic tables.
     */
    public function up(): void
    {
        // Clicks table indexes - most queried table
        Schema::table('link_clicks', function (Blueprint $table) {
            // Check if indexes don't exist before creating
            $existingIndexes = collect(Schema::getIndexes('link_clicks'))->pluck('name')->toArray();
            
            if (!in_array('link_clicks_link_id_index', $existingIndexes)) {
                $table->index('link_id', 'link_clicks_link_id_index');
            }
            if (!in_array('link_clicks_created_at_index', $existingIndexes)) {
                $table->index('created_at', 'link_clicks_created_at_index');
            }
            if (!in_array('link_clicks_country_id_index', $existingIndexes)) {
                $table->index('country_id', 'link_clicks_country_id_index');
            }
            if (!in_array('link_clicks_is_unique_index', $existingIndexes)) {
                $table->index('is_unique', 'link_clicks_is_unique_index');
            }
            if (!in_array('link_clicks_is_paid_index', $existingIndexes)) {
                $table->index('is_paid', 'link_clicks_is_paid_index');
            }
            // Composite index for common queries
            if (!in_array('link_clicks_link_created_index', $existingIndexes)) {
                $table->index(['link_id', 'created_at'], 'link_clicks_link_created_index');
            }
        });

        // Links table indexes
        Schema::table('links', function (Blueprint $table) {
            $existingIndexes = collect(Schema::getIndexes('links'))->pluck('name')->toArray();
            
            if (!in_array('links_user_id_index', $existingIndexes)) {
                $table->index('user_id', 'links_user_id_index');
            }
            if (!in_array('links_created_at_index', $existingIndexes)) {
                $table->index('created_at', 'links_created_at_index');
            }
            if (!in_array('links_is_active_index', $existingIndexes)) {
                $table->index('is_active', 'links_is_active_index');
            }
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $existingIndexes = collect(Schema::getIndexes('users'))->pluck('name')->toArray();
            
            if (!in_array('users_referred_by_index', $existingIndexes) && Schema::hasColumn('users', 'referred_by')) {
                $table->index('referred_by', 'users_referred_by_index');
            }
            if (!in_array('users_created_at_index', $existingIndexes)) {
                $table->index('created_at', 'users_created_at_index');
            }
        });

        // Sessions table index for faster session lookups
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $existingIndexes = collect(Schema::getIndexes('sessions'))->pluck('name')->toArray();
                
                if (!in_array('sessions_user_id_index', $existingIndexes) && Schema::hasColumn('sessions', 'user_id')) {
                    $table->index('user_id', 'sessions_user_id_index');
                }
                if (!in_array('sessions_last_activity_index', $existingIndexes)) {
                    $table->index('last_activity', 'sessions_last_activity_index');
                }
            });
        }

        // Cache table index (if using database cache)
        if (Schema::hasTable('cache')) {
            Schema::table('cache', function (Blueprint $table) {
                $existingIndexes = collect(Schema::getIndexes('cache'))->pluck('name')->toArray();
                
                if (!in_array('cache_expiration_index', $existingIndexes)) {
                    $table->index('expiration', 'cache_expiration_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_clicks', function (Blueprint $table) {
            $table->dropIndex('link_clicks_link_id_index');
            $table->dropIndex('link_clicks_created_at_index');
            $table->dropIndex('link_clicks_country_id_index');
            $table->dropIndex('link_clicks_is_unique_index');
            $table->dropIndex('link_clicks_is_paid_index');
            $table->dropIndex('link_clicks_link_created_index');
        });

        Schema::table('links', function (Blueprint $table) {
            $table->dropIndex('links_user_id_index');
            $table->dropIndex('links_created_at_index');
            $table->dropIndex('links_is_active_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_referred_by_index');
            $table->dropIndex('users_created_at_index');
        });

        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropIndex('sessions_user_id_index');
                $table->dropIndex('sessions_last_activity_index');
            });
        }

        if (Schema::hasTable('cache')) {
            Schema::table('cache', function (Blueprint $table) {
                $table->dropIndex('cache_expiration_index');
            });
        }
    }
};
