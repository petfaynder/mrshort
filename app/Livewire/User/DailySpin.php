<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\DailySpinPrize;
use App\Models\UserSpin;
use App\Models\GamificationSetting;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class DailySpin extends Component
{
    public $prizes = [];
    public $canSpin = false;
    public $timeUntilNextSpin = 0;
    public $isSpinning = false;
    public $wonPrize = null;
    public $showResultModal = false;
    public $spinEnabled = true;
    public $spinHistory = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Check if feature is enabled
        $enabledSetting = GamificationSetting::where('setting_key', 'daily_spin_enabled')->first();
        $this->spinEnabled = $enabledSetting ? (bool) $enabledSetting->setting_value : true;

        if (!$this->spinEnabled) {
            return;
        }

        // Load prizes
        $this->prizes = DailySpinPrize::getActivePrizes()->toArray();

        // Check if user can spin
        $this->canSpin = UserSpin::canUserSpin(Auth::id());
        $this->timeUntilNextSpin = UserSpin::timeUntilNextSpin(Auth::id());

        // Load spin history
        $this->spinHistory = UserSpin::where('user_id', Auth::id())
            ->with('prize')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    public function spin()
    {
        if (!$this->spinEnabled) {
            Notification::make()
                ->title('The lucky wheel is currently closed.')
                ->warning()
                ->send();
            return;
        }

        // Always check eligibility from DB, not stale component property
        if (!UserSpin::canUserSpin(Auth::id())) {
            Notification::make()
                ->title("You can't spin the wheel yet!")
                ->body('Please wait for the cooldown to expire.')
                ->warning()
                ->send();
            // Refresh state to reflect actual cooldown
            $this->loadData();
            return;
        }

        $this->isSpinning = true;

        // Get random prize
        $prize = DailySpinPrize::spin();

        if (!$prize) {
            Notification::make()
                ->title('An error occurred.')
                ->danger()
                ->send();
            $this->isSpinning = false;
            return;
        }

        // Wrap spin record + prize application in a transaction to prevent double-spend
        \Illuminate\Support\Facades\DB::transaction(function () use ($prize) {
            // Record the spin first
            UserSpin::create([
                'user_id'    => Auth::id(),
                'prize_id'   => $prize->id,
                'prize_value' => $prize->value,
            ]);

            // Apply the prize
            $this->applyPrize($prize);
        });

        // Store won prize for display
        $this->wonPrize = $prize;

        // Dispatch event to JS for animation
        $this->dispatch('spin-wheel', prizeIndex: array_search($prize->id, array_column($this->prizes, 'id')));
    }

    public function finishSpin()
    {
        $this->isSpinning = false;
        $this->showResultModal = true;
        $this->loadData();
    }

    public function closeResultModal()
    {
        $this->showResultModal = false;
        $this->wonPrize = null;
    }

    protected function applyPrize(DailySpinPrize $prize)
    {
        $user = Auth::user();

        switch ($prize->type) {
            case 'points':
                $user->gamification_points += $prize->value;
                $user->save();
                break;

            case 'streak_freeze':
                // Add streak freeze to user (will implement with streak system)
                $user->increment('streak_freeze_available', $prize->value ?: 1);
                break;

            case 'reward_id':
                // Add to user inventory
                \App\Models\UserInventory::create([
                    'user_id' => $user->id,
                    'reward_id' => $prize->value,
                    'is_active' => false,
                ]);
                break;

            case 'xp_multiplier':
                // Will be handled by XP system
                break;
        }
    }

    public function render()
    {
        return view('livewire.user.daily-spin');
    }
}
