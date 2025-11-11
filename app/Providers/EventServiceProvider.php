<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\LinkCreatedEvent::class => [
            \App\Listeners\UpdateGamificationProgress::class,
        ],
        \App\Events\LinkClickedEvent::class => [
            \App\Listeners\UpdateGamificationProgress::class,
        ],
        \App\Events\LinkSharedEvent::class => [
            \App\Listeners\UpdateGamificationProgress::class,
        ],
        \App\Events\ReferralRegisteredEvent::class => [
            \App\Listeners\UpdateGamificationProgress::class,
        ],
        \App\Events\EarningAchievedEvent::class => [
            \App\Listeners\UpdateGamificationProgress::class,
        ],
        \App\Events\ProfileUpdatedEvent::class => [
            \App\Listeners\UpdateGamificationProgress::class,
        ],
        \App\Events\SupportTicketCreatedEvent::class => [
            \App\Listeners\UpdateGamificationProgress::class,
        ],
        \App\Events\AdCampaignCreatedEvent::class => [
            \App\Listeners\UpdateGamificationProgress::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}