<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Ticket;
use App\Models\TicketReply;

class TicketManager extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $tickets;
    public $selectedTicket = null;
    public $userReply;
    public $search = '';
    public $statusFilter = 'all';
    public $newTicketSubject;
    public $newTicketMessage;
    public $newTicketCategory;
    public $newTicketPriority;

    protected $rules = [
        'newTicketSubject' => 'required|string|max:255',
        'newTicketMessage' => 'required|string|max:1000',
        'newTicketCategory' => 'required|string|max:255',
        'newTicketPriority' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->loadTickets();
    }

    public function loadTickets()
    {
        $this->tickets = Auth::user()->tickets()->latest()->get();
    }

    public function selectTicket($ticketId)
    {
        $this->selectedTicket = Auth::user()->tickets()->with('replies')->find($ticketId);
        $this->dispatch('open-ticket-modal');
    }

    public function deselectTicket()
    {
        $this->selectedTicket = null;
    }

    public function sendUserReply()
    {
        $this->validate([
            'userReply' => 'required|string|max:1000',
        ]);

        $this->selectedTicket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $this->userReply,
        ]);

        $this->userReply = '';
        $this->selectedTicket->refresh();
        session()->flash('message', 'Cevabınız başarıyla gönderildi.');
    }

    public function createTicket()
    {
        $this->validate();

        Log::info('Ticket oluşturma denemesi:', [
            'user_id' => Auth::id(),
            'subject' => $this->newTicketSubject,
            'message' => $this->newTicketMessage,
            'category' => $this->newTicketCategory,
            'priority' => $this->newTicketPriority,
        ]);

        try {
            Auth::user()->tickets()->create([
                'subject' => $this->newTicketSubject,
                'message' => $this->newTicketMessage,
                'category' => $this->newTicketCategory,
                'priority' => $this->newTicketPriority,
                'status' => 'open',
            ]);
            Log::info('Ticket başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            Log::error('Ticket oluşturulurken hata oluştu: ' . $e->getMessage());
            session()->flash('error', 'Destek talebi oluşturulurken bir hata oluştu: ' . $e->getMessage());
            return;
        }

        $this->reset(['newTicketSubject', 'newTicketMessage', 'newTicketCategory', 'newTicketPriority']);
        $this->loadTickets();
        $this->dispatch('ticket-created');
    }

    public function closeTicket()
    {
        if ($this->selectedTicket) {
            $this->selectedTicket->status = 'closed';
            $this->selectedTicket->save();
            $this->selectedTicket = null;
            $this->loadTickets();
            session()->flash('message', 'Destek talebi kapatıldı.');
        }
    }

    public function render()
    {
        $query = Auth::user()->tickets();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('subject', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $this->tickets = $query->latest()->get();

        return view('livewire.user.ticket-manager');
    }
}
