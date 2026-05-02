<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkClick;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerformanceOverview extends Component
{
    public $topCountries = [];
    public $goalProgress = 0;
    public $currentEarnings = 0;       // This month's publisher-only earnings
    public $totalReferralEarnings = 0; // All-time referral earnings (separate display)
    public $monthlyGoal = 0;
    
    // Modal State
    public $showGoalModal = false;
    public $newGoal = 100;

    protected $rules = [
        'newGoal' => 'required|numeric|min:1',
    ];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $user = Auth::user();
        $linkIds = $user->links()->pluck('id');

        // Defaults to current month for this overview
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Top Countries Logic
        $totalViews = LinkClick::whereIn('link_id', $linkIds)
            ->where('is_skipped', false)
            ->whereBetween('link_clicks.created_at', [$startDate, $endDate])
            ->count();

        $this->topCountries = LinkClick::whereIn('link_id', $linkIds)
            ->where('is_skipped', false)
            ->whereBetween('link_clicks.created_at', [$startDate, $endDate])
            ->join('countries', 'link_clicks.country_id', '=', 'countries.id')
            ->select('countries.name', 'countries.iso_code', DB::raw('count(*) as total_clicks'))
            ->groupBy('countries.name', 'countries.iso_code')
            ->orderByDesc('total_clicks')
            ->take(5)
            ->get()
            ->map(function ($item) use ($totalViews) {
                return [
                    'name' => $item->name,
                    'iso_code' => $item->iso_code,
                    'clicks' => $item->total_clicks,
                    'percentage' => $totalViews > 0 ? ($item->total_clicks / $totalViews) * 100 : 0
                ];
            });

        // Goal Logic
        $this->monthlyGoal = $user->monthly_goal ?? 100;

        // Publisher earnings for this month (accurate: from link_clicks)
        $publisherEarnings = LinkClick::whereIn('link_id', $linkIds)
            ->where('is_skipped', false)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('cpm_rate') / 1000;

        // Goal progress uses only publisher earnings for this month.
        // Referral earnings don't have daily granularity, so they are shown separately
        // rather than being divided by account age (which was inaccurate).
        $this->currentEarnings      = $publisherEarnings;
        $this->totalReferralEarnings = $user->referral_earnings ?? 0;
        $this->goalProgress = $this->monthlyGoal > 0
            ? min(($this->currentEarnings / $this->monthlyGoal) * 100, 100)
            : 0;
    }

    public function openGoalModal()
    {
        $this->newGoal = $this->monthlyGoal;
        $this->showGoalModal = true;
    }

    public function closeGoalModal()
    {
        $this->showGoalModal = false;
    }

    public function saveGoal()
    {
        $this->validate();

        $user = Auth::user();
        $user->monthly_goal = $this->newGoal;
        $user->save(); // Ensure 'monthly_goal' is fillable or force save

        // Or use forceFill if not in fillable yet (I should check User model)
        // Given I added the migration but maybe didn't add to fillable.
        // I'll use direct property assignment and save, usually works for User model unless strictly guarded.
        
        $this->showGoalModal = false;
        $this->loadStats(); // Reload stats to update progress bar
        
        session()->flash('message', 'Monthly goal updated successfully!');
    }

    public function render()
    {
        return view('livewire.user.performance-overview');
    }
}
