<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CaptchaService
{
    /**
     * Verify captcha response based on provider
     */
    public function verify(?string $token): bool
    {
        if (!setting('captcha_enabled', false)) {
            return true;
        }

        // Skip captcha verification on local/development environment
        if (app()->environment('local', 'development') || request()->getHost() === 'localhost' || request()->getHost() === '127.0.0.1') {
            Log::info('Captcha bypassed for local environment');
            return true;
        }

        if (empty($token)) {
            return false;
        }

        $provider = setting('captcha_provider', 'turnstile');
        $secretKey = setting('captcha_secret_key');

        if (empty($secretKey)) {
            Log::warning('Captcha secret key not configured');
            return true; // Allow if not configured
        }

        return match ($provider) {
            'turnstile' => $this->verifyTurnstile($token, $secretKey),
            'recaptcha_v2', 'recaptcha_v2_invisible' => $this->verifyRecaptchaV2($token, $secretKey),
            'recaptcha_v3' => $this->verifyRecaptchaV3($token, $secretKey),
            'hcaptcha' => $this->verifyHCaptcha($token, $secretKey),
            default => true,
        };
    }

    /**
     * Get captcha HTML script for form
     */
    public function getScript(): string
    {
        if (!setting('captcha_enabled', false)) {
            return '';
        }

        $provider = setting('captcha_provider', 'turnstile');
        $siteKey = setting('captcha_site_key');

        if (empty($siteKey)) {
            return '';
        }

        return match ($provider) {
            'turnstile' => $this->getTurnstileScript($siteKey),
            'recaptcha_v2' => $this->getRecaptchaV2Script($siteKey),
            'recaptcha_v2_invisible' => $this->getRecaptchaV2InvisibleScript($siteKey),
            'recaptcha_v3' => $this->getRecaptchaV3Script($siteKey),
            'hcaptcha' => $this->getHCaptchaScript($siteKey),
            default => '',
        };
    }

    /**
     * Get captcha widget HTML
     */
    public function getWidget(): string
    {
        if (!setting('captcha_enabled', false)) {
            return '';
        }

        $provider = setting('captcha_provider', 'turnstile');
        $siteKey = setting('captcha_site_key');

        if (empty($siteKey)) {
            return '';
        }

        return match ($provider) {
            'turnstile' => '<div class="cf-turnstile" data-sitekey="' . $siteKey . '"></div>',
            'recaptcha_v2' => '<div class="g-recaptcha" data-sitekey="' . $siteKey . '"></div>',
            'recaptcha_v2_invisible' => '<div class="g-recaptcha" data-sitekey="' . $siteKey . '" data-size="invisible"></div>',
            'recaptcha_v3' => '<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">',
            'hcaptcha' => '<div class="h-captcha" data-sitekey="' . $siteKey . '"></div>',
            default => '',
        };
    }

    /**
     * Get token field name based on provider
     */
    public function getTokenFieldName(): string
    {
        $provider = setting('captcha_provider', 'turnstile');
        
        return match ($provider) {
            'turnstile' => 'cf-turnstile-response',
            'recaptcha_v2', 'recaptcha_v2_invisible', 'recaptcha_v3' => 'g-recaptcha-response',
            'hcaptcha' => 'h-captcha-response',
            default => 'captcha_token',
        };
    }

    protected function verifyTurnstile(string $token, string $secretKey): bool
    {
        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secretKey,
                'response' => $token,
            ]);

            $data = $response->json();
            return $data['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('Turnstile verification failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function verifyRecaptchaV2(string $token, string $secretKey): bool
    {
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $token,
            ]);

            $data = $response->json();
            return $data['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA v2 verification failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function verifyRecaptchaV3(string $token, string $secretKey): bool
    {
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $token,
            ]);

            $data = $response->json();
            
            if (!($data['success'] ?? false)) {
                return false;
            }

            $minScore = (float) setting('captcha_v3_min_score', 0.5);
            return ($data['score'] ?? 0) >= $minScore;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA v3 verification failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function verifyHCaptcha(string $token, string $secretKey): bool
    {
        try {
            $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
                'secret' => $secretKey,
                'response' => $token,
            ]);

            $data = $response->json();
            return $data['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('hCaptcha verification failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function getTurnstileScript(string $siteKey): string
    {
        return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
    }

    protected function getRecaptchaV2Script(string $siteKey): string
    {
        return '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
    }

    protected function getRecaptchaV2InvisibleScript(string $siteKey): string
    {
        return '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
    }

    protected function getRecaptchaV3Script(string $siteKey): string
    {
        return '<script src="https://www.google.com/recaptcha/api.js?render=' . $siteKey . '"></script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute("' . $siteKey . '", {action: "submit"}).then(function(token) {
        document.getElementById("g-recaptcha-response").value = token;
    });
});
</script>';
    }

    protected function getHCaptchaScript(string $siteKey): string
    {
        return '<script src="https://js.hcaptcha.com/1/api.js" async defer></script>';
    }
}
