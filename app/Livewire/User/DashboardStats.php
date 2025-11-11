<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkClick;
use Carbon\Carbon;

class DashboardStats extends Component
{
    public $totalViews = 0;
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
        $user = Auth::user();
        $linkIds = $user->links()->pluck('id');

        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $query = LinkClick::whereIn('link_id', $linkIds)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $clicks = $query->get();

        $totalViews = $clicks->count();
        $publisherEarnings = $clicks->sum('cpm_rate') / 1000;
        $referralEarnings = $user->referral_earnings ?? 0;

        $this->totalViews = $totalViews;
        $this->publisherEarnings = $publisherEarnings;
        $this->referralEarnings = $referralEarnings;
        $this->averageCpm = $totalViews > 0 ? ($publisherEarnings / $totalViews) * 1000 : 0;
    }

    public function render()
    {
        return view('livewire.user.dashboard-stats');
    }
}
