<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'invited_by',
        'status',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Accept the invite
     */
    public function accept(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        // Check team member limit (max 20)
        if ($this->team->member_count >= 20) {
            return false;
        }

        // Create team member
        TeamMember::create([
            'team_id' => $this->team_id,
            'user_id' => $this->user_id,
            'role' => 'member',
        ]);

        // Update invite status
        $this->update(['status' => 'accepted']);

        // Update team member count
        $this->team->updateMemberCount();

        return true;
    }

    /**
     * Reject the invite
     */
    public function reject(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $this->update(['status' => 'rejected']);
        return true;
    }

    /**
     * Get pending invites for a user
     */
    public static function getPendingForUser(int $userId)
    {
        return self::where('user_id', $userId)
            ->where('status', 'pending')
            ->with('team', 'invitedBy')
            ->get();
    }
}
