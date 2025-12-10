<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Services\StreakService;
use Illuminate\Support\Facades\Auth;

class StreakDisplay extends Component
{
    public $streakStatus;
    public $showMilestoneModal = false;
    public $claimedMilestone = null;

    protected $listeners = ['refreshStreak' => 'loadStreak'];

    public function mount()
    {
        $this->loadStreak();
    }

    public function loadStreak()
    {
        $streakService = new StreakService();
        $this->streakStatus = $streakService->getStreakStatus(Auth::user());
    }

    public function closeMilestoneModal()
    {
        $this->showMilestoneModal = false;
        $this->claimedMilestone = null;
    }

    public function render()
    {
        return view('livewire.user.streak-display');
    }
}
