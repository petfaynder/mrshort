<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Link;
use App\Models\User;

class LinkSharedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $link;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Link $link, User $user)
    {
        $this->link = $link;
        $this->user = $user;
    }
}