<?php

use App\Models\SiteSetting;

if (!function_exists('formatMoney')) {
    /**
     * Format a monetary value according to site settings
     * 
     * @param float|int|string $amount The amount to format
     * @param bool $showSymbol Whether to show the currency symbol
     * @return string Formatted money string
     */
    function formatMoney($amount, bool $showSymbol = true): string
    {
        $symbol = SiteSetting::get('currency_symbol', '$');
        $decimals = (int) SiteSetting::get('price_decimals', 2);
        $position = SiteSetting::get('currency_position', 'before');
        $code = SiteSetting::get('currency_code', 'USD');
        
        // Format the number
        $formatted = number_format((float) $amount, $decimals);
        
        if (!$showSymbol) {
            return $formatted . ' ' . $code;
        }
        
        // Return with symbol in correct position
        return $position === 'before' 
            ? $symbol . $formatted 
            : $formatted . ' ' . $symbol;
    }
}

if (!function_exists('setting')) {
    /**
     * Helper function to get a site setting
     * 
     * @param string $key The setting key
     * @param mixed $default Default value if setting not found
     * @return mixed The setting value
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }
}

if (!function_exists('settingEnabled')) {
    /**
     * Check if a boolean setting is enabled
     * 
     * @param string $key The setting key
     * @return bool Whether the setting is enabled
     */
    function settingEnabled(string $key): bool
    {
        return (bool) SiteSetting::get($key, false);
    }
}

if (!function_exists('activeDomain')) {
    /**
     * Get the active domain for short links
     * 
     * @return \App\Models\Domain|null The active domain
     */
    function activeDomain(): ?\App\Models\Domain
    {
        return \App\Models\Domain::getActive();
    }
}

if (!function_exists('shortLinkUrl')) {
    /**
     * Generate a short link URL using the active domain
     * 
     * @param string $code The link code
     * @return string The full short link URL
     */
    function shortLinkUrl(string $code): string
    {
        $domain = activeDomain();
        
        if ($domain) {
            return $domain->getShortUrl($code);
        }
        
        // Fallback to app URL if no active domain
        return config('app.url') . '/' . $code;
    }
}
