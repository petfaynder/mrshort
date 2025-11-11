<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\GamificationGoal;
use App\Models\UserAchievement;

use Illuminate\Support\Facades\Auth;

class Achievements extends Component
{
    protected $layout = 'layouts.achievements'; // Layout'u belirt

    public $goals; // Tüm aktif hedefleri tutacak
    public $userAchievements; // Kullanıcının kazandığı başarımları tutacak
    public $filterCategory = 'all'; // 'all', 'daily', 'weekly', 'one_time', 'social', 'economic', 'discovery'

    protected $queryString = ['filterCategory'];

    public function mount()
    {
        $this->loadGoals();
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

    public function render()
    {
        return view('livewire.user.achievements', [
            'goals' => $this->goals,
        ]);
    }
}
