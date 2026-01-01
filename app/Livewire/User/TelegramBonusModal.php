<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TelegramBonusModal extends Component
{
    public bool $showModal = false;
    public bool $agreedToTerms = false;

    protected $listeners = ['showTelegramBonusModal' => 'openModal'];

    public function mount()
    {
        $user = Auth::user();
        
        // Show modal if user needs to make a decision after tutorial
        if ($user && $user->needsTelegramBonusDecision()) {
            $this->showModal = true;
        }
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function enableTelegramBonus()
    {
        if (!$this->agreedToTerms) {
            session()->flash('telegram_modal_error', 'Please agree to the terms to enable Telegram Traffic Bonus.');
            return;
        }

        $user = Auth::user();
        
        if ($user->canEnableTelegramBonus()) {
            $user->enableTelegramBonus();
            $this->showModal = false;
            session()->flash('success', '🎉 Telegram Traffic Bonus enabled! You will earn +10% CPM for verified Telegram traffic.');
            
            $this->dispatch('telegram-bonus-enabled');
        } else {
            $cooldownEnds = $user->getTelegramCooldownEndsAt();
            if ($cooldownEnds) {
                session()->flash('telegram_modal_error', 'You are in a cooldown period. You can enable again on ' . $cooldownEnds->format('M d, Y'));
            } else {
                session()->flash('telegram_modal_error', 'Unable to enable Telegram bonus at this time.');
            }
        }
    }

    public function skipTelegramBonus()
    {
        $user = Auth::user();
        $user->skipTelegramBonusDecision();
        $this->showModal = false;
        
        session()->flash('info', 'You can enable Telegram Traffic Bonus later from Settings.');
    }

    public function render()
    {
        return view('livewire.user.telegram-bonus-modal');
    }
}
