<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Competition;
use App\Models\CompetitionEntry;
use App\Services\CompetitionService;
use Illuminate\Support\Facades\Auth;

class WeeklyCompetition extends Component
{
    public $competition = null;
    public $leaderboardData = null;
    public $userEntry = null;
    public $userRank = null;

    public function mount()
    {
        $this->loadCompetition();
    }

    public function loadCompetition()
    {
        // Get active competition (prioritize clicks type)
        $this->competition = Competition::getCurrentWeekly('clicks') 
            ?? Competition::getCurrentWeekly();

        if ($this->competition) {
            $competitionService = new CompetitionService();
            $data = $competitionService->getLeaderboardWithUserPosition(
                $this->competition,
                Auth::id(),
                10
            );

            $this->leaderboardData = $data['top_entries'];
            $this->userEntry = $data['user_entry'];
            $this->userRank = $data['user_rank'];
        }
    }

    public function render()
    {
        return view('livewire.user.weekly-competition');
    }
}
