<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralTransaction extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'link_click_id',
        'base_click_earning',
        'amount',
        'commission_rate',
    ];

    protected $casts = [
        'base_click_earning' => 'float',
        'amount'             => 'float',
        'commission_rate'    => 'float',
    ];

    // ─────────────────────────────────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────────────────────────────────

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function linkClick(): BelongsTo
    {
        return $this->belongsTo(LinkClick::class, 'link_click_id');
    }
}
