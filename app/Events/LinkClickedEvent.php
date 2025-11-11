<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\LinkClick;
use App\Models\User;

class LinkClickedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $linkClick;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(LinkClick $linkClick, User $user)
    {
        $this->linkClick = $linkClick;
        $this->user = $user;
    }
}