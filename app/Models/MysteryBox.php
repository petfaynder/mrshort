<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MysteryBox extends Model
{
    protected $fillable = [
        'name',
        'tier',
        'description',
        'icon',
        'color',
        'contents',
        'is_active',
    ];

    protected $casts = [
        'contents' => 'array',
        'is_active' => 'boolean',
    ];

    public function userBoxes(): HasMany
    {
        return $this->hasMany(UserMysteryBox::class);
    }

    /**
     * Get tier color
     */
    public function getTierColorAttribute(): string
    {
        return match ($this->tier) {
            'bronze' => '#cd7f32',
            'silver' => '#c0c0c0',
            'gold' => '#ffd700',
            'diamond' => '#b9f2ff',
            default => $this->color,
        };
    }

    /**
     * Get tier label
     */
    public function getTierLabelAttribute(): string
    {
        return match ($this->tier) {
            'bronze' => 'Bronz',
            'silver' => 'Gümüş',
            'gold' => 'Altın',
            'diamond' => 'Elmas',
            default => ucfirst($this->tier),
        };
    }

    /**
     * Open box and get random reward
     */
    public function openBox(): array
    {
        $contents = $this->contents;
        $totalProbability = array_sum(array_column($contents, 'probability'));
        $random = mt_rand(1, $totalProbability);
        
        $cumulative = 0;
        foreach ($contents as $content) {
            $cumulative += $content['probability'];
            if ($random <= $cumulative) {
                // Determine reward
                $reward = $content;
                
                if ($content['type'] === 'points' && isset($content['min']) && isset($content['max'])) {
                    $reward['value'] = mt_rand($content['min'], $content['max']);
                }
                
                return $reward;
            }
        }
        
        // Fallback to first item
        return $contents[0] ?? ['type' => 'points', 'value' => 100];
    }

    /**
     * Get box by tier
     */
    public static function getByTier(string $tier): ?self
    {
        return static::where('tier', $tier)
            ->where('is_active', true)
            ->first();
    }
}
