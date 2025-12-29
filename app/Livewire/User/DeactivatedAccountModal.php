<?php

namespace App\Livewire\User;

use Livewire\Component;
use Carbon\Carbon;

class DeactivatedAccountModal extends Component
{
    public bool $showModal = false;
    public ?string $reason = null;
    public ?string $deactivatedAt = null;
    public bool $isImpersonating = false;

    public function mount()
    {
        $user = auth()->user();
        
        if (!$user) {
            return;
        }
        
        // Don't show modal on support page
        $currentRoute = request()->route()?->getName();
        if ($currentRoute === 'user.contact') {
            return;
        }
        
        // Check if admin is impersonating
        $this->isImpersonating = session()->has('impersonating_from_admin_id');
        
        if ($user->status === 'deactivated') {
            $this->showModal = true;
            $this->reason = $user->deactivation_reason ?? 'No reason provided';
            $this->deactivatedAt = $user->deactivated_at ? Carbon::parse($user->deactivated_at)->format('F j, Y') : null;
        }
    }

    public function dismiss()
    {
        // Only allow dismiss if impersonating
        if ($this->isImpersonating) {
            $this->showModal = false;
        }
    }

    public function goToSupport()
    {
        return redirect()->route('user.contact');
    }

    public function render()
    {
        return view('livewire.user.deactivated-account-modal');
    }
}
