<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\LevelConfiguration;
use Illuminate\Support\Facades\Log;

class LevelUpCheckJob implements ShouldQueue
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
        Log::info('LevelUpCheckJob started.');

        // Tüm kullanıcıları döngüye al
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                $currentLevel = $user->userLevel ? $user->userLevel->level : 0;
                $nextLevelConfig = LevelConfiguration::where('level', $currentLevel + 1)->first();

                if ($nextLevelConfig && $user->gamification_points >= $nextLevelConfig->required_experience) {
                    if ($user->userLevel) {
                        $user->userLevel->update(['level' => $nextLevelConfig->level, 'experience_points' => $user->gamification_points]);
                    } else {
                        $user->userLevel()->create(['level' => $nextLevelConfig->level, 'experience_points' => $user->gamification_points]);
                    }
                    Log::info('User ' . $user->id . ' leveled up to level ' . $nextLevelConfig->level);
                    // Seviye atlama ödülü varsa dağıtılabilir
                }
            }
        });

        Log::info('LevelUpCheckJob completed.');
    }
}