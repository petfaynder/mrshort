<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSpin extends Model
{
    protected $fillable = [
        'user_id',
        'prize_id',
        'prize_value',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prize(): BelongsTo
    {
        return $this->belongsTo(DailySpinPrize::class, 'prize_id');
    }

    /**
     * Check if user can spin today
     */
    public static function canUserSpin(int $userId): bool
    {
        $cooldownHours = \App\Models\GamificationSetting::where('setting_key', 'spin_cooldown_hours')->first();
        $hours = $cooldownHours ? (int) $cooldownHours->setting_value : 24;

        $lastSpin = static::where('user_id', $userId)
            ->where('created_at', '>=', now()->subHours($hours))
            ->first();

        return $lastSpin === null;
    }

    /**
     * Get time until next spin
     */
    public static function timeUntilNextSpin(int $userId): ?int
    {
        $cooldownHours = \App\Models\GamificationSetting::where('setting_key', 'spin_cooldown_hours')->first();
        $hours = $cooldownHours ? (int) $cooldownHours->setting_value : 24;

        $lastSpin = static::where('user_id', $userId)
            ->latest()
            ->first();

        if (!$lastSpin) {
            return 0;
        }

        $nextSpinTime = $lastSpin->created_at->addHours($hours);
        
        if ($nextSpinTime->isPast()) {
            return 0;
        }

        return now()->diffInSeconds($nextSpinTime);
    }
}
