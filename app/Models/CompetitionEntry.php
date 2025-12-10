<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionEntry extends Model
{
    protected $fillable = [
        'competition_id',
        'user_id',
        'score',
        'rank',
        'reward_claimed',
    ];

    protected $casts = [
        'reward_claimed' => 'boolean',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create entry for user in competition
     */
    public static function getOrCreate(int $competitionId, int $userId): self
    {
        return static::firstOrCreate(
            ['competition_id' => $competitionId, 'user_id' => $userId],
            ['score' => 0]
        );
    }

    /**
     * Increment score
     */
    public function incrementScore(int $amount = 1): void
    {
        $this->score += $amount;
        $this->save();
    }
}
