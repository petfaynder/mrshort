<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\CompetitionEntry;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CompetitionService
{
    /**
     * Update user score for active competitions of given type
     */
    public function updateScore(User $user, string $type, int $amount = 1): void
    {
        try {
            $competitions = Competition::where('is_active', true)
                ->where('type', $type)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->get();

            foreach ($competitions as $competition) {
                $entry = CompetitionEntry::getOrCreate($competition->id, $user->id);
                $entry->incrementScore($amount);
            }
        } catch (\Exception $e) {
            Log::error('Competition score update failed: ' . $e->getMessage());
        }
    }

    /**
     * Get user's current rank in competition
     */
    public function getUserRank(int $competitionId, int $userId): ?int
    {
        $entry = CompetitionEntry::where('competition_id', $competitionId)
            ->where('user_id', $userId)
            ->first();

        if (!$entry) {
            return null;
        }

        // Count how many users have higher score
        $rank = CompetitionEntry::where('competition_id', $competitionId)
            ->where('score', '>', $entry->score)
            ->count() + 1;

        return $rank;
    }

    /**
     * Get competition leaderboard with user position
     */
    public function getLeaderboardWithUserPosition(Competition $competition, int $userId, int $topCount = 10): array
    {
        $topEntries = $competition->entries()
            ->with('user:id,name,avatar')
            ->orderByDesc('score')
            ->limit($topCount)
            ->get();

        $userEntry = CompetitionEntry::where('competition_id', $competition->id)
            ->where('user_id', $userId)
            ->first();

        $userRank = $userEntry ? $this->getUserRank($competition->id, $userId) : null;
        $userInTop = $topEntries->contains('user_id', $userId);

        return [
            'top_entries' => $topEntries,
            'user_entry' => $userEntry,
            'user_rank' => $userRank,
            'user_in_top' => $userInTop,
            'total_participants' => $competition->entries()->count(),
        ];
    }

    /**
     * End competition and distribute rewards
     */
    public function endCompetition(Competition $competition): array
    {
        if (!$competition->hasEnded()) {
            return ['success' => false, 'message' => 'Yarışma henüz bitmedi'];
        }

        $rewardsGiven = $competition->distributeRewards();
        
        return [
            'success' => true,
            'rewards_given' => $rewardsGiven,
        ];
    }
}
