<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkClick;
use Carbon\Carbon;

class DashboardStats extends Component
{
    public $totalViews = 0;
    public $paidViews = 0;
    public $publisherEarnings = 0;
    public $averageCpm = 0;
    public $referralEarnings = 0;

    protected $listeners = ['dateRangeUpdated' => 'loadStatsForMonth'];

    public function mount()
    {
        $this->loadStatsForMonth(['month' => Carbon::now()->format('Y-m')]);
    }

    public function loadStatsForMonth($data = null)
    {
        $month = $data['month'] ?? Carbon::now()->format('Y-m');
        $user = Auth::user()->fresh(); // Always get fresh data from DB
        $linkIds = $user->links()->pluck('id');

        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $query = LinkClick::whereIn('link_id', $linkIds)
            ->where('is_skipped', false)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $clicks = $query->get();

        $totalViews = $clicks->count();
        $paidViews = $clicks->where('cpm_rate', '>', 0)->count();

        // Sum paid cpm_rates for this month. cpm_rate is stored as per-1000-views rate.
        $paidClicksCpmSum = $clicks->where('cpm_rate', '>', 0)->sum('cpm_rate');
        $publisherEarnings = $paidClicksCpmSum / 1000;

        $referralEarnings = $user->referral_earnings ?? 0;

        $this->totalViews = $totalViews;
        $this->paidViews = $paidViews;
        $this->publisherEarnings = $publisherEarnings;
        $this->referralEarnings = $referralEarnings;
        $this->averageCpm = $paidViews > 0 ? ($paidClicksCpmSum / $paidViews) : 0;
    }

    public function render()
    {
        return view('livewire.user.dashboard-stats');
    }
}
