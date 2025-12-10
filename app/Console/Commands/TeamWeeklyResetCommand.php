<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Team;

class TeamWeeklyResetCommand extends Command
{
    protected $signature = 'team:weekly-reset';
    protected $description = 'Reset weekly team points and distribute rewards';

    public function handle()
    {
        $this->info('Starting weekly team reset...');
        
        // Get top 3 teams before reset for rewards
        $topTeams = Team::where('is_active', true)
            ->orderBy('weekly_points', 'desc')
            ->limit(3)
            ->get();

        // Distribute rewards to top teams
        $rewards = [
            0 => 1000, // 1st place
            1 => 500,  // 2nd place
            2 => 250,  // 3rd place
        ];

        foreach ($topTeams as $index => $team) {
            $rewardPoints = $rewards[$index] ?? 0;
            
            if ($rewardPoints > 0) {
                $rank = $index + 1;
                $this->info("Rewarding team '{$team->name}' (Rank #{$rank}): {$rewardPoints} points to each member");
                
                // Give points to each team member
                foreach ($team->members as $member) {
                    $member->user->increment('gamification_points', $rewardPoints);
                }
            }
        }

        // Reset all team weekly points
        $resetCount = Team::where('is_active', true)->update(['weekly_points' => 0]);
        
        $this->info("Reset weekly points for {$resetCount} teams.");
        $this->info('Weekly team reset completed.');
        
        return Command::SUCCESS;
    }
}
