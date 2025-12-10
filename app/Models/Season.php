<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'theme',
        'description',
        'image_path',
        'start_at',
        'end_at',
        'premium_price_points',
        'premium_price_money',
        'is_active',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
        'premium_price_points' => 'integer',
        'premium_price_money' => 'decimal:2',
    ];

    public function rewards(): HasMany
    {
        return $this->hasMany(SeasonReward::class)->orderBy('level')->orderBy('is_premium');
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserSeasonProgress::class);
    }

    /**
     * Get currently active season
     */
    public static function getActive(): ?self
    {
        return self::where('is_active', true)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->first();
    }

    /**
     * Get days remaining in the season
     */
    public function getDaysRemainingAttribute(): int
    {
        return max(0, now()->diffInDays($this->end_at, false));
    }

    /**
     * Get total days of the season
     */
    public function getTotalDaysAttribute(): int
    {
        return $this->start_at->diffInDays($this->end_at);
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentAttribute(): float
    {
        $total = $this->total_days;
        $passed = $total - $this->days_remaining;
        return $total > 0 ? round(($passed / $total) * 100, 1) : 0;
    }

    /**
     * Get free rewards for a specific level
     */
    public function getFreeRewardsForLevel(int $level): array
    {
        return $this->rewards()
            ->where('level', '<=', $level)
            ->where('is_premium', false)
            ->get()
            ->toArray();
    }

    /**
     * Get premium rewards for a specific level
     */
    public function getPremiumRewardsForLevel(int $level): array
    {
        return $this->rewards()
            ->where('level', '<=', $level)
            ->where('is_premium', true)
            ->get()
            ->toArray();
    }

    /**
     * Get max level for this season
     */
    public function getMaxLevelAttribute(): int
    {
        return $this->rewards()->max('level') ?? 30;
    }

    /**
     * Calculate XP required for a specific level
     */
    public static function getXpRequiredForLevel(int $level): int
    {
        if ($level <= 10) {
            return $level * 100;
        } elseif ($level <= 20) {
            return 1000 + (($level - 10) * 200);
        } else {
            return 3000 + (($level - 20) * 300);
        }
    }
}
