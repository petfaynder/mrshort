<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LinkClick;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\GamificationService; // Add this line

class Link extends Model
{
    protected $fillable = [
        'user_id',
        'original_url',
        'code',
        'title',
        'expires_at',
        'is_hidden',
        'campaign_template_id', // Add this field
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($link) {
            if ($link->user_id) {
                $gamificationService = app(GamificationService::class);
                $gamificationService->updateGoalProgress($link->user, 'shorten_links', 1);
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
        return url('/') . '/' . $this->code;
    }
}
