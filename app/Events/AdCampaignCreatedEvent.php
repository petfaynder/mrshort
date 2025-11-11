<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\AdCampaign;
use App\Models\User;

class AdCampaignCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $adCampaign;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(AdCampaign $adCampaign, User $user)
    {
        $this->adCampaign = $adCampaign;
        $this->user = $user;
    }
}