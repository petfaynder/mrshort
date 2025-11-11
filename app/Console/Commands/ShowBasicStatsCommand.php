<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Link; // Add this line
use App\Models\User; // Add this line

class ShowBasicStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show:basic-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays basic statistics (total links, clicks, earnings)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $totalLinks = Link::count();
        $totalClicks = Link::sum('clicks');
        $totalEarnings = User::sum('earnings');

        $this->info('--- Basic Statistics ---');
        $this->info("Total Links: {$totalLinks}");
        $this->info("Total Clicks: {$totalClicks}");
        $this->info("Total Earnings: $" . number_format($totalEarnings, 2));
        $this->info('------------------------');
    }
}
