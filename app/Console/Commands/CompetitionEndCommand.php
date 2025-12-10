<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Competition;
use Carbon\Carbon;

class CompetitionEndCommand extends Command
{
    protected $signature = 'competition:end';
    protected $description = 'End expired competitions and distribute rewards';

    public function handle()
    {
        $endedCompetitions = Competition::where('is_active', true)
            ->where('end_date', '<', Carbon::now())
            ->get();

        $this->info("Found {$endedCompetitions->count()} ended competitions to process.");

        foreach ($endedCompetitions as $competition) {
            $this->info("Processing: {$competition->title}");
            
            // Calculate final ranks
            $competition->calculateRanks();
            
            // Distribute rewards
            $rewardsGiven = $competition->distributeRewards();
            
            $this->info("  - Distributed {$rewardsGiven} rewards");
            
            // Mark competition as inactive
            $competition->update(['is_active' => false]);
            
            $this->info("  - Competition marked as inactive");
        }

        $this->info('Competition end processing completed.');
        
        return Command::SUCCESS;
    }
}
