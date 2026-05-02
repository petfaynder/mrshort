<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\GamificationService; // Add this line

class LinkClick extends Model
{

    protected $fillable = [
        'link_id',
        'ip_address',
        'country_id',
        'cpm_rate',
        'bonus_amount', // VIP + Telegram bonus applied to this click (dollar amount)
        'country',
        'city',
        'device_type',
        'os',
        'browser',
        'referrer',
        'is_skipped', // Click reduction için
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($linkClick) {
            // Skip gamification for skipped/reduced clicks
            if ($linkClick->is_skipped) {
                return;
            }
            
            if ($linkClick->link && $linkClick->link->user_id) {
                $userId = $linkClick->link->user_id;
                $user = $linkClick->link->user;

                try {
                    // Update gamification goals
                    $gamificationService = app(GamificationService::class);
                    $gamificationService->updateGoalProgress($user, 'clicks', 1);

                    // Update daily challenges
                    $todayChallenge = \App\Models\UserDailyChallenge::where('user_id', $userId)
                        ->where('challenge_date', now()->toDateString())
                        ->first();

                    if ($todayChallenge) {
                        // Update click progress
                        $todayChallenge->updateProgress('get_clicks', 1);

                        // Track unique countries for "different_countries" challenge
                        if ($linkClick->country) {
                            $countriesKey = 'daily_countries_' . $userId . '_' . now()->toDateString();
                            $countries = cache()->get($countriesKey, []);
                            
                            if (!in_array($linkClick->country, $countries)) {
                                $countries[] = $linkClick->country;
                                cache()->put($countriesKey, $countries, now()->endOfDay());
                                
                                // Update unique country count
                                $todayChallenge->updateProgress('different_countries', 1);
                            }
                        }
                    }

                    // Update competition score
                    $competitionService = new \App\Services\CompetitionService();
                    $competitionService->updateScore($user, 'clicks', 1);

                    // Mystery Box trigger - her 1000 tıklamada Silver Box
                    $userTotalClicks = LinkClick::whereHas('link', fn($q) => $q->where('user_id', $user->id))->count();
                    if ($userTotalClicks > 0 && $userTotalClicks % 1000 === 0) {
                        \App\Models\UserMysteryBox::giveBox($user->id, 'silver', 'click_milestone_' . $userTotalClicks);
                    }
                } catch (\Exception $e) {
                    \Log::error('LinkClick gamification update failed: ' . $e->getMessage());
                }
            }
        });
    }

    /**
     * Get the link that owns the click.
     */
    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    /**
     * Get the withdrawal request that owns the click.
     */
    public function withdrawalRequest(): BelongsTo
    {
        return $this->belongsTo(WithdrawalRequest::class, 'withdrawal_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
