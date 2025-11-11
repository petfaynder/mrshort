<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LinkClick; // Add this line
use Illuminate\Database\Eloquent\Relations\HasMany; // Add this line
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Add this line

class WithdrawalRequest extends Model
{

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'status',
    ];

    /**
     * Get the user that owns the withdrawal request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the clicks associated with the withdrawal request.
     */
    public function clicks(): HasMany // Dönüş tipi ipucu düzeltildi
    {
        return $this->hasMany(LinkClick::class, 'withdrawal_id');
    }
}
