<?php

namespace App\Services;

use App\Models\User;
use App\Models\GamificationGoal;
use App\Models\UserAchievement;
use App\Models\GamificationReward;
use App\Models\UserReward;
use App\Models\UserLevel;
use App\Models\LevelConfiguration;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    public function updateGoalProgress(User $user, string $goalType, int $progressIncrement = 1)
    {
        // Kullanıcının ilgili hedefteki ilerlemesini bul veya oluştur
        $goal = GamificationGoal::where('type', $goalType)
                                ->where(function ($query) use ($user) {
                                    $query->whereNull('user_id') // Genel hedefler
                                          ->orWhere('user_id', $user->id); // Kullanıcıya özel hedefler
                                })
                                ->where('is_active', true)
                                ->first();

        if (!$goal) {
            return; // Hedef bulunamadı veya aktif değil
        }

        $userAchievement = UserAchievement::firstOrCreate(
            ['user_id' => $user->id, 'goal_id' => $goal->id],
            ['current_value' => 0]
        );

        // İlerleme değerini artır
        $userAchievement->increment('current_value', $progressIncrement);

        // Hedef tamamlandı mı kontrol et
        if ($userAchievement->current_value >= $goal->target_value && is_null($userAchievement->completed_at)) {
            $userAchievement->update(['completed_at' => now()]);
            $this->awardRewardsForGoal($user, $goal, $userAchievement);
        }

        // Deneyim puanlarını güncelle ve seviye atlamayı kontrol et
        $this->updateExperiencePoints($user, $progressIncrement);
    }

    protected function awardRewardsForGoal(User $user, GamificationGoal $goal, UserAchievement $userAchievement)
    {
        $rewards = GamificationReward::where('goal_id', $goal->id)->get();

        foreach ($rewards as $reward) {
            UserReward::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'achievement_id' => $userAchievement->id,
            ]);

            // Ödül türüne göre kullanıcıya ilgili değeri ekle
            $this->distributeReward($user, $reward);
        }
    }

    protected function distributeReward(User $user, GamificationReward $reward)
    {
        switch ($reward->type) {
            case 'points':
                $user->increment('gamification_points', $reward->value);
                break;
            case 'virtual_currency':
                $user->increment('virtual_currency', $reward->value); // User modeline 'virtual_currency' alanı eklenmeli
                break;
            case 'badge':
            case 'avatar_item':
            case 'special_content':
                // Envantere ekle
                $user->inventory()->create([
                    'reward_id' => $reward->id,
                    'quantity' => 1,
                ]);
                break;
            // Diğer ödül türleri eklenebilir
        }
    }

    public function updateExperiencePoints(User $user, int $points)
    {
        $userLevel = UserLevel::firstOrCreate(
            ['user_id' => $user->id],
            ['level' => 1, 'experience_points' => 0]
        );

        $userLevel->increment('experience_points', $points);

        $this->checkLevelUp($user, $userLevel);
    }

    protected function checkLevelUp(User $user, UserLevel $userLevel)
    {
        $nextLevelConfig = LevelConfiguration::where('level', $userLevel->level + 1)->first();

        if ($nextLevelConfig && $userLevel->experience_points >= $nextLevelConfig->required_experience) {
            $userLevel->increment('level');
            // Seviye atlama ödüllerini dağıt
            $levelRewards = GamificationReward::where('is_level_reward', true)
                                            ->whereHas('levelConfigurations', function ($query) use ($userLevel) {
                                                $query->where('level', $userLevel->level);
                                            })
                                            ->get();
            foreach ($levelRewards as $reward) {
                UserReward::create([
                    'user_id' => $user->id,
                    'reward_id' => $reward->id,
                    'achievement_id' => null, // Seviye ödülleri bir başarıya bağlı olmayabilir
                ]);
                $this->distributeReward($user, $reward);
            }
            // Rekürsif olarak bir sonraki seviyeyi kontrol et
            $this->checkLevelUp($user, $userLevel);
        }
    }
}