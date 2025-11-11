<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\UserAchievement;
use App\Models\GamificationGoal;
use Illuminate\Support\Facades\Log;

class DailyGoalResetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('DailyGoalResetJob started.');

        // Günlük hedefleri bul
        $dailyGoals = GamificationGoal::where('category', 'daily')->get();

        foreach ($dailyGoals as $goal) {
            // Bu hedefe ait tamamlanmamış başarımları sıfırla
            UserAchievement::where('goal_id', $goal->id)
                            ->whereNull('completed_at')
                            ->update(['current_value' => 0]);
        }

        Log::info('DailyGoalResetJob completed.');
    }
}