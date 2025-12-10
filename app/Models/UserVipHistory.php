<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVipHistory extends Model
{
    use HasFactory;

    protected $table = 'user_vip_history';

    protected $fillable = [
        'user_id',
        'month',
        'earnings',
        'vip_level_id',
    ];

    protected $casts = [
        'earnings' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vipLevel(): BelongsTo
    {
        return $this->belongsTo(VipLevel::class);
    }

    /**
     * Get or create history for current month
     */
    public static function getCurrentMonth(int $userId): self
    {
        $month = now()->format('Y-m');
        
        return self::firstOrCreate(
            ['user_id' => $userId, 'month' => $month],
            ['earnings' => 0, 'vip_level_id' => null]
        );
    }

    /**
     * Add earnings and update VIP level
     */
    public function addEarnings(float $amount): void
    {
        $this->increment('earnings', $amount);
        
        // Update VIP level based on new earnings
        $newLevel = VipLevel::getByEarnings($this->earnings);
        
        if ($newLevel && $newLevel->id !== $this->vip_level_id) {
            $this->update(['vip_level_id' => $newLevel->id]);
            $this->user->update(['vip_level_id' => $newLevel->id]);
        }
    }

    /**
     * Get previous month history
     */
    public static function getPreviousMonth(int $userId): ?self
    {
        $month = now()->subMonth()->format('Y-m');
        return self::where('user_id', $userId)->where('month', $month)->first();
    }

    /**
     * Calculate starting VIP level for new month based on previous month
     */
    public static function calculateStartingLevel(int $userId): ?VipLevel
    {
        $previous = self::getPreviousMonth($userId);
        
        if (!$previous || !$previous->vip_level_id) {
            return null;
        }

        $previousLevel = $previous->vipLevel;
        
        // Diamond → Start at Silver minimum
        if ($previousLevel->name === 'Diamond') {
            return VipLevel::where('name', 'Silver')->first();
        }
        
        // Platinum → Start at Bronze minimum
        if ($previousLevel->name === 'Platinum') {
            return VipLevel::where('name', 'Bronze')->first();
        }

        return null; // Start fresh
    }
}
