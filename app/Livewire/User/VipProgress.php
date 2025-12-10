<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\VipLevel;
use App\Models\UserVipHistory;
use Illuminate\Support\Facades\Auth;

class VipProgress extends Component
{
    public ?VipLevel $currentLevel = null;
    public ?VipLevel $nextLevel = null;
    public array $allLevels = [];
    public float $currentEarnings = 0;
    public float $progressPercent = 0;

    public function mount()
    {
        $this->loadVipData();
    }

    public function loadVipData()
    {
        $user = Auth::user();
        $this->allLevels = VipLevel::getAllActive()->toArray();
        
        // Get current month's history
        $history = UserVipHistory::getCurrentMonth($user->id);
        $this->currentEarnings = $history->earnings;
        
        // Get current VIP level
        $this->currentLevel = VipLevel::getByEarnings($this->currentEarnings);
        
        // Find next level
        if ($this->currentLevel) {
            $this->nextLevel = VipLevel::where('is_active', true)
                ->where('order', '>', $this->currentLevel->order)
                ->orderBy('order')
                ->first();
            
            // Calculate progress to next level
            if ($this->nextLevel) {
                $rangeStart = $this->currentLevel->min_earnings;
                $rangeEnd = $this->nextLevel->min_earnings;
                $progress = $this->currentEarnings - $rangeStart;
                $range = $rangeEnd - $rangeStart;
                $this->progressPercent = $range > 0 ? min(100, ($progress / $range) * 100) : 0;
            } else {
                $this->progressPercent = 100; // Max level
            }
        }
    }

    public function render()
    {
        return view('livewire.user.vip-progress')
            ->layout('components.user-dashboard-layout');
    }
}
