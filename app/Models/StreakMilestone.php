<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StreakMilestone extends Model
{
    protected $fillable = [
        'days_required',
        'points_reward',
        'badge_reward_id',
        'bonus_type',
        'bonus_value',
        'bonus_duration_hours',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function badgeReward(): BelongsTo
    {
        return $this->belongsTo(GamificationReward::class, 'badge_reward_id');
    }

    public function userMilestones(): HasMany
    {
        return $this->hasMany(UserStreakMilestone::class, 'milestone_id');
    }

    /**
     * Get all active milestones ordered by days required
     */
    public static function getActiveMilestones()
    {
        return static::where('is_active', true)
            ->orderBy('days_required')
            ->get();
    }

    /**
     * Get unclaimed milestones for a user
     */
    public static function getUnclaimedMilestones(int $userId, int $currentStreak)
    {
        $claimedIds = UserStreakMilestone::where('user_id', $userId)->pluck('milestone_id');

        return static::where('is_active', true)
            ->where('days_required', '<=', $currentStreak)
            ->whereNotIn('id', $claimedIds)
            ->orderBy('days_required')
            ->get();
    }
}
