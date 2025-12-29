<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Country;
use Jenssegers\Agent\Agent;

class VisitorDetectionService
{
    /**
     * Ziyaretçi bilgilerini tespit et
     */
    public static function detect(Request $request): array
    {
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());
        
        return [
            'country' => self::detectCountry($request),
            'device' => self::detectDevice($agent),
            'os' => self::detectOS($agent),
            'browser' => self::detectBrowser($agent),
            'ip' => $request->ip(),
        ];
    }
    
    /**
     * Ülke tespiti - LinkClick kayıtlarından veya IP'den
     */
    protected static function detectCountry(Request $request): ?string
    {
        // Önce session'dan kontrol et
        if (session()->has('visitor_country')) {
            return session('visitor_country');
        }
        
        // IP tabanlı ülke tespiti için geoip veya 3rd party API kullanılabilir
        // Şimdilik basit bir header kontrolü yapalım
        $countryCode = null;
        
        // CloudFlare CF-IPCountry header'ı
        if ($request->header('CF-IPCountry')) {
            $countryCode = strtoupper($request->header('CF-IPCountry'));
        }
        
        // X-Country header (opsiyonel)
        if (!$countryCode && $request->header('X-Country')) {
            $countryCode = strtoupper($request->header('X-Country'));
        }
        
        // Ülke kodunu doğrula
        if ($countryCode && $countryCode !== 'XX') {
            $country = Country::where('iso_code', $countryCode)->first();
            if ($country) {
                session(['visitor_country' => $countryCode]);
                return $countryCode;
            }
        }
        
        return null; // Tespit edilemedi
    }
    
    /**
     * Cihaz türü tespiti
     */
    protected static function detectDevice(Agent $agent): string
    {
        if ($agent->isTablet()) {
            return 'Tablet';
        }
        
        if ($agent->isMobile()) {
            return 'Mobile';
        }
        
        return 'Desktop';
    }
    
    /**
     * İşletim sistemi tespiti
     */
    protected static function detectOS(Agent $agent): string
    {
        $platform = $agent->platform();
        
        // Normalize OS names to match admin panel values
        $osMap = [
            'Windows' => 'Windows',
            'OS X' => 'macOS',
            'macOS' => 'macOS',
            'iOS' => 'iOS',
            'AndroidOS' => 'Android',
            'Android' => 'Android',
            'Linux' => 'Linux',
            'Ubuntu' => 'Linux',
            'ChromeOS' => 'Other',
        ];
        
        return $osMap[$platform] ?? 'Other';
    }
    
    /**
     * Tarayıcı tespiti
     */
    protected static function detectBrowser(Agent $agent): string
    {
        return $agent->browser() ?? 'Unknown';
    }
    
    /**
     * Ziyaretçi hedefleme kurallarına uyuyor mu kontrol et
     */
    public static function matchesTargeting(array $visitorInfo, $template): bool
    {
        // Ülke kontrolü
        if (!self::matchesCountry($visitorInfo['country'], $template->targeting_countries)) {
            return false;
        }
        
        // Cihaz kontrolü
        if (!self::matchesDevice($visitorInfo['device'], $template->targeting_devices)) {
            return false;
        }
        
        // OS kontrolü
        if (!self::matchesOS($visitorInfo['os'], $template->targeting_os)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Ülke eşleşmesi kontrolü
     */
    protected static function matchesCountry(?string $visitorCountry, ?array $targetCountries): bool
    {
        // Hedefleme boşsa veya "ALL" içeriyorsa herkes uyar
        if (empty($targetCountries) || in_array('ALL', $targetCountries)) {
            return true;
        }
        
        // Ziyaretçi ülkesi tespit edilemezse geç (veya reddet - policy kararı)
        if ($visitorCountry === null) {
            return true; // Tespit edilemezse izin ver
        }
        
        return in_array($visitorCountry, $targetCountries);
    }
    
    /**
     * Cihaz eşleşmesi kontrolü
     */
    protected static function matchesDevice(string $visitorDevice, ?array $targetDevices): bool
    {
        // Hedefleme boşsa herkes uyar
        if (empty($targetDevices)) {
            return true;
        }
        
        return in_array($visitorDevice, $targetDevices);
    }
    
    /**
     * OS eşleşmesi kontrolü
     */
    protected static function matchesOS(string $visitorOS, ?array $targetOS): bool
    {
        // Hedefleme boşsa herkes uyar
        if (empty($targetOS)) {
            return true;
        }
        
        return in_array($visitorOS, $targetOS);
    }
}
