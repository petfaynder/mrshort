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
            // Get existing indexes
            $existingIndexes = collect(Schema::getIndexes('link_clicks'))->pluck('name')->toArray();
            
            // link_id already has foreign key index, but add if missing
            if (!in_array('link_clicks_link_id_index', $existingIndexes) && !in_array('link_clicks_link_id_foreign', $existingIndexes)) {
                if (Schema::hasColumn('link_clicks', 'link_id')) {
                    $table->index('link_id', 'link_clicks_link_id_index');
                }
            }
            if (!in_array('link_clicks_created_at_index', $existingIndexes)) {
                $table->index('created_at', 'link_clicks_created_at_index');
            }
            if (!in_array('link_clicks_country_id_index', $existingIndexes)) {
                if (Schema::hasColumn('link_clicks', 'country_id')) {
                    $table->index('country_id', 'link_clicks_country_id_index');
                }
            }
            if (!in_array('link_clicks_is_bot_index', $existingIndexes)) {
                if (Schema::hasColumn('link_clicks', 'is_bot')) {
                    $table->index('is_bot', 'link_clicks_is_bot_index');
                }
            }
        });

        // Links table indexes
        Schema::table('links', function (Blueprint $table) {
            $existingIndexes = collect(Schema::getIndexes('links'))->pluck('name')->toArray();
            
            if (!in_array('links_user_id_index', $existingIndexes) && !in_array('links_user_id_foreign', $existingIndexes)) {
                if (Schema::hasColumn('links', 'user_id')) {
                    $table->index('user_id', 'links_user_id_index');
                }
            }
            if (!in_array('links_created_at_index', $existingIndexes)) {
                $table->index('created_at', 'links_created_at_index');
            }
            if (!in_array('links_is_active_index', $existingIndexes)) {
                if (Schema::hasColumn('links', 'is_active')) {
                    $table->index('is_active', 'links_is_active_index');
                }
            }
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $existingIndexes = collect(Schema::getIndexes('users'))->pluck('name')->toArray();
            
            if (!in_array('users_referred_by_index', $existingIndexes)) {
                if (Schema::hasColumn('users', 'referred_by')) {
                    $table->index('referred_by', 'users_referred_by_index');
                }
            }
            if (!in_array('users_created_at_index', $existingIndexes)) {
                $table->index('created_at', 'users_created_at_index');
            }
        });

        // Sessions table index for faster session lookups
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $existingIndexes = collect(Schema::getIndexes('sessions'))->pluck('name')->toArray();
                
                if (!in_array('sessions_user_id_index', $existingIndexes)) {
                    if (Schema::hasColumn('sessions', 'user_id')) {
                        $table->index('user_id', 'sessions_user_id_index');
                    }
                }
                if (!in_array('sessions_last_activity_index', $existingIndexes)) {
                    if (Schema::hasColumn('sessions', 'last_activity')) {
                        $table->index('last_activity', 'sessions_last_activity_index');
                    }
                }
            });
        }

        // Cache table index (if using database cache)
        if (Schema::hasTable('cache')) {
            Schema::table('cache', function (Blueprint $table) {
                $existingIndexes = collect(Schema::getIndexes('cache'))->pluck('name')->toArray();
                
                if (!in_array('cache_expiration_index', $existingIndexes)) {
                    if (Schema::hasColumn('cache', 'expiration')) {
                        $table->index('expiration', 'cache_expiration_index');
                    }
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
            $existingIndexes = collect(Schema::getIndexes('link_clicks'))->pluck('name')->toArray();
            if (in_array('link_clicks_link_id_index', $existingIndexes)) $table->dropIndex('link_clicks_link_id_index');
            if (in_array('link_clicks_created_at_index', $existingIndexes)) $table->dropIndex('link_clicks_created_at_index');
            if (in_array('link_clicks_country_id_index', $existingIndexes)) $table->dropIndex('link_clicks_country_id_index');
            if (in_array('link_clicks_is_bot_index', $existingIndexes)) $table->dropIndex('link_clicks_is_bot_index');
        });

        Schema::table('links', function (Blueprint $table) {
            $existingIndexes = collect(Schema::getIndexes('links'))->pluck('name')->toArray();
            if (in_array('links_user_id_index', $existingIndexes)) $table->dropIndex('links_user_id_index');
            if (in_array('links_created_at_index', $existingIndexes)) $table->dropIndex('links_created_at_index');
            if (in_array('links_is_active_index', $existingIndexes)) $table->dropIndex('links_is_active_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $existingIndexes = collect(Schema::getIndexes('users'))->pluck('name')->toArray();
            if (in_array('users_referred_by_index', $existingIndexes)) $table->dropIndex('users_referred_by_index');
            if (in_array('users_created_at_index', $existingIndexes)) $table->dropIndex('users_created_at_index');
        });

        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $existingIndexes = collect(Schema::getIndexes('sessions'))->pluck('name')->toArray();
                if (in_array('sessions_user_id_index', $existingIndexes)) $table->dropIndex('sessions_user_id_index');
                if (in_array('sessions_last_activity_index', $existingIndexes)) $table->dropIndex('sessions_last_activity_index');
            });
        }

        if (Schema::hasTable('cache')) {
            Schema::table('cache', function (Blueprint $table) {
                $existingIndexes = collect(Schema::getIndexes('cache'))->pluck('name')->toArray();
                if (in_array('cache_expiration_index', $existingIndexes)) $table->dropIndex('cache_expiration_index');
            });
        }
    }
};

