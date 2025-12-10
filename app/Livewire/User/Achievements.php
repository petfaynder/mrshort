<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\GamificationGoal;
use App\Models\UserAchievement;
use App\Models\StreakMilestone;
use App\Models\UserStreakMilestone;
use Illuminate\Support\Facades\Auth;

class Achievements extends Component
{
    public $goals;
    public $userAchievements;
    public $filterCategory = 'all';
    
    // Streak milestones data
    public $streakMilestones = [];
    public $currentStreak = 0;

    protected $queryString = ['filterCategory'];

    public function mount()
    {
        $this->loadGoals();
        $this->loadStreakMilestones();
    }

    public function updatedFilterCategory()
    {
        $this->loadGoals();
    }

    public function loadGoals()
    {
        $this->userAchievements = Auth::user()->achievements()->get()->keyBy('goal_id');

        $query = GamificationGoal::where('is_active', true)->with('reward');

        if ($this->filterCategory !== 'all') {
            $query->where('category', $this->filterCategory);
        }

        $this->goals = $query->get()->map(function ($goal) {
            $goal->userAchievement = $this->userAchievements->get($goal->id);
            return $goal;
        });
    }

    public function loadStreakMilestones()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $this->currentStreak = $user->current_streak ?? 0;

        $claimedMilestoneIds = UserStreakMilestone::where('user_id', $userId)
            ->pluck('milestone_id')
            ->toArray();

        $this->streakMilestones = StreakMilestone::where('is_active', true)
            ->orderBy('days_required')
            ->get()
            ->map(function ($milestone) use ($claimedMilestoneIds) {
                return [
                    'id' => $milestone->id,
                    'days' => $milestone->days_required,
                    'points' => $milestone->points_reward,
                    'bonus_type' => $milestone->bonus_type,
                    'bonus_value' => $milestone->bonus_value,
                    'claimed' => in_array($milestone->id, $claimedMilestoneIds),
                    'reachable' => $this->currentStreak >= $milestone->days_required,
                    'progress' => min(100, round(($this->currentStreak / max(1, $milestone->days_required)) * 100)),
                ];
            })
            ->toArray();
    }

    public function claimStreakMilestone($milestoneId)
    {
        $userId = Auth::id();
        $user = Auth::user();
        
        $milestone = StreakMilestone::find($milestoneId);
        if (!$milestone) return;

        // Check if already claimed
        if (UserStreakMilestone::where('user_id', $userId)->where('milestone_id', $milestoneId)->exists()) {
            return;
        }

        // Check if user has enough streak days
        if (($user->current_streak ?? 0) < $milestone->days_required) {
            return;
        }

        // Claim the milestone
        UserStreakMilestone::create([
            'user_id' => $userId,
            'milestone_id' => $milestoneId,
            'claimed_at' => now(),
        ]);

        // Give rewards
        $user->gamification_points += $milestone->points_reward;
        
        if ($milestone->bonus_type === 'streak_freeze') {
            $user->streak_freeze_available = ($user->streak_freeze_available ?? 0) + ($milestone->bonus_value ?? 1);
        }
        
        $user->save();

        $this->loadStreakMilestones();
        $this->dispatch('refresh-user-dashboard');
    }

    public function render()
    {
        return view('livewire.user.achievements', [
            'goals' => $this->goals,
        ])->layout('components.user-dashboard-layout');
    }
}

