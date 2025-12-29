<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Ticket;

class AdminMessageModal extends Component
{
    public bool $showModal = false;
    public ?Ticket $ticket = null;

    public function mount()
    {
        $user = auth()->user();
        
        if ($user && $user->has_admin_message && $user->admin_message_ticket_id) {
            $this->ticket = Ticket::with('replies.user')->find($user->admin_message_ticket_id);
            $this->showModal = true;
        }
    }

    public function dismiss()
    {
        $user = auth()->user();
        $user->update([
            'has_admin_message' => false,
            'admin_message_ticket_id' => null,
        ]);
        
        $this->showModal = false;
    }

    public function viewTicket()
    {
        $ticketId = $this->ticket?->id;
        $this->dismiss();
        
        return redirect()->route('user.contact', ['open_ticket' => $ticketId]);
    }

    public function render()
    {
        return view('livewire.user.admin-message-modal');
    }
}
