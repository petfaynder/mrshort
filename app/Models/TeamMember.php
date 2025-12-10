<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'role',
        'contribution_points',
    ];

    protected $casts = [
        'contribution_points' => 'integer',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'leader' => 'Lider',
            'officer' => 'Yönetici',
            'member' => 'Üye',
            default => $this->role,
        };
    }

    /**
     * Check if can be promoted
     */
    public function canBePromoted(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Check if can be demoted
     */
    public function canBeDemoted(): bool
    {
        return $this->role === 'officer';
    }

    /**
     * Promote to officer
     */
    public function promote(): bool
    {
        if (!$this->canBePromoted()) {
            return false;
        }

        $this->update(['role' => 'officer']);
        return true;
    }

    /**
     * Demote to member
     */
    public function demote(): bool
    {
        if (!$this->canBeDemoted()) {
            return false;
        }

        $this->update(['role' => 'member']);
        return true;
    }

    /**
     * Add contribution points
     */
    public function addPoints(int $points): void
    {
        $this->increment('contribution_points', $points);
        $this->team->addPoints($points);
    }
}
