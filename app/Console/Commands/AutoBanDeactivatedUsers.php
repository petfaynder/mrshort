<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoBanDeactivatedUsers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'users:auto-ban-deactivated';

    /**
     * The console command description.
     */
    protected $description = 'Permanently ban users who have been deactivated for more than 30 days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        $usersToban = User::where('status', 'deactivated')
            ->whereNotNull('deactivated_at')
            ->where('deactivated_at', '<', $thirtyDaysAgo)
            ->get();
        
        $count = 0;
        
        foreach ($usersToban as $user) {
            $user->update([
                'status' => 'banned',
                'deactivation_reason' => $user->deactivation_reason . ' [Auto-banned after 30 days of deactivation]',
            ]);
            $count++;
            
            $this->info("Banned user: {$user->email}");
        }
        
        $this->info("Total users banned: {$count}");
        
        return Command::SUCCESS;
    }
}
