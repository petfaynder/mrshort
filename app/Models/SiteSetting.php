<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SiteSetting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'label',
        'description',
        'options',
        'is_encrypted',
        'order',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = "site_setting_{$key}";
        
        return Cache::remember($cacheKey, self::getCacheTtl('settings'), function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            return self::castValue($setting);
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value, ?string $group = null): void
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            // Encrypt if needed
            if ($setting->is_encrypted && $value) {
                $value = Crypt::encryptString($value);
            }
            
            $setting->update(['value' => $value]);
        } else {
            // Create new setting
            self::create([
                'group' => $group ?? 'general',
                'key' => $key,
                'value' => $value,
                'type' => 'string',
            ]);
        }
        
        // Clear cache
        Cache::forget("site_setting_{$key}");
        Cache::forget('site_settings_all');
    }

    /**
     * Get all settings grouped
     */
    public static function getAllGrouped(): array
    {
        return Cache::remember('site_settings_all', self::getCacheTtl('settings'), function () {
            $settings = self::orderBy('group')->orderBy('order')->get();
            
            $grouped = [];
            foreach ($settings as $setting) {
                $grouped[$setting->group][$setting->key] = self::castValue($setting);
            }
            
            return $grouped;
        });
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group): array
    {
        return Cache::remember("site_settings_group_{$group}", self::getCacheTtl('settings'), function () use ($group) {
            $settings = self::where('group', $group)->orderBy('order')->get();
            
            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting);
            }
            
            return $result;
        });
    }

    /**
     * Cast value based on type
     */
    protected static function castValue(SiteSetting $setting): mixed
    {
        $value = $setting->value;
        
        // Decrypt if encrypted
        if ($setting->is_encrypted && $value) {
            try {
                $value = Crypt::decryptString($value);
            } catch (\Exception $e) {
                // Return as-is if decryption fails
            }
        }
        
        // Cast based on type
        return match ($setting->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number', 'integer' => (int) $value,
            'float', 'decimal' => (float) $value,
            'json', 'array' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        $settings = self::all();
        
        foreach ($settings as $setting) {
            Cache::forget("site_setting_{$setting->key}");
        }
        
        Cache::forget('site_settings_all');
        
        // Clear group caches
        $groups = self::distinct('group')->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("site_settings_group_{$group}");
        }
    }

    /**
     * Get options as array (for select fields)
     */
    public function getOptionsArray(): array
    {
        if (!$this->options) {
            return [];
        }
        
        $decoded = json_decode($this->options, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Get cache TTL for a specific type
     */
    public static function getCacheTtl(string $type = 'default'): int
    {
        $key = "cache_ttl_{$type}";
        $default = match($type) {
            'leaderboard' => 3600,
            'settings' => 3600,
            default => 3600,
        };
        
        return (int) self::get($key, $default);
    }

    /**
     * Check if a specific queue type is enabled
     */
    public static function isQueueEnabled(string $type): bool
    {
        $key = "queue_{$type}";
        return (bool) self::get($key, true);
    }

    /**
     * Check if emails should be queued
     */
    public static function shouldQueueEmails(): bool
    {
        return self::isQueueEnabled('emails');
    }

    /**
     * Check if analytics should be queued
     */
    public static function shouldQueueAnalytics(): bool
    {
        return self::isQueueEnabled('analytics');
    }

    /**
     * Check if webhooks should be queued
     */
    public static function shouldQueueWebhooks(): bool
    {
        return self::isQueueEnabled('webhooks');
    }
}

