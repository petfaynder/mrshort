<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Announcement;
use Carbon\Carbon;

class Notifications extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $notifications; // Property to hold active notifications
    public $notificationCount; // Property to hold the count of active notifications

    public function mount()
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.user.notifications');
    }

    private function loadNotifications()
    {
        $this->notifications = Announcement::where('is_active', true)
                                            ->where('is_notification', true) // Filter for notifications
                                            ->where(function ($query) {
                                                $query->whereNull('published_at')
                                                      ->orWhere('published_at', '<=', Carbon::now());
                                            })
                                            ->where(function ($query) {
                                                $query->whereNull('expires_at')
                                                      ->orWhere('expires_at', '>=', Carbon::now());
                                            })
                                            ->latest()
                                            ->get();

        $this->notificationCount = $this->notifications->count();
    }
}
