<?php

namespace App\Livewire\Feedback;

use Livewire\Component;

class Roadmap extends Component
{
    public function render()
    {
        return view('livewire.feedback.roadmap')
            ->layout('layouts.feedback');
    }
}
