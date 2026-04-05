<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkClick;
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

        // Single query: get per-day totals including real cpm_rate sums
        $dailyData = LinkClick::whereIn('link_id', $linkIds)
            ->where('is_skipped', false)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_clicks,
                SUM(CASE WHEN cpm_rate > 0 THEN 1 ELSE 0 END) as paid_clicks,
                SUM(cpm_rate) as total_cpm_rate
            ')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        $statsData = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $row = $dailyData->get($dateString);

            $views      = $row ? (int) $row->total_clicks : 0;
            $paidClicks = $row ? (int) $row->paid_clicks  : 0;
            $totalCpmRateSum = $row ? (float) $row->total_cpm_rate : 0.0;

            // Earnings = sum of cpm_rates / 1000  (cpm_rate is stored as $/1000-views unit)
            $earnings = $totalCpmRateSum / 1000;

            // Average CPM = average cpm_rate among PAID clicks only
            $cpm = $paidClicks > 0 ? ($totalCpmRateSum / $paidClicks) : 0;

            $statsData[] = [
                'date'               => $dateString,
                'views'              => $views,
                'paid_views'         => $paidClicks,
                'publisher_earnings' => round($earnings, 6),
                'cpm'                => round($cpm, 4),
                'referral_earnings'  => 0,
            ];

            $currentDate->addDay();
        }

        return $statsData;
    }
}
