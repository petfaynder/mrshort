<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDailyChallenge;
use App\Models\GamificationSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * DailyActivityService
 * 
 * Merkezi gamification entegrasyon servisi.
 * Tüm kullanıcı aktiviteleri bu servis üzerinden işlenir.
 * 
 * Desteklenen aktiviteler:
 * - shorten_links: Link kısaltma
 * - get_clicks: Tıklama alma
 * - different_countries: Farklı ülkelerden tıklama
 * - share_links: Sosyal medya paylaşımı
 * - referral: Referral kayıt
 * - daily_login: Günlük giriş
 */
class DailyActivityService
{
    protected StreakService $streakService;

    public function __construct()
    {
        $this->streakService = new StreakService();
    }

    /**
     * Kullanıcı aktivitesi kaydı
     * 
     * @param User $user
     * @param string $activityType Aktivite türü
     * @param int $amount Miktar (varsayılan 1)
     * @param array $metadata Ek bilgiler (ülke vb.)
     */
    public function recordActivity(User $user, string $activityType, int $amount = 1, array $metadata = []): array
    {
        $results = [
            'activity' => $activityType,
            'amount' => $amount,
            'streak_updated' => false,
            'challenge_updated' => false,
            'xp_earned' => 0,
            'points_earned' => 0,
            'milestones' => [],
        ];

        try {
            // 1. Streak güncelle (her aktivitede)
            $streakResult = $this->streakService->updateStreak($user);
            $results['streak_updated'] = $streakResult['changed'];
            $results['milestones'] = array_merge($results['milestones'], $streakResult['milestones'] ?? []);

            // 2. Daily Challenge güncelle
            $challengeResult = $this->updateDailyChallenge($user, $activityType, $amount, $metadata);
            $results['challenge_updated'] = $challengeResult['updated'];
            $results['points_earned'] += $challengeResult['points_earned'];

            // 3. XP hesapla ve Season Progress'e ekle (Battle Pass)
            $xpEarned = $this->calculateXP($activityType, $amount);
            $results['xp_earned'] = $xpEarned;
            if ($xpEarned > 0) {
                $this->addSeasonXP($user, $xpEarned);
            }

        } catch (\Exception $e) {
            Log::error('DailyActivityService error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'activity' => $activityType,
                'amount' => $amount,
            ]);
        }

        return $results;
    }

    /**
     * Daily Challenge ilerleme güncelleme
     */
    protected function updateDailyChallenge(User $user, string $type, int $amount, array $metadata = []): array
    {
        $result = ['updated' => false, 'points_earned' => 0, 'completed' => []];

        // Bugünkü challenge'ı al veya oluştur
        $todayChallenge = UserDailyChallenge::getOrCreateToday($user->id);

        if (!$todayChallenge) {
            return $result;
        }

        // Özel durumlar
        switch ($type) {
            case 'different_countries':
                // Ülke takibi cache üzerinden
                $country = $metadata['country'] ?? null;
                if ($country) {
                    $countriesKey = 'daily_countries_' . $user->id . '_' . now()->toDateString();
                    $countries = cache()->get($countriesKey, []);
                    
                    if (!in_array($country, $countries)) {
                        $countries[] = $country;
                        cache()->put($countriesKey, $countries, now()->endOfDay());
                        $todayChallenge->updateProgress('different_countries', 1);
                        $result['updated'] = true;
                    }
                }
                break;

            default:
                // Standart ilerleme güncelleme
                $beforePoints = $user->gamification_points;
                $todayChallenge->updateProgress($type, $amount);
                $user->refresh();
                $result['points_earned'] = $user->gamification_points - $beforePoints;
                $result['updated'] = true;
                break;
        }

        return $result;
    }

    /**
     * Belirli bir aktivite için XP hesapla
     */
    protected function calculateXP(string $activityType, int $amount): int
    {
        $xpRates = [
            'shorten_links' => 5,      // Link başına 5 XP
            'get_clicks' => 1,         // 10 tıklama = 10 XP (her tık 1 XP)
            'daily_login' => 25,       // Günlük giriş 25 XP
            'referral' => 100,         // Referral 100 XP
            'share_links' => 10,       // Paylaşım 10 XP
        ];

        $rate = $xpRates[$activityType] ?? 0;
        return $rate * $amount;
    }

    /**
     * Season (Battle Pass) XP ekle
     */
    protected function addSeasonXP(User $user, int $xp): void
    {
        $season = \App\Models\Season::getActive();
        if ($season) {
            $progress = \App\Models\UserSeasonProgress::getOrCreate($user->id, $season->id);
            $progress->addXp($xp);
        }
    }

    /**
     * Sosyal paylaşım kaydı
     */
    public function recordShare(User $user, string $platform, string $shareType): array
    {
        // Günlük/haftalık limit kontrolü
        $limitKey = 'share_limit_' . $user->id . '_' . $shareType . '_' . now()->format('Y-W');
        $currentShares = cache()->get($limitKey, 0);

        $limits = [
            'weekly_stats' => 1,    // Haftalık istatistik paylaşımı 1 kez
            'milestone' => 1,       // Her milestone 1 kez paylaşılabilir
            'competition' => 1,     // Her yarışma 1 kez paylaşılabilir
        ];

        $limit = $limits[$shareType] ?? 1;

        if ($currentShares >= $limit) {
            return ['success' => false, 'message' => 'Paylaşım limiti dolu'];
        }

        // Paylaşım kaydı
        \App\Models\UserShare::create([
            'user_id' => $user->id,
            'share_type' => $shareType,
            'platform' => $platform,
        ]);

        cache()->put($limitKey, $currentShares + 1, now()->endOfWeek());

        // Daily challenge güncelle
        $this->recordActivity($user, 'share_links', 1, ['platform' => $platform]);

        // Puan ver
        $points = [
            'weekly_stats' => 50,
            'milestone' => 100,
            'competition' => 75,
        ];

        $pointReward = $points[$shareType] ?? 50;
        $user->gamification_points += $pointReward;
        $user->save();

        return [
            'success' => true,
            'points_earned' => $pointReward,
        ];
    }
}
