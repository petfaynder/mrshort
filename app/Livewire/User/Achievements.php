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
    
    // Featured goal (Günün Başarımı)
    public $featuredGoal = null;
    public $featuredGoalProgress = 0;
    public $featuredGoalTimeRemaining = null;
    
    // Goal collections (Özel Başarım Koleksiyonları)
    public $goalCollections = [];

    protected $queryString = ['filterCategory'];

    public function mount()
    {
        $this->loadGoals();
        $this->loadStreakMilestones();
        $this->loadFeaturedGoal();
        $this->loadGoalCollections();
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

    public function loadFeaturedGoal()
    {
        $user = Auth::user();
        
        // Get a random active goal that user hasn't completed yet as "featured"
        // Priority: goals from 'weekly' category, then 'milestone', then any
        $completedGoalIds = $user->achievements()->pluck('goal_id')->toArray();
        
        $query = GamificationGoal::where('is_active', true)
            ->whereNotIn('id', $completedGoalIds);
        
        // Try to get weekly goals first
        $this->featuredGoal = $query->clone()
            ->where('category', 'weekly')
            ->inRandomOrder()
            ->first();
        
        // If no weekly, try milestone
        if (!$this->featuredGoal) {
            $this->featuredGoal = $query->clone()
                ->where('category', 'milestone')
                ->inRandomOrder()
                ->first();
        }
        
        // If still none, get any goal
        if (!$this->featuredGoal) {
            $this->featuredGoal = $query->inRandomOrder()->first();
        }
        
        // Calculate progress for featured goal
        if ($this->featuredGoal) {
            $currentProgress = $this->calculateGoalProgress($user, $this->featuredGoal);
            $this->featuredGoalProgress = min(100, round(($currentProgress / max(1, $this->featuredGoal->target_value)) * 100));
            
            // Calculate time remaining (end of week for weekly, end of day for daily)
            if ($this->featuredGoal->category === 'weekly') {
                $this->featuredGoalTimeRemaining = now()->endOfWeek();
            } elseif ($this->featuredGoal->category === 'daily') {
                $this->featuredGoalTimeRemaining = now()->endOfDay();
            } else {
                $this->featuredGoalTimeRemaining = now()->addDays(7); // Default 7 days
            }
        }
    }
    
    private function calculateGoalProgress($user, $goal)
    {
        return match($goal->type) {
            'shorten_links' => $user->links()->count(),
            // Fix: links()->sum('clicks') was always 0 because 'clicks' is in link_clicks table, not links table.
            // Use a proper subquery count via the linkClicks relationship.
            'clicks'        => \App\Models\LinkClick::whereIn(
                                'link_id',
                                $user->links()->pluck('id')
                               )->where('is_skipped', false)->count(),
            'referrals'     => \App\Models\User::where('referred_by_user_id', $user->id)->count(),
            'earnings'      => ($user->link_earnings ?? 0) + ($user->referral_earnings ?? 0),
            default         => 0,
        };
    }
    
    public function loadGoalCollections()
    {
        $user = Auth::user();
        $userAchievements = $user->achievements()->pluck('goal_id')->toArray();
        
        // Define collections based on categories
        $collectionDefinitions = [
            [
                'name' => 'Entry Level Shortener',
                'description' => 'Discover basic shortening features.',
                'icon' => 'rocket_launch',
                'color' => 'primary',
                'categories' => ['beginner', 'daily'],
                'types' => ['shorten_links'],
            ],
            [
                'name' => 'Click Master',
                'description' => 'Drive traffic to your links.',
                'icon' => 'ads_click',
                'color' => 'green',
                'categories' => ['milestone'],
                'types' => ['clicks'],
            ],
            [
                'name' => 'Social Media Expert',
                'description' => 'Grow with social sharing.',
                'icon' => 'share',
                'color' => 'purple',
                'categories' => ['social'],
                'types' => ['referrals', 'shares'],
            ],
            [
                'name' => 'Earnings Champion',
                'description' => 'Get maximum income.',
                'icon' => 'payments',
                'color' => 'yellow',
                'categories' => ['earnings', 'weekly'],
                'types' => ['earnings'],
            ],
        ];
        
        $this->goalCollections = [];
        
        foreach ($collectionDefinitions as $def) {
            $goals = GamificationGoal::where('is_active', true)
                ->where(function($query) use ($def) {
                    $query->whereIn('category', $def['categories'])
                        ->orWhereIn('type', $def['types']);
                })
                ->take(5)
                ->get();
            
            if ($goals->count() > 0) {
                $completedCount = $goals->filter(fn($g) => in_array($g->id, $userAchievements))->count();
                
                $this->goalCollections[] = [
                    'name' => $def['name'],
                    'description' => $def['description'],
                    'icon' => $def['icon'],
                    'color' => $def['color'],
                    'total' => $goals->count(),
                    'completed' => $completedCount,
                    'progress' => round(($completedCount / $goals->count()) * 100),
                    'goals' => $goals->map(fn($g) => [
                        'id' => $g->id,
                        'title' => $g->title,
                        'completed' => in_array($g->id, $userAchievements),
                    ])->toArray(),
                ];
            }
        }
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

