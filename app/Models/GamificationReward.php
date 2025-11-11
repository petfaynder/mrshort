<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GamificationReward extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
        'image_path',
        'gamification_goal_id',
        'level_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'reward_config' => 'array',
    ];

    public function gamificationGoal(): BelongsTo
    {
        return $this->belongsTo(GamificationGoal::class);
    }

    public function levelConfiguration(): BelongsTo
    {
        return $this->belongsTo(LevelConfiguration::class);
    }

    public function userRewards(): HasMany
    {
        return $this->hasMany(UserReward::class, 'reward_id');
    }
}
