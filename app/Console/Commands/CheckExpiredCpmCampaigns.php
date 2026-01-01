<?php

namespace App\Console\Commands;

use App\Services\CpmCampaignService;
use Illuminate\Console\Command;

class CheckExpiredCpmCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cpm:check-expired-campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired CPM campaigns and automatically revert rates';

    /**
     * Execute the console command.
     */
    public function handle(CpmCampaignService $campaignService): int
    {
        $this->info('Checking for expired CPM campaigns...');

        $count = $campaignService->checkExpiredCampaigns();

        if ($count > 0) {
            $this->info("✓ {$count} campaign(s) expired and rates reverted.");
        } else {
            $this->info('No expired campaigns found.');
        }

        return Command::SUCCESS;
    }
}
