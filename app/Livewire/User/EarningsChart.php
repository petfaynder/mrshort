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
    }

    public function updateChartForMonth($data)
    {
        $this->selectedMonth = $data['month'];
        $this->dispatch('chartDataUpdated', data: $this->getStatsData());
    }

    public function render()
    {
        return view('livewire.user.earnings-chart', [
            'statsData' => $this->getStatsData(),
        ]);
    }


    private function getStatsData()
    {
        $user = Auth::user();
        $linkIds = $user->links()->pluck('id');

        $startDate = Carbon::parse($this->selectedMonth)->startOfMonth();
        $endDate = Carbon::parse($this->selectedMonth)->endOfMonth();

        $query = LinkClick::whereIn('link_id', $linkIds)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $dailyClicks = $query->selectRaw('DATE(created_at) as date, count(*) as total_clicks')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        $statsData = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $clicksToday = $dailyClicks->get($dateString);
            
            $views = $clicksToday ? $clicksToday->total_clicks : 0;
            $earnings = $views * 0.001; // Örnek kazanç hesaplaması
            $cpm = $views > 0 ? ($earnings / $views) * 1000 : 0;
            $referralEarnings = 0; // Referans kazancı için placeholder

            $statsData[] = [
                'date' => $dateString,
                'views' => $views,
                'publisher_earnings' => $earnings,
                'cpm' => $cpm,
                'referral_earnings' => $referralEarnings,
            ];
            
            $currentDate->addDay();
        }

        return $statsData;
    }
}
