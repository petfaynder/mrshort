<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUnverifiedUsers extends Command
{
    protected $signature = 'users:delete-unverified';
    protected $description = 'Delete users who have not verified their email for X months (configured in site settings)';

    public function handle()
    {
        $months = (int) setting('delete_unverified_users_months', 0);
        
        if ($months <= 0) {
            $this->info('Unverified user deletion is disabled (set to 0 months).');
            return 0;
        }
        
        $cutoffDate = now()->subMonths($months);
        
        // Find unverified users created before cutoff date
        $query = User::whereNull('email_verified_at')
            ->where('created_at', '<', $cutoffDate)
            ->where('is_admin', false); // Never delete admin accounts
        
        $count = $query->count();
        
        if ($count > 0) {
            $query->delete();
            $this->info("Deleted {$count} unverified users (not verified for {$months} months).");
        } else {
            $this->info('No unverified users found to delete.');
        }
        
        return 0;
    }
}
