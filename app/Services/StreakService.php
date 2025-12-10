<?php

namespace App\Services;

use App\Models\User;
use App\Models\StreakMilestone;
use App\Models\UserStreakMilestone;
use App\Models\GamificationSetting;
use Carbon\Carbon;

class StreakService
{
    /**
     * Check and update user's streak
     * Called when user performs an activity
     */
    public function updateStreak(User $user): array
    {
        $today = Carbon::today();
        $lastStreakDate = $user->last_streak_date ? Carbon::parse($user->last_streak_date) : null;

        // If already logged today, no change
        if ($lastStreakDate && $lastStreakDate->isSameDay($today)) {
            return [
                'streak' => $user->current_streak,
                'changed' => false,
                'milestones' => [],
            ];
        }

        // Check if streak continues (yesterday) or breaks
        if ($lastStreakDate && $lastStreakDate->isSameDay($today->copy()->subDay())) {
            // Streak continues
            $user->current_streak += 1;
        } elseif ($lastStreakDate && $lastStreakDate->lessThan($today->copy()->subDay())) {
            // Streak broken - check for freeze
            if ($user->streak_freeze_available > 0) {
                // Use freeze, streak continues
                $user->streak_freeze_available -= 1;
                $user->current_streak += 1;
            } else {
                // No freeze, reset streak
                $user->current_streak = 1;
            }
        } else {
            // First time or very old
            $user->current_streak = 1;
        }

        // Update longest streak if needed
        if ($user->current_streak > $user->longest_streak) {
            $user->longest_streak = $user->current_streak;
        }

        $user->last_streak_date = $today;
        $user->save();

        // Check for milestone rewards
        $claimedMilestones = $this->checkAndClaimMilestones($user);

        return [
            'streak' => $user->current_streak,
            'changed' => true,
            'milestones' => $claimedMilestones,
        ];
    }

    /**
     * Check and claim any unclaimed milestones
     */
    public function checkAndClaimMilestones(User $user): array
    {
        $unclaimedMilestones = StreakMilestone::getUnclaimedMilestones($user->id, $user->current_streak);
        $claimedMilestones = [];

        foreach ($unclaimedMilestones as $milestone) {
            // Claim the milestone
            UserStreakMilestone::create([
                'user_id' => $user->id,
                'milestone_id' => $milestone->id,
            ]);

            // Apply rewards
            $this->applyMilestoneRewards($user, $milestone);

            $claimedMilestones[] = $milestone;
        }

        return $claimedMilestones;
    }

    /**
     * Apply milestone rewards to user
     */
    protected function applyMilestoneRewards(User $user, StreakMilestone $milestone): void
    {
        // Add points
        if ($milestone->points_reward > 0) {
            $user->gamification_points += $milestone->points_reward;
        }

        // Add badge to inventory
        if ($milestone->badge_reward_id) {
            \App\Models\UserInventory::create([
                'user_id' => $user->id,
                'reward_id' => $milestone->badge_reward_id,
                'is_active' => true,
            ]);
        }

        // Apply bonus
        if ($milestone->bonus_type === 'streak_freeze') {
            $user->streak_freeze_available += $milestone->bonus_value ?? 1;
        }

        $user->save();
    }

    /**
     * Get streak status for display
     */
    public function getStreakStatus(User $user): array
    {
        $today = Carbon::today();
        $lastStreakDate = $user->last_streak_date ? Carbon::parse($user->last_streak_date) : null;

        $isActiveToday = $lastStreakDate && $lastStreakDate->isSameDay($today);
        $willBreakTomorrow = !$isActiveToday;

        // Get all milestones with claim status
        $allMilestones = StreakMilestone::getActiveMilestones();
        $claimedIds = UserStreakMilestone::where('user_id', $user->id)->pluck('milestone_id')->toArray();

        $milestonesWithStatus = $allMilestones->map(function ($milestone) use ($user, $claimedIds) {
            return [
                'milestone' => $milestone,
                'claimed' => in_array($milestone->id, $claimedIds),
                'reachable' => $milestone->days_required <= $user->current_streak,
            ];
        });

        return [
            'current_streak' => $user->current_streak,
            'longest_streak' => $user->longest_streak,
            'is_active_today' => $isActiveToday,
            'will_break_tomorrow' => $willBreakTomorrow,
            'freeze_available' => $user->streak_freeze_available,
            'milestones' => $milestonesWithStatus,
        ];
    }
}
