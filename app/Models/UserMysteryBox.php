<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMysteryBox extends Model
{
    protected $fillable = [
        'user_id',
        'mystery_box_id',
        'source',
        'is_opened',
        'won_contents',
        'opened_at',
    ];

    protected $casts = [
        'is_opened' => 'boolean',
        'won_contents' => 'array',
        'opened_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mysteryBox(): BelongsTo
    {
        return $this->belongsTo(MysteryBox::class);
    }

    /**
     * Open the box and apply rewards
     */
    public function open(): array
    {
        if ($this->is_opened) {
            return ['success' => false, 'message' => 'Kutu zaten açılmış'];
        }

        $reward = $this->mysteryBox->openBox();
        
        // Apply reward
        $user = $this->user;
        
        switch ($reward['type']) {
            case 'points':
                $user->gamification_points += $reward['value'];
                $user->save();
                break;
                
            case 'reward_id':
                UserInventory::create([
                    'user_id' => $user->id,
                    'reward_id' => $reward['value'],
                    'is_active' => true,
                ]);
                break;
                
            case 'streak_freeze':
                $user->streak_freeze_available += $reward['value'] ?? 1;
                $user->save();
                break;
        }

        $this->is_opened = true;
        $this->won_contents = $reward;
        $this->opened_at = now();
        $this->save();

        return [
            'success' => true,
            'reward' => $reward,
        ];
    }

    /**
     * Give a mystery box to user
     */
    public static function giveBox(int $userId, string $tier, string $source): ?self
    {
        $box = MysteryBox::getByTier($tier);
        
        if (!$box) {
            return null;
        }

        return static::create([
            'user_id' => $userId,
            'mystery_box_id' => $box->id,
            'source' => $source,
        ]);
    }

    /**
     * Get unopened boxes for user
     */
    public static function getUnopenedForUser(int $userId)
    {
        return static::where('user_id', $userId)
            ->where('is_opened', false)
            ->with('mysteryBox')
            ->get();
    }
}
