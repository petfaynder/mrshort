<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSeasonProgress extends Model
{
    use HasFactory;

    protected $table = 'user_season_progress';

    protected $fillable = [
        'user_id',
        'season_id',
        'xp',
        'current_level',
        'has_premium',
        'claimed_rewards',
    ];

    protected $casts = [
        'xp' => 'integer',
        'current_level' => 'integer',
        'has_premium' => 'boolean',
        'claimed_rewards' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    /**
     * Add XP and check for level up
     */
    public function addXp(int $amount): array
    {
        $this->xp += $amount;
        
        $leveledUp = false;
        $newLevel = $this->current_level;
        $unlockedRewards = [];

        // Check for level ups
        while ($this->xp >= Season::getXpRequiredForLevel($newLevel + 1) && $newLevel < $this->season->max_level) {
            $newLevel++;
            $leveledUp = true;
            
            // Get rewards for this level
            $rewards = $this->season->rewards()
                ->where('level', $newLevel)
                ->get();
            
            foreach ($rewards as $reward) {
                if (!$reward->is_premium || $this->has_premium) {
                    $unlockedRewards[] = $reward;
                }
            }
        }

        $this->current_level = $newLevel;
        $this->save();

        return [
            'xp_gained' => $amount,
            'total_xp' => $this->xp,
            'leveled_up' => $leveledUp,
            'new_level' => $newLevel,
            'unlocked_rewards' => $unlockedRewards,
        ];
    }

    /**
     * Get current level progress percentage
     */
    public function getLevelProgressAttribute(): float
    {
        $currentRequired = Season::getXpRequiredForLevel($this->current_level);
        $nextRequired = Season::getXpRequiredForLevel($this->current_level + 1);
        $xpInLevel = $this->xp - $currentRequired;
        $xpNeeded = $nextRequired - $currentRequired;
        
        return $xpNeeded > 0 ? round(($xpInLevel / $xpNeeded) * 100, 1) : 100;
    }

    /**
     * Get XP needed for next level
     */
    public function getXpToNextLevelAttribute(): int
    {
        return max(0, Season::getXpRequiredForLevel($this->current_level + 1) - $this->xp);
    }

    /**
     * Claim a reward
     */
    public function claimReward(int $rewardId): bool
    {
        $claimed = $this->claimed_rewards ?? [];
        
        if (in_array($rewardId, $claimed)) {
            return false; // Already claimed
        }

        $reward = SeasonReward::find($rewardId);
        if (!$reward || $reward->season_id !== $this->season_id) {
            return false; // Invalid reward
        }

        if ($reward->level > $this->current_level) {
            return false; // Level not reached
        }

        if ($reward->is_premium && !$this->has_premium) {
            return false; // Premium required
        }

        $claimed[] = $rewardId;
        $this->claimed_rewards = $claimed;
        $this->save();

        return true;
    }

    /**
     * Check if a reward has been claimed
     */
    public function hasClaimedReward(int $rewardId): bool
    {
        return in_array($rewardId, $this->claimed_rewards ?? []);
    }

    /**
     * Upgrade to premium
     */
    public function upgradeToPremium(): bool
    {
        if ($this->has_premium) {
            return false;
        }

        $this->has_premium = true;
        $this->save();

        return true;
    }

    /**
     * Get or create progress for user and season
     */
    public static function getOrCreate(int $userId, int $seasonId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId, 'season_id' => $seasonId],
            ['xp' => 0, 'current_level' => 0, 'has_premium' => false, 'claimed_rewards' => []]
        );
    }
}
