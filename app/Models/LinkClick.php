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
        'country_id', // Eklendi
        'cpm_rate', // Eklendi
        'country',
        'city',
        'device_type', // Eklendi
        'os', // Eklendi
        'browser', // Eklendi
        'referrer', // Eklendi
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($linkClick) {
            if ($linkClick->link && $linkClick->link->user_id) {
                $gamificationService = app(GamificationService::class);
                $gamificationService->updateGoalProgress($linkClick->link->user, 'clicks', 1);
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
