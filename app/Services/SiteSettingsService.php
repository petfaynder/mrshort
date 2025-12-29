<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingsService
{
    /**
     * Get a setting value
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }

    /**
     * Set a setting value
     */
    public function set(string $key, mixed $value, ?string $group = null): void
    {
        SiteSetting::set($key, $value, $group);
    }

    /**
     * Get all settings
     */
    public function all(): array
    {
        return SiteSetting::getAllGrouped();
    }

    /**
     * Get settings by group
     */
    public function group(string $group): array
    {
        return SiteSetting::getByGroup($group);
    }

    /**
     * Check if a setting is enabled (boolean)
     */
    public function isEnabled(string $key): bool
    {
        return (bool) $this->get($key, false);
    }

    /**
     * Bulk update settings
     */
    public function bulkUpdate(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Clear all cache
     */
    public function clearCache(): void
    {
        SiteSetting::clearCache();
    }

    /**
     * Get all settings for a specific group as key-value pairs (for forms)
     */
    public function getGroupForForm(string $group): array
    {
        $settings = SiteSetting::where('group', $group)
            ->orderBy('order')
            ->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = SiteSetting::get($setting->key);
        }

        return $result;
    }

    /**
     * Get setting model by key (for admin editing)
     */
    public function getModel(string $key): ?SiteSetting
    {
        return SiteSetting::where('key', $key)->first();
    }

    /**
     * Create or update a setting with full metadata
     */
    public function upsert(array $data): SiteSetting
    {
        return SiteSetting::updateOrCreate(
            ['key' => $data['key']],
            $data
        );
    }
}
