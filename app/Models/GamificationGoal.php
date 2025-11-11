<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GamificationGoal extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'target_value',
        'difficulty_level',
        'category',
        'is_active',
        'user_id',
        'reward_id',
        'points',
        'coins',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'goal_type_config' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class, 'goal_id');
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(GamificationReward::class);
    }
}
