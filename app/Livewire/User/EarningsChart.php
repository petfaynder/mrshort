<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkClick;
use App\Models\ReferralTransaction;
use Carbon\Carbon;

class EarningsChart extends Component
{
    protected $layout = 'components.user-dashboard-layout';

    public $selectedMonth;

    protected $listeners = ['dateRangeUpdated' => 'updateChartForMonth'];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->dispatch('chartDataUpdated', data: $this->getStatsData());
    }

    public function updateChartForMonth($month = null)
    {
        if ($month) {
            $this->selectedMonth = $month;
        }
        $this->dispatch('chartDataUpdated', data: $this->getStatsData());
    }

    public function render()
    {
        $statsData = $this->getStatsData();

        // Filter stats for the cards: Show Today + Past 4 days (Total 5), descending
        $dailyStats = collect($statsData)
            ->filter(function ($item) {
                return Carbon::parse($item['date'])->lte(Carbon::now()->endOfDay());
            })
            ->reverse()
            ->take(5);

        return view('livewire.user.earnings-chart', [
            'statsData' => $statsData,
            'dailyStats' => $dailyStats
        ]);
    }


    private function getStatsData()
    {
        $user = Auth::user();
        $linkIds = $user->links()->pluck('id');

        $startDate = Carbon::parse($this->selectedMonth)->startOfMonth();
        $endDate = Carbon::parse($this->selectedMonth)->endOfMonth();

        // ── Query 1: Per-day link click earnings (CPM + bonuses) ─────────────
        $dailyData = LinkClick::whereIn('link_id', $linkIds)
            ->where('is_skipped', false)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_clicks,
                SUM(CASE WHEN cpm_rate > 0 THEN 1 ELSE 0 END) as paid_clicks,
                SUM(cpm_rate) as total_cpm_rate,
                SUM(bonus_amount) as total_bonus_amount
            ')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        // ── Query 2: Per-day referral commission earnings ─────────────────────
        $referralData = ReferralTransaction::where('referrer_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total_referral')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // ── Build final stats array ───────────────────────────────────────────
        $statsData = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $row        = $dailyData->get($dateString);
            $refRow     = $referralData->get($dateString);

            $views            = $row ? (int)   $row->total_clicks      : 0;
            $paidClicks       = $row ? (int)   $row->paid_clicks       : 0;
            $totalCpmRateSum  = $row ? (float) $row->total_cpm_rate    : 0.0;
            $totalBonusAmount = $row ? (float) $row->total_bonus_amount : 0.0;
            $referralEarnings = $refRow ? (float) $refRow->total_referral : 0.0;

            // Earnings = base (sum of cpm_rates / 1000) + bonus amounts tracked per click
            $earnings = ($totalCpmRateSum / 1000) + $totalBonusAmount;

            // Average CPM = average cpm_rate among PAID clicks only
            $cpm = $paidClicks > 0 ? ($totalCpmRateSum / $paidClicks) : 0;

            $statsData[] = [
                'date'               => $dateString,
                'views'              => $views,
                'paid_views'         => $paidClicks,
                'publisher_earnings' => round($earnings, 6),
                'cpm'                => round($cpm, 4),
                'referral_earnings'  => round($referralEarnings, 6),
            ];

            $currentDate->addDay();
        }

        return $statsData;
    }
}
