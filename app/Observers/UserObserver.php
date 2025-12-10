<?php

namespace App\Observers;

use App\Models\User;
use App\Services\CompetitionService;
use App\Services\DailyActivityService;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * When a new user is created with a referrer, reward the referrer.
     */
    public function created(User $user): void
    {
        if ($user->referred_by_user_id) {
            $this->rewardReferrer($user);
        }
    }

    /**
     * Reward the referrer when a new user signs up
     */
    protected function rewardReferrer(User $newUser): void
    {
        try {
            $referrer = User::find($newUser->referred_by_user_id);
            
            if (!$referrer) {
                return;
            }

            // Give referrer points
            $referralPoints = 500; // Default points for referral
            $referrer->gamification_points += $referralPoints;
            $referrer->save();

            // Update competition score for referrals
            $competitionService = new CompetitionService();
            $competitionService->updateScore($referrer, 'referrals', 1);

            // Update daily challenge (if there's a referral type challenge)
            $activityService = new DailyActivityService();
            $activityService->recordActivity($referrer, 'referral', 1);

            // Give mystery box for every 5 referrals
            $referralCount = User::where('referred_by_user_id', $referrer->id)->count();
            
            if ($referralCount % 5 === 0) {
                // Award a silver mystery box
                \App\Models\UserMysteryBox::giveBox($referrer->id, 'silver', 'referral_milestone');
            }

            Log::info("Referrer {$referrer->id} rewarded for referring user {$newUser->id}");
        } catch (\Exception $e) {
            Log::error('Referral reward failed: ' . $e->getMessage());
        }
    }
}
