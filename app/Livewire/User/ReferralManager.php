<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ReferralManager extends Component
{
    protected $layout = 'components.user-dashboard-layout';

    public $referralLink;
    public $referredUsers;
    public $totalReferralEarnings;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $search = '';
    public $statusFilter = '';
    public bool $referralsEnabled = true;

    public function mount()
    {
        $this->referralsEnabled = setting('enable_referrals', true);
        
        $user = Auth::user();
        $this->referralLink = route('register', ['referral_code' => $user->referral_code]);
        $this->loadReferrals();
    }

    public function updatedSearch()
    {
        $this->loadReferrals();
    }

    public function updatedStatusFilter()
    {
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
        $query = User::where('referred_by_user_id', $user->id);

        // Search filter
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Status filter
        if ($this->statusFilter === 'active') {
            $query->whereHas('links');
        } elseif ($this->statusFilter === 'inactive') {
            $query->whereDoesntHave('links');
        }

        // Sorting
        if ($this->sortBy !== 'earnings') {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        $this->referredUsers = $query->get();
        $this->totalReferralEarnings = $user->referral_earnings ?? 0;
    }

    // This method calculates the earnings the current user made FROM the referred user.
    // Uses dynamic commission rate from site settings.
    public function getReferralEarningForUser(User $referredUser)
    {
        $totalEarningsOfReferred = ($referredUser->link_earnings ?? 0) + ($referredUser->referral_earnings ?? 0);
        $commissionRate = setting('referral_commission_rate', 15) / 100; // Convert percentage to decimal
        return $totalEarningsOfReferred * $commissionRate;
    }
}

