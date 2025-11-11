<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Add this line
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class Ticket extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory; // Add HasFactory

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'category', // Add category to fillable
        'priority', // Add priority to fillable
    ];

    /**
     * Get the user that owns the ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the replies for the ticket.
     */
    public function replies(): HasMany // Add HasMany import
    {
        return $this->hasMany(TicketReply::class);
    }
}
