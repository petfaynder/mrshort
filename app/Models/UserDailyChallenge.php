<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserDailyChallenge extends Model
{
    protected $fillable = [
        'user_id',
        'challenge_date',
        'challenge_ids',
        'progress',
        'completed_ids',
        'bonus_claimed',
    ];

    protected $casts = [
        'challenge_date' => 'date',
        'challenge_ids' => 'array',
        'progress' => 'array',
        'completed_ids' => 'array',
        'bonus_claimed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create today's challenges for a user
     */
    public static function getOrCreateToday(int $userId): self
    {
        $today = Carbon::today();

        $existing = static::where('user_id', $userId)
            ->where('challenge_date', $today)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Create new daily challenges
        $challengeCount = \App\Models\GamificationSetting::where('setting_key', 'daily_challenge_count')->first();
        $count = $challengeCount ? (int) $challengeCount->setting_value : 3;

        $challenges = DailyChallengePool::getRandomChallenges($count);

        // Initialize progress for each challenge
        $progress = [];
        foreach ($challenges as $challenge) {
            $progress[$challenge->id] = 0;
        }

        return static::create([
            'user_id' => $userId,
            'challenge_date' => $today,
            'challenge_ids' => $challenges->pluck('id')->toArray(),
            'progress' => $progress,
            'completed_ids' => [],
        ]);
    }

    /**
     * Update progress for a specific challenge type
     * Supports prefix matching: 'shorten_links' matches 'shorten_links_easy', 'shorten_links_medium', etc.
     */
    public function updateProgress(string $type, int $amount = 1): void
    {
        // Find matching challenges - exact match or prefix match
        $challenges = DailyChallengePool::whereIn('id', $this->challenge_ids)
            ->where(function ($query) use ($type) {
                $query->where('type', $type)
                    ->orWhere('type', 'like', $type . '_%');
            })
            ->get();

        if ($challenges->isEmpty()) {
            return;
        }

        $progress = $this->progress ?? [];
        $completedIds = $this->completed_ids ?? [];
        $pointsAwarded = 0;

        foreach ($challenges as $challenge) {
            // Skip already completed
            if (in_array($challenge->id, $completedIds)) {
                continue;
            }

            $currentProgress = $progress[$challenge->id] ?? 0;
            $newProgress = $currentProgress + $amount;
            $progress[$challenge->id] = $newProgress;

            // Check if completed
            if ($newProgress >= $challenge->target_value) {
                $completedIds[] = $challenge->id;
                $pointsAwarded += $challenge->points_reward;
            }
        }

        $this->progress = $progress;
        $this->completed_ids = array_unique($completedIds);
        $this->save();

        // Award points after save to prevent race conditions
        if ($pointsAwarded > 0) {
            $this->user->gamification_points += $pointsAwarded;
            $this->user->save();
        }
    }

    /**
     * Claim bonus for completing all challenges
     */
    public function claimBonus(): bool
    {
        if ($this->bonus_claimed) {
            return false;
        }

        $completedCount = count($this->completed_ids ?? []);
        $totalCount = count($this->challenge_ids ?? []);

        if ($completedCount < $totalCount) {
            return false;
        }

        // Award bonus
        $bonusSetting = \App\Models\GamificationSetting::where('setting_key', 'daily_challenge_bonus')->first();
        $bonusPoints = $bonusSetting ? (int) $bonusSetting->setting_value : 150;

        $this->user->gamification_points += $bonusPoints;
        $this->user->save();

        $this->bonus_claimed = true;
        $this->save();

        return true;
    }

    /**
     * Check if all challenges are completed
     */
    public function isAllCompleted(): bool
    {
        $completedCount = count($this->completed_ids ?? []);
        $totalCount = count($this->challenge_ids ?? []);
        return $completedCount >= $totalCount && $totalCount > 0;
    }
}
