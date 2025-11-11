<?php

namespace App\Livewire\User;

use Livewire\Component;
use Carbon\Carbon; // Add this line

use App\Models\LinkClick;
use Illuminate\Support\Facades\Auth;

class StatsDateFilter extends Component
{
    public $selectedMonth;
    public $months = [];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->months = $this->getAvailableMonths();
    }

    public function updatedSelectedMonth()
    {
        // Hem kartların hem de grafiğin dinleyeceği genel bir olay yayınla
        $this->dispatch('dateRangeUpdated', month: $this->selectedMonth);
    }

    private function getAvailableMonths()
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }
        $linkIds = $user->links()->pluck('id');

        return LinkClick::whereIn('link_id', $linkIds)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as value, DATE_FORMAT(created_at, "%M %Y") as label')
            ->groupBy('value', 'label')
            ->orderBy('value', 'desc')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.user.stats-date-filter');
    }
}
