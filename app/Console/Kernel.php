<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\DailyGoalResetJob;
use App\Jobs\WeeklyGoalResetJob;
use App\Jobs\LevelUpCheckJob;
use App\Jobs\LeaderboardCacheJob;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new DailyGoalResetJob())->daily();
        $schedule->job(new WeeklyGoalResetJob())->weekly();
        $schedule->job(new LevelUpCheckJob())->everyMinute(); // Sık kontrol için her dakika
        $schedule->job(new LeaderboardCacheJob())->hourly(); // Saatlik önbellek yenileme
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}