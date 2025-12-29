<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ReferralStats extends Component
{
    public $totalReferrals;
    public $activeReferrals;
    public $totalCommissionEarned;
    public $commissionRate = 15; // %15 commission rate

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $user = Auth::user();
        
        // Total referrals - all users referred by this user
        $this->totalReferrals = User::where('referred_by_user_id', $user->id)->count();
        
        // Active referrals - referred users who have created at least one link
        $this->activeReferrals = User::where('referred_by_user_id', $user->id)
            ->whereHas('links')
            ->count();
        
        // Total commission earned from referrals
        $this->totalCommissionEarned = $user->referral_earnings ?? 0;
    }

    public function render()
    {
        return view('livewire.user.referral-stats');
    }
}
