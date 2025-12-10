<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailySpinPrize extends Model
{
    protected $fillable = [
        'name',
        'type',
        'value',
        'probability',
        'color',
        'icon',
        'is_jackpot',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'probability' => 'decimal:2',
        'is_jackpot' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function userSpins(): HasMany
    {
        return $this->hasMany(UserSpin::class, 'prize_id');
    }

    /**
     * Get active prizes ordered by sort_order
     */
    public static function getActivePrizes()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Spin the wheel and get a random prize based on probabilities
     */
    public static function spin()
    {
        $prizes = static::getActivePrizes();
        
        if ($prizes->isEmpty()) {
            return null;
        }

        $totalProbability = $prizes->sum('probability');
        $random = mt_rand(0, $totalProbability * 100) / 100;
        
        $cumulative = 0;
        foreach ($prizes as $prize) {
            $cumulative += $prize->probability;
            if ($random <= $cumulative) {
                return $prize;
            }
        }
        
        // Fallback to last prize
        return $prizes->last();
    }
}
