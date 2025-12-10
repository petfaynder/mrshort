<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo_path',
        'leader_id',
        'member_count',
        'total_points',
        'weekly_points',
        'is_public',
        'is_active',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'member_count' => 'integer',
        'total_points' => 'integer',
        'weekly_points' => 'integer',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TeamMessage::class)->latest();
    }

    public function invites(): HasMany
    {
        return $this->hasMany(TeamInvite::class);
    }

    /**
     * Get member users
     */
    public function getMemberUsersAttribute()
    {
        return User::whereIn('id', $this->members()->pluck('user_id'))->get();
    }

    /**
     * Check if user is a member
     */
    public function isMember(int $userId): bool
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    /**
     * Check if user is leader
     */
    public function isLeader(int $userId): bool
    {
        return $this->leader_id === $userId;
    }

    /**
     * Check if user is officer
     */
    public function isOfficer(int $userId): bool
    {
        return $this->members()
            ->where('user_id', $userId)
            ->where('role', 'officer')
            ->exists();
    }

    /**
     * Check if user can manage team
     */
    public function canManage(int $userId): bool
    {
        return $this->isLeader($userId) || $this->isOfficer($userId);
    }

    /**
     * Add points to team
     */
    public function addPoints(int $points): void
    {
        $this->increment('total_points', $points);
        $this->increment('weekly_points', $points);
    }

    /**
     * Reset weekly points
     */
    public function resetWeeklyPoints(): void
    {
        $this->update(['weekly_points' => 0]);
    }

    /**
     * Update member count
     */
    public function updateMemberCount(): void
    {
        $this->update(['member_count' => $this->members()->count()]);
    }

    /**
     * Get recent messages
     */
    public function getRecentMessages(int $limit = 100)
    {
        return $this->messages()
            ->with('user:id,name,avatar')
            ->limit($limit)
            ->get()
            ->reverse();
    }

    /**
     * Get leaderboard rank
     */
    public function getWeeklyRankAttribute(): int
    {
        return self::where('weekly_points', '>', $this->weekly_points)
            ->where('is_active', true)
            ->count() + 1;
    }
}
