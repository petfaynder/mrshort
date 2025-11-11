<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EarningAchievedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $amount;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, $amount)
    {
        $this->user = $user;
        $this->amount = $amount;
    }
}