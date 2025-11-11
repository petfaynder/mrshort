<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;
use App\Models\User;

class SupportTicketCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Ticket $ticket, User $user)
    {
        $this->ticket = $ticket;
        $this->user = $user;
    }
}