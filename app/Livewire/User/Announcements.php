<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Announcement;
use Carbon\Carbon;

class Announcements extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $announcements; // Property to hold active announcements

    public function mount()
    {
        $this->loadAnnouncements();
    }

    public function render()
    {
        return view('livewire.user.announcements');
    }

    private function loadAnnouncements()
    {
        $this->announcements = Announcement::where('is_active', true)
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
    }
}
