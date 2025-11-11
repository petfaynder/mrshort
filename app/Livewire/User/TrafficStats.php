<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkClick;
use Carbon\Carbon;

class TrafficStats extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $startDate;
    public $endDate;
    public $trafficData = [];
    public $totalClicks = 0;

    public function mount()
    {
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->generateTrafficStats();
    }

    public function generateTrafficStats()
    {
        $user = Auth::user();

        $clicks = LinkClick::where('user_id', $user->id)
                            ->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
                            ->get();

        $this->totalClicks = $clicks->count();

        // Cihaz, OS ve Tarayıcıya göre grupla
        $this->trafficData = [
            'device' => $clicks->groupBy('device_type')->map->count(),
            'os' => $clicks->groupBy('os')->map->count(),
            'browser' => $clicks->groupBy('browser')->map->count(),
        ];
    }

    public function render()
    {
        return view('livewire.user.traffic-stats');
    }
}
