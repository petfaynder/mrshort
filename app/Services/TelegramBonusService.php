<?php

namespace App\Services;

use App\Models\User;
use App\Models\LinkClick;
use Illuminate\Support\Facades\Log;

class TelegramBonusService
{
    /**
     * Telegram referrer patterns to match
     */
    protected array $telegramPatterns = [
        't.me',
        'telegram.me',
        'web.telegram.org',
        'tg://',
    ];

    /**
     * Required match rate for verification (70%)
     */
    protected float $requiredMatchRate = 70.0;

    /**
     * Number of clicks before verification
     */
    protected int $verificationThreshold = 500;

    /**
     * Check if a referrer URL is from Telegram
     */
    public function isTelegramReferrer(?string $referrer): bool
    {
        if (empty($referrer) || $referrer === 'Direct Access') {
            return false;
        }

        $referrerLower = strtolower($referrer);
        
        foreach ($this->telegramPatterns as $pattern) {
            if (str_contains($referrerLower, strtolower($pattern))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate the Telegram referrer match rate for a user's last N clicks
     */
    public function calculateMatchRate(User $user, int $clickCount = 500): float
    {
        // Get link IDs for this user
        $linkIds = $user->links()->pluck('id');
        
        if ($linkIds->isEmpty()) {
            return 0.0;
        }

        // Get last N clicks for user's links
        $clicks = LinkClick::whereIn('link_id', $linkIds)
            ->orderBy('created_at', 'desc')
            ->limit($clickCount)
            ->get();

        if ($clicks->isEmpty()) {
            return 0.0;
        }

        $telegramCount = 0;
        foreach ($clicks as $click) {
            if ($this->isTelegramReferrer($click->referrer)) {
                $telegramCount++;
            }
        }

        return ($telegramCount / $clicks->count()) * 100;
    }

    /**
     * Verify user's Telegram traffic and update their bonus status
     */
    public function verifyTelegramTraffic(User $user): bool
    {
        if (!$user->telegram_bonus_enabled) {
            return false;
        }

        $matchRate = $this->calculateMatchRate($user, $this->verificationThreshold);

        // Update user's match rate
        $user->update([
            'telegram_referrer_match_rate' => $matchRate,
            'telegram_verification_clicks' => 0, // Reset counter
        ]);

        Log::info('Telegram traffic verification completed', [
            'user_id' => $user->id,
            'match_rate' => $matchRate,
            'threshold' => $this->requiredMatchRate,
            'passed' => $matchRate >= $this->requiredMatchRate,
        ]);

        if ($matchRate >= $this->requiredMatchRate) {
            // Verification passed
            $user->update([
                'telegram_bonus_verified_at' => now(),
            ]);
            return true;
        } else {
            // Verification failed - disable bonus with cooldown
            $user->disableTelegramBonus(failed: true);
            
            Log::warning('Telegram bonus revoked due to low match rate', [
                'user_id' => $user->id,
                'match_rate' => $matchRate,
                'required' => $this->requiredMatchRate,
            ]);
            
            return false;
        }
    }

    /**
     * Check if user has reached verification threshold
     */
    public function needsVerification(User $user): bool
    {
        return $user->telegram_bonus_enabled 
            && $user->telegram_verification_clicks >= $this->verificationThreshold;
    }

    /**
     * Increment verification click counter for user
     */
    public function incrementVerificationCounter(User $user): int
    {
        if (!$user->telegram_bonus_enabled) {
            return 0;
        }

        $user->increment('telegram_verification_clicks');
        
        return $user->fresh()->telegram_verification_clicks;
    }

    /**
     * Get the CPM bonus multiplier for Telegram traffic
     */
    public function getCpmBonusMultiplier(): float
    {
        return 1.10; // +10% bonus
    }

    /**
     * Get the advertiser Telegram promotion price multiplier
     */
    public function getAdvertiserTelegramMultiplier(): float
    {
        return 1.25; // +25% for Telegram channel promotions
    }

    /**
     * Check if a URL is a Telegram promotion URL
     */
    public function isTelegramPromotionUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $urlLower = strtolower($url);
        
        return str_contains($urlLower, 't.me/') 
            || str_contains($urlLower, 'telegram.me/')
            || str_starts_with($urlLower, 'tg://');
    }
}
