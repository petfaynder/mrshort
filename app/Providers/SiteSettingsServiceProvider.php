<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SiteSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\SiteSettingsService::class, function ($app) {
            return new \App\Services\SiteSettingsService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only run if table exists (prevents errors during migrations)
        if (!$this->app->runningInConsole() || $this->app->runningUnitTests()) {
            $this->overrideConfigs();
        }
    }

    /**
     * Override Laravel config values with database settings
     */
    protected function overrideConfigs(): void
    {
        try {
            if (!Schema::hasTable('site_settings')) {
                return;
            }

            // App settings
            $this->overrideIfSet('site_name', 'app.name');
            $this->overrideIfSet('timezone', 'app.timezone');
            $this->overrideIfSet('default_language', 'app.locale');

            // Mail settings
            $this->overrideIfSet('mail_from_address', 'mail.from.address');
            $this->overrideIfSet('mail_from_name', 'mail.from.name');
            $this->overrideIfSet('mail_host', 'mail.mailers.smtp.host');
            $this->overrideIfSet('mail_port', 'mail.mailers.smtp.port');
            $this->overrideIfSet('mail_username', 'mail.mailers.smtp.username');
            $this->overrideIfSet('mail_password', 'mail.mailers.smtp.password');
            $this->overrideIfSet('mail_encryption', 'mail.mailers.smtp.encryption');

        } catch (\Exception $e) {
            // Silently fail if database is not available
            // This can happen during initial setup or migrations
        }
    }

    /**
     * Override a config value if the setting exists
     */
    protected function overrideIfSet(string $settingKey, string $configKey): void
    {
        $value = SiteSetting::get($settingKey);
        
        if ($value !== null && $value !== '') {
            config([$configKey => $value]);
        }
    }
}
