<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'ticket_replies';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];

    /**
     * Get the ticket that the reply belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(\App\Models\Ticket::class);
    }

    /**
     * Get the user who wrote the reply.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
