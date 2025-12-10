<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'level',
        'is_premium',
        'reward_type',
        'reward_value',
        'reward_name',
        'reward_icon',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_premium' => 'boolean',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    /**
     * Get reward type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->reward_type) {
            'points' => 'Puan',
            'mystery_box' => 'Gizem Kutusu',
            'badge' => 'Rozet',
            'avatar_frame' => 'Avatar Çerçevesi',
            'profile_theme' => 'Profil Teması',
            'xp_boost' => 'XP Boost',
            'streak_freeze' => 'Streak Freeze',
            default => $this->reward_type,
        };
    }

    /**
     * Get reward display text
     */
    public function getDisplayTextAttribute(): string
    {
        return match($this->reward_type) {
            'points' => $this->reward_value . ' Puan',
            'mystery_box' => $this->reward_name,
            'badge' => '"' . $this->reward_name . '" Rozeti',
            'avatar_frame' => $this->reward_name . ' Çerçevesi',
            'profile_theme' => $this->reward_name . ' Teması',
            'xp_boost' => '%' . $this->reward_value . ' XP Boost',
            'streak_freeze' => $this->reward_value . 'x Streak Freeze',
            default => $this->reward_name,
        };
    }
}
