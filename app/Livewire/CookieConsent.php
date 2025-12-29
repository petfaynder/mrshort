<?php

namespace App\Livewire;

use Livewire\Component;

class CookieConsent extends Component
{
    public bool $show = false;
    
    public function mount()
    {
        // Check if cookie consent is enabled in settings
        if (!setting('display_cookie_notification', true)) {
            $this->show = false;
            return;
        }
        
        // Check if user already accepted
        $this->show = !request()->cookie('cookie_consent');
    }
    
    public function accept()
    {
        cookie()->queue('cookie_consent', 'accepted', 60 * 24 * 365); // 1 year
        $this->show = false;
    }
    
    public function render()
    {
        return view('livewire.cookie-consent');
    }
}
