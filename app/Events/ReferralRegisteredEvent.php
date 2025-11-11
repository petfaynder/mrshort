<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ReferralRegisteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $referrer;
    public $referredUser;

    /**
     * Create a new event instance.
     */
    public function __construct(User $referrer, User $referredUser)
    {
        $this->referrer = $referrer;
        $this->referredUser = $referredUser;
    }
}