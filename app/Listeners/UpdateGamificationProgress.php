<?php

namespace App\Listeners;

use App\Events\AdCampaignCreatedEvent;
use App\Events\EarningAchievedEvent;
use App\Events\LinkClickedEvent;
use App\Events\LinkCreatedEvent;
use App\Events\LinkSharedEvent;
use App\Events\ProfileUpdatedEvent;
use App\Events\ReferralRegisteredEvent;
use App\Events\SupportTicketCreatedEvent;
use App\Models\GamificationGoal;
use App\Models\UserAchievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateGamificationProgress implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;

        if (!$user) {
            Log::warning('UpdateGamificationProgress: User not found in event.', ['event' => get_class($event)]);
            return;
        }

        // Kullanıcının mevcut başarımlarını çek
        $userAchievements = $user->achievements->keyBy('goal_id');

        // Tüm aktif gamification hedeflerini çek
        $activeGoals = GamificationGoal::where('is_active', true)->get();

        foreach ($activeGoals as $goal) {
            $currentAchievement = $userAchievements->get($goal->id);
            $currentValue = $currentAchievement ? $currentAchievement->current_value : 0;
            $isCompleted = $currentAchievement ? ($currentAchievement->completed_at !== null) : false;

            if ($isCompleted) {
                continue; // Zaten tamamlanmış hedefleri tekrar kontrol etme
            }

            $newValue = $currentValue;

            switch ($goal->type) {
                case 'shorten_links':
                    if ($event instanceof LinkCreatedEvent && $event->user->id === $user->id) {
                        $newValue++;
                    }
                    break;
                case 'clicks':
                    if ($event instanceof LinkClickedEvent && $event->user->id === $user->id) {
                        $newValue++;
                    }
                    break;
                case 'shares':
                    if ($event instanceof LinkSharedEvent && $event->user->id === $user->id) {
                        $newValue++;
                    }
                    break;
                case 'referrals':
                    if ($event instanceof ReferralRegisteredEvent && $event->referrer->id === $user->id) {
                        $newValue++;
                    }
                    break;
                case 'earn_money':
                    if ($event instanceof EarningAchievedEvent && $event->user->id === $user->id) {
                        $newValue += $event->amount; // Kazanılan miktarı ekle
                    }
                    break;
                case 'profile_completion':
                    if ($event instanceof ProfileUpdatedEvent && $event->user->id === $user->id) {
                        // Profil tamamlama mantığı: Kullanıcının profilinin ne kadarının tamamlandığını kontrol et
                        // Basit bir örnek: name, email, payment_settings gibi alanların dolu olması
                        $profileCompleted = true; // Varsayılan olarak tamamlandı
                        if (empty($user->name) || empty($user->email) /* || !isset($user->payment_settings) */) {
                            $profileCompleted = false;
                        }
                        if ($profileCompleted) {
                            $newValue = $goal->target_value; // Hedefi tamamla
                        }
                    }
                    break;
                case 'support_ticket':
                    if ($event instanceof SupportTicketCreatedEvent && $event->user->id === $user->id) {
                        $newValue++;
                    }
                    break;
                case 'create_ad_campaign':
                    if ($event instanceof AdCampaignCreatedEvent && $event->user->id === $user->id) {
                        $newValue++;
                    }
                    break;
                // Diğer hedef türleri buraya eklenebilir
            }

            // İlerleme güncellendi mi?
            if ($newValue !== $currentValue) {
                if ($currentAchievement) {
                    $currentAchievement->current_value = $newValue;
                    if ($newValue >= $goal->target_value) {
                        $currentAchievement->completed_at = now();
                        $this->awardReward($user, $goal);
                    }
                    $currentAchievement->save();
                } else {
                    // Yeni başarım kaydı oluştur
                    $newAchievement = UserAchievement::create([
                        'user_id' => $user->id,
                        'goal_id' => $goal->id,
                        'current_value' => $newValue,
                        'completed_at' => ($newValue >= $goal->target_value) ? now() : null,
                    ]);
                    if ($newValue >= $goal->target_value) {
                        $this->awardReward($user, $goal);
                    }
                }
            }
        }
    }

    private function awardReward($user, $goal)
    {
        if ($goal->reward) {
            // Ödülü kullanıcının envanterine ekle
            $user->inventory()->create([
                'reward_id' => $goal->reward->id,
                'is_active' => false, // Başlangıçta pasif olabilir, kullanıcı etkinleştirmeli
                'expires_at' => null,
            ]);
        }

        // Puan ve Coin ekle
        $user->gamification_points += $goal->points;
        $user->virtual_currency += $goal->coins;
        $user->save();

        // Seviye kontrolü yap
        $this->checkLevelUp($user);

        Log::info('User ' . $user->id . ' completed goal: ' . $goal->title . ' and received rewards.');
    }

    private function checkLevelUp($user)
    {
        $currentLevel = $user->userLevel ? $user->userLevel->level : 0;
        $nextLevelConfig = LevelConfiguration::where('level', $currentLevel + 1)->first();

        if ($nextLevelConfig && $user->gamification_points >= $nextLevelConfig->required_experience) {
            if ($user->userLevel) {
                $user->userLevel->update(['level' => $nextLevelConfig->level, 'experience_points' => $user->gamification_points]);
            } else {
                $user->userLevel()->create(['level' => $nextLevelConfig->level, 'experience_points' => $user->gamification_points]);
            }
            Log::info('User ' . $user->id . ' leveled up to level ' . $nextLevelConfig->level);
            $this->checkLevelUp($user); // Bir sonraki seviyeyi kontrol et
        }
    }
}