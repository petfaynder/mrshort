<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\VipLevel;
use App\Models\UserVipHistory;
use Carbon\Carbon;

class VipMonthlyResetCommand extends Command
{
    protected $signature = 'vip:monthly-reset';
    protected $description = 'Reset VIP levels monthly based on earnings';

    public function handle()
    {
        $this->info('Starting monthly VIP reset...');
        
        $previousMonth = Carbon::now()->subMonth()->format('Y-m');
        $currentMonth = Carbon::now()->format('Y-m');
        
        // Get all users with monthly earnings
        $users = User::where('monthly_earnings', '>', 0)->get();
        
        $this->info("Processing {$users->count()} users...");

        foreach ($users as $user) {
            // Record last month's VIP history
            $lastMonthLevel = VipLevel::getByEarnings($user->monthly_earnings);
            
            if ($lastMonthLevel) {
                UserVipHistory::updateOrCreate(
                    ['user_id' => $user->id, 'month' => $previousMonth],
                    [
                        'earnings' => $user->monthly_earnings,
                        'vip_level_id' => $lastMonthLevel->id,
                    ]
                );
            }
            
            // Determine starting level for new month
            // Diamond -> Silver, Platinum -> Bronze, else start fresh
            $newLevel = null;
            
            if ($lastMonthLevel) {
                if ($lastMonthLevel->name === 'Diamond') {
                    $newLevel = VipLevel::where('name', 'Silver')->first();
                } elseif ($lastMonthLevel->name === 'Platinum') {
                    $newLevel = VipLevel::where('name', 'Bronze')->first();
                }
            }
            
            // Reset monthly earnings
            $user->monthly_earnings = 0;
            $user->vip_level_id = $newLevel?->id ?? VipLevel::where('order', 1)->first()?->id;
            $user->save();
        }

        $this->info('Monthly VIP reset completed.');
        
        return Command::SUCCESS;
    }
}
