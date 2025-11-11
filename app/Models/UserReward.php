<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReward extends Model
{
    protected $fillable = [
        'user_id',
        'reward_id',
        'achievement_id',
        'awarded_at',
        'is_claimed',
        'claimed_at',
    ];

    protected $casts = [
        'awarded_at' => 'datetime',
        'is_claimed' => 'boolean',
        'claimed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(GamificationReward::class);
    }

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(UserAchievement::class, 'achievement_id');
    }
}
