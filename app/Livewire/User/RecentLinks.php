<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Link;

class RecentLinks extends Component
{
    protected $listeners = ['linkShortened' => '$refresh'];

    public function deleteLink($linkId)
    {
        $link = Auth::user()->links()->find($linkId);

        if ($link) {
            $link->delete();
            $this->dispatch('linkDeleted'); // Optional, if we want to show a toast
        }
    }

    public function render()
    {
        $links = Auth::user()->links()->latest()->take(5)->get();

        return view('livewire.user.recent-links', [
            'links' => $links
        ]);
    }
}
