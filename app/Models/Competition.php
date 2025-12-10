<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Competition extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'prize_structure',
        'badge_reward_id',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'prize_structure' => 'array',
        'is_active' => 'boolean',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(CompetitionEntry::class);
    }

    public function badgeReward(): BelongsTo
    {
        return $this->belongsTo(GamificationReward::class, 'badge_reward_id');
    }

    /**
     * Get active competitions
     */
    public static function getActive()
    {
        return static::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();
    }

    /**
     * Get current week's competition
     */
    public static function getCurrentWeekly(?string $type = null)
    {
        $query = static::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());

        if ($type) {
            $query->where('type', $type);
        }

        return $query->first();
    }

    /**
     * Check if competition is currently running
     */
    public function isRunning(): bool
    {
        $now = Carbon::now();
        return $this->is_active && 
               $now->gte($this->start_date) && 
               $now->lte($this->end_date);
    }

    /**
     * Check if competition has ended
     */
    public function hasEnded(): bool
    {
        return Carbon::now()->gt($this->end_date);
    }

    /**
     * Get leaderboard
     */
    public function getLeaderboard(int $limit = 100)
    {
        return $this->entries()
            ->with('user:id,name,avatar')
            ->orderByDesc('score')
            ->limit($limit)
            ->get();
    }

    /**
     * Calculate and update ranks
     */
    public function calculateRanks(): void
    {
        $entries = $this->entries()
            ->orderByDesc('score')
            ->get();

        $rank = 1;
        foreach ($entries as $entry) {
            $entry->rank = $rank;
            $entry->save();
            $rank++;
        }
    }

    /**
     * Distribute rewards after competition ends
     */
    public function distributeRewards(): int
    {
        if (!$this->hasEnded()) {
            return 0;
        }

        $this->calculateRanks();
        $rewardsGiven = 0;

        $entries = $this->entries()
            ->where('reward_claimed', false)
            ->whereNotNull('rank')
            ->get();

        foreach ($entries as $entry) {
            foreach ($this->prize_structure as $prize) {
                if ($entry->rank == $prize['rank'] || 
                    (isset($prize['rank_to']) && $entry->rank >= $prize['rank'] && $entry->rank <= $prize['rank_to'])) {
                    
                    // Award points
                    if (isset($prize['points']) && $prize['points'] > 0) {
                        $entry->user->gamification_points += $prize['points'];
                        $entry->user->save();
                    }

                    // Award badge for winner
                    if ($entry->rank == 1 && $this->badge_reward_id) {
                        UserInventory::create([
                            'user_id' => $entry->user_id,
                            'reward_id' => $this->badge_reward_id,
                            'is_active' => true,
                        ]);
                    }

                    $entry->reward_claimed = true;
                    $entry->save();
                    $rewardsGiven++;
                    break;
                }
            }
        }

        return $rewardsGiven;
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'clicks' => 'En Çok Tıklama',
            'links' => 'En Çok Link',
            'referrals' => 'En Çok Referans',
            'earnings' => 'En Yüksek Kazanç',
            default => $this->type,
        };
    }
}
