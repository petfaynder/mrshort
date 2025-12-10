<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Season;
use App\Models\UserSeasonProgress;
use Illuminate\Support\Facades\Auth;

class BattlePass extends Component
{
    public ?Season $season = null;
    public ?UserSeasonProgress $progress = null;
    public array $rewards = [];
    public bool $showUpgradeModal = false;

    public function mount()
    {
        $this->loadSeason();
    }

    public function loadSeason()
    {
        $this->season = Season::getActive();
        
        if ($this->season && Auth::check()) {
            $this->progress = UserSeasonProgress::getOrCreate(Auth::id(), $this->season->id);
            $this->rewards = $this->season->rewards()->orderBy('level')->get()->groupBy('level')->toArray();
        }
    }

    public function claimReward(int $rewardId)
    {
        if (!$this->progress) {
            return;
        }

        $success = $this->progress->claimReward($rewardId);
        
        if ($success) {
            // Give the actual reward to user
            $reward = \App\Models\SeasonReward::find($rewardId);
            if ($reward) {
                $this->giveReward($reward);
            }
            
            $this->dispatch('reward-claimed', ['reward_id' => $rewardId]);
        }

        $this->loadSeason();
    }

    protected function giveReward(\App\Models\SeasonReward $reward)
    {
        $user = Auth::user();

        switch ($reward->reward_type) {
            case 'points':
                $user->increment('gamification_points', (int) $reward->reward_value);
                break;
            case 'streak_freeze':
                $user->increment('streak_freeze_available', (int) $reward->reward_value);
                break;
            // Other reward types can be handled here
        }
    }

    public function upgradeToPremium()
    {
        if (!$this->progress || !$this->season) {
            return;
        }

        $user = Auth::user();
        $cost = $this->season->premium_price_points;

        if ($user->gamification_points < $cost) {
            $this->dispatch('not-enough-points', ['required' => $cost, 'current' => $user->gamification_points]);
            return;
        }

        $user->decrement('gamification_points', $cost);
        $this->progress->upgradeToPremium();
        
        $this->showUpgradeModal = false;
        $this->dispatch('premium-upgraded');
        $this->loadSeason();
    }

    public function openUpgradeModal()
    {
        $this->showUpgradeModal = true;
    }

    public function closeUpgradeModal()
    {
        $this->showUpgradeModal = false;
    }

    public function render()
    {
        return view('livewire.user.battle-pass')
            ->layout('components.user-dashboard-layout');
    }
}
