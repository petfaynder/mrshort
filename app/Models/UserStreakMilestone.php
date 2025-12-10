<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStreakMilestone extends Model
{
    protected $fillable = [
        'user_id',
        'milestone_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(StreakMilestone::class, 'milestone_id');
    }
}
