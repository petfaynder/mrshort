<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // User modelini ekledik

class ReferralManager extends Component
{
    protected $layout = 'components.user-dashboard-layout';

    public $referralLink;
    public $referredUsers;
    public $totalReferralEarnings;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    public function mount()
    {
        $user = Auth::user();
        $this->referralLink = route('register', ['ref' => $user->username]);
        $this->loadReferrals();
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->loadReferrals();
    }

    public function render()
    {
        return view('livewire.user.referral-manager');
    }

    private function loadReferrals()
    {
        $user = Auth::user();
        // Note: 'earnings' is not a real column, so sorting by it won't work directly.
        // This would require a more complex query with joins or calculated columns.
        // For now, we allow sorting by existing columns 'name' and 'created_at'.
        $query = User::where('referred_by_user_id', $user->id);

        if ($this->sortBy !== 'earnings') {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        $this->referredUsers = $query->get();
        $this->totalReferralEarnings = $user->referral_earnings;
    }

    // This method calculates the earnings the current user made FROM the referred user.
    // It assumes a 15% commission rate on the referred user's total earnings.
    public function getReferralEarningForUser(User $referredUser)
    {
        $totalEarningsOfReferred = $referredUser->link_earnings + $referredUser->referral_earnings;
        return $totalEarningsOfReferred * 0.15;
    }
}
