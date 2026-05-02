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
        $endDate   = Carbon::parse($month)->endOfMonth();

        // Use SQL aggregates instead of loading ALL clicks into RAM
        $stats = LinkClick::whereIn('link_id', $linkIds)
            ->where('is_skipped', false)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_views,
                SUM(CASE WHEN cpm_rate > 0 THEN 1 ELSE 0 END) as paid_views,
                SUM(CASE WHEN cpm_rate > 0 THEN cpm_rate ELSE 0 END) as paid_cpm_sum
            ')
            ->first();

        $totalViews      = (int) ($stats->total_views ?? 0);
        $paidViews       = (int) ($stats->paid_views  ?? 0);
        $paidClicksCpmSum = (float) ($stats->paid_cpm_sum ?? 0);
        $publisherEarnings = $paidClicksCpmSum / 1000;

        $referralEarnings = $user->referral_earnings ?? 0;

        $this->totalViews        = $totalViews;
        $this->paidViews         = $paidViews;
        $this->publisherEarnings = $publisherEarnings;
        $this->referralEarnings  = $referralEarnings;
        $this->averageCpm        = $paidViews > 0 ? ($paidClicksCpmSum / $paidViews) : 0;
    }

    public function render()
    {
        return view('livewire.user.dashboard-stats');
    }
}
