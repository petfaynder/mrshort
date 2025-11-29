<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class QuickShortener extends Component
{
    public $original_url;

    protected $rules = [
        'original_url' => 'required|url',
    ];

    public function shortenLink()
    {
        $this->validate();

        $code = Str::random(6); // Generate a random short code

        $link = Auth::user()->links()->create([
            'original_url' => $this->original_url,
            'code' => $code,
        ]);

        $this->original_url = ''; // Clear the input field
        
        session()->flash('status', 'Link shortened successfully! ' . $link->short_url); // Assuming short_url accessor exists or constructing it
        
        $this->dispatch('linkShortened'); // To update RecentLinks component
    }

    public function render()
    {
        return view('livewire.user.quick-shortener');
    }
}
