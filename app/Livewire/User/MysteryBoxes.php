<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\UserMysteryBox;
use Illuminate\Support\Facades\Auth;

class MysteryBoxes extends Component
{
    public $unopenedBoxes = [];
    public $openingBox = null;
    public $showResultModal = false;
    public $wonReward = null;

    public function mount()
    {
        $this->loadBoxes();
    }

    public function loadBoxes()
    {
        $this->unopenedBoxes = UserMysteryBox::getUnopenedForUser(Auth::id());
    }

    public function openBox($boxId)
    {
        $box = UserMysteryBox::where('id', $boxId)
            ->where('user_id', Auth::id())
            ->where('is_opened', false)
            ->first();

        if (!$box) {
            return;
        }

        $this->openingBox = $box;

        // Small delay for animation
        $this->dispatch('box-opening', boxId: $boxId);
    }

    public function confirmOpen($boxId)
    {
        $box = UserMysteryBox::where('id', $boxId)
            ->where('user_id', Auth::id())
            ->where('is_opened', false)
            ->first();

        if (!$box) {
            return;
        }

        $result = $box->open();

        if ($result['success']) {
            $this->wonReward = $result['reward'];
            $this->showResultModal = true;
        }

        $this->openingBox = null;
        $this->loadBoxes();
    }

    public function closeResultModal()
    {
        $this->showResultModal = false;
        $this->wonReward = null;
    }

    public function render()
    {
        return view('livewire.user.mystery-boxes');
    }
}
