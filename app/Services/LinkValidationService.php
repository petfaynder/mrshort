<?php

namespace App\Services;

use App\Models\SiteSetting;

class LinkValidationService
{
    /**
     * Validate URL against site settings rules
     */
    public function validate(string $url): array
    {
        $errors = [];
        
        // Check banned words
        $bannedWords = $this->getBannedWords();
        foreach ($bannedWords as $word) {
            if (stripos($url, $word) !== false) {
                $errors[] = "URL contains banned word: {$word}";
                break;
            }
        }
        
        // Check disallowed domains
        $disallowedDomains = $this->getDisallowedDomains();
        $urlHost = parse_url($url, PHP_URL_HOST);
        if ($urlHost) {
            foreach ($disallowedDomains as $domain) {
                if (stripos($urlHost, $domain) !== false) {
                    $errors[] = "Links from {$domain} are not allowed";
                    break;
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Check if alias is valid
     */
    public function validateAlias(string $alias): array
    {
        $errors = [];
        
        $minLength = (int) setting('alias_min_length', 4);
        $maxLength = (int) setting('alias_max_length', 8);
        $reservedAliases = $this->getReservedAliases();
        
        if (strlen($alias) < $minLength) {
            $errors[] = "Alias must be at least {$minLength} characters";
        }
        
        if (strlen($alias) > $maxLength) {
            $errors[] = "Alias must not exceed {$maxLength} characters";
        }
        
        if (in_array(strtolower($alias), array_map('strtolower', $reservedAliases))) {
            $errors[] = "This alias is reserved and cannot be used";
        }
        
        return $errors;
    }
    
    /**
     * Check if URL is safe (Google Safe Browsing, PhishTank)
     */
    public function checkUrlSafety(string $url): array
    {
        $errors = [];
        
        if (!setting('url_safety_enabled', false)) {
            return $errors;
        }
        
        // Google Safe Browsing check
        $googleKey = setting('google_safe_browsing_key');
        if ($googleKey) {
            $isSafe = $this->checkGoogleSafeBrowsing($url, $googleKey);
            if (!$isSafe) {
                $errors[] = "URL is flagged as unsafe by Google Safe Browsing";
            }
        }
        
        // PhishTank check
        $phishtankKey = setting('phishtank_api_key');
        if ($phishtankKey) {
            $isPhishing = $this->checkPhishTank($url, $phishtankKey);
            if ($isPhishing) {
                $errors[] = "URL is flagged as phishing by PhishTank";
            }
        }
        
        return $errors;
    }
    
    protected function getBannedWords(): array
    {
        $words = setting('banned_words', '');
        return array_filter(array_map('trim', explode(',', $words)));
    }
    
    protected function getDisallowedDomains(): array
    {
        $domains = setting('disallowed_domains', '');
        return array_filter(array_map('trim', explode(',', $domains)));
    }
    
    protected function getReservedAliases(): array
    {
        $aliases = setting('reserved_aliases', '');
        return array_filter(array_map('trim', explode(',', $aliases)));
    }
    
    protected function checkGoogleSafeBrowsing(string $url, string $apiKey): bool
    {
        try {
            $endpoint = "https://safebrowsing.googleapis.com/v4/threatMatches:find?key={$apiKey}";
            
            $body = [
                'client' => [
                    'clientId' => config('app.name'),
                    'clientVersion' => '1.0.0'
                ],
                'threatInfo' => [
                    'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                    'platformTypes' => ['ANY_PLATFORM'],
                    'threatEntryTypes' => ['URL'],
                    'threatEntries' => [['url' => $url]]
                ]
            ];
            
            $response = \Illuminate\Support\Facades\Http::post($endpoint, $body);
            
            if ($response->successful()) {
                $data = $response->json();
                return empty($data['matches']);
            }
        } catch (\Exception $e) {
            \Log::warning('Google Safe Browsing check failed', ['error' => $e->getMessage()]);
        }
        
        return true; // Assume safe if check fails
    }
    
    protected function checkPhishTank(string $url, string $apiKey): bool
    {
        try {
            $endpoint = "https://checkurl.phishtank.com/checkurl/";
            
            $response = \Illuminate\Support\Facades\Http::asForm()->post($endpoint, [
                'url' => $url,
                'format' => 'json',
                'app_key' => $apiKey
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return isset($data['results']['in_database']) && $data['results']['in_database'] && $data['results']['valid'];
            }
        } catch (\Exception $e) {
            \Log::warning('PhishTank check failed', ['error' => $e->getMessage()]);
        }
        
        return false; // Assume not phishing if check fails
    }
}
