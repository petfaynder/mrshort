<?php

namespace App\Console\Commands;

use App\Models\Link;
use Illuminate\Console\Command;

class DeleteInactiveLinks extends Command
{
    protected $signature = 'links:delete-inactive';
    protected $description = 'Delete links that have not received clicks for X months (configured in site settings)';

    public function handle()
    {
        $months = (int) setting('delete_inactive_links_months', 0);
        
        if ($months <= 0) {
            $this->info('Inactive link deletion is disabled (set to 0 months).');
            return 0;
        }
        
        $cutoffDate = now()->subMonths($months);
        
        // Find links with no clicks or last click older than cutoff
        $query = Link::where(function ($q) use ($cutoffDate) {
            $q->whereDoesntHave('clicks')
              ->where('created_at', '<', $cutoffDate);
        })->orWhere(function ($q) use ($cutoffDate) {
            $q->whereHas('clicks', function ($clickQuery) use ($cutoffDate) {
                $clickQuery->havingRaw('MAX(created_at) < ?', [$cutoffDate]);
            });
        });
        
        $count = $query->count();
        
        if ($count > 0) {
            $query->delete();
            $this->info("Deleted {$count} inactive links (no clicks for {$months} months).");
        } else {
            $this->info('No inactive links found to delete.');
        }
        
        return 0;
    }
}
