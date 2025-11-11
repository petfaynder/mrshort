<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLevel extends Model
{
    protected $fillable = [
        'user_id',
        'level',
        'experience_points',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function levelConfiguration(): BelongsTo
    {
        return $this->belongsTo(LevelConfiguration::class, 'level', 'level');
    }
}
