<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\UserDailyChallenge;
use App\Models\DailyChallengePool;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class DailyChallenges extends Component
{
    public $todaysChallenges;
    public $challengeDetails = [];
    public $bonusPoints = 150;
    public $showCompletionModal = false;
    public $completedChallenge = null;

    public function mount()
    {
        $this->loadChallenges();

        // Get bonus points from settings
        $bonusSetting = \App\Models\GamificationSetting::where('setting_key', 'daily_challenge_bonus')->first();
        $this->bonusPoints = $bonusSetting ? (int) $bonusSetting->setting_value : 150;
    }

    public function loadChallenges()
    {
        $this->todaysChallenges = UserDailyChallenge::getOrCreateToday(Auth::id());

        // Load challenge details
        $this->challengeDetails = DailyChallengePool::whereIn('id', $this->todaysChallenges->challenge_ids)
            ->get()
            ->keyBy('id')
            ->toArray();
    }

    public function claimBonus()
    {
        if (!$this->todaysChallenges->isAllCompleted()) {
            Notification::make()
                ->title('Complete all challenges!')
                ->warning()
                ->send();
            return;
        }

        if ($this->todaysChallenges->bonus_claimed) {
            Notification::make()
                ->title('Bonus already claimed!')
                ->warning()
                ->send();
            return;
        }

        $claimed = $this->todaysChallenges->claimBonus();

        if ($claimed) {
            Notification::make()
                ->title('3/3 Bonus Claimed!')
                ->body('+' . $this->bonusPoints . ' Points earned!')
                ->success()
                ->send();

            $this->loadChallenges();
        }
    }

    public function closeCompletionModal()
    {
        $this->showCompletionModal = false;
        $this->completedChallenge = null;
    }

    public function render()
    {
        return view('livewire.user.daily-challenges');
    }
}
