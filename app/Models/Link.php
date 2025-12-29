<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LinkClick;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\GamificationService;
use App\Services\DailyActivityService;

class Link extends Model
{
    protected $fillable = [
        'user_id',
        'original_url',
        'code',
        'title',
        'expires_at',
        'is_hidden',
        'campaign_template_id',
        'use_wordpress',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($link) {
            if ($link->user_id && $link->user) {
                try {
                    // Update gamification goals (existing system)
                    $gamificationService = app(GamificationService::class);
                    $gamificationService->updateGoalProgress($link->user, 'shorten_links', 1);

                    // Update daily challenges and streak via centralized service
                    $activityService = new DailyActivityService();
                    $activityService->recordActivity($link->user, 'shorten_links', 1);

                    // Update competition score
                    $competitionService = new \App\Services\CompetitionService();
                    $competitionService->updateScore($link->user, 'links', 1);

                    // Mystery Box trigger - her 50 linkte Bronze Box
                    $linkCount = Link::where('user_id', $link->user_id)->count();
                    if ($linkCount > 0 && $linkCount % 50 === 0) {
                        UserMysteryBox::giveBox($link->user_id, 'bronze', 'link_milestone_' . $linkCount);
                    }
                } catch (\Exception $e) {
                    \Log::error('Link gamification update failed: ' . $e->getMessage());
                }
            }
        });
    }

    /**
     * Get the clicks for the link.
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class);
    }

    /**
     * Get the user that owns the link.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the campaign template associated with the link.
     */
    public function campaignTemplate(): BelongsTo
    {
        return $this->belongsTo(CampaignTemplate::class);
    }

    public function shortLink(): string
    {
        // Use active domain if available
        $domain = activeDomain();
        
        if ($domain) {
            return $domain->getShortUrl($this->code);
        }
        
        // Fallback to app URL
        $baseUrl = url('/');
        
        // Force HTTPS if enabled in settings
        if (setting('enable_https_short_links', true)) {
            $baseUrl = str_replace('http://', 'https://', $baseUrl);
        }
        
        return $baseUrl . '/' . $this->code;
    }
}
