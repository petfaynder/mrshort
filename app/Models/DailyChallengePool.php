<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyChallengePool extends Model
{
    protected $table = 'daily_challenge_pool';

    protected $fillable = [
        'title',
        'description',
        'type',
        'target_value',
        'difficulty',
        'points_reward',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get random challenges for today
     */
    public static function getRandomChallenges(int $count = 3)
    {
        return static::where('is_active', true)
            ->inRandomOrder()
            ->limit($count)
            ->get();
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'shorten_links' => 'Link Kısalt',
            'get_clicks' => 'Tıklama Al',
            'different_countries' => 'Farklı Ülke',
            'share_links' => 'Link Paylaş',
            default => $this->type,
        };
    }

    /**
     * Get difficulty color
     */
    public function getDifficultyColorAttribute(): string
    {
        return match ($this->difficulty) {
            'easy' => 'green',
            'medium' => 'yellow',
            'hard' => 'red',
            default => 'gray',
        };
    }
}
