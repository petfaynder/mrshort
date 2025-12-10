<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserShare extends Model
{
    protected $fillable = [
        'user_id',
        'share_type',
        'platform',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
