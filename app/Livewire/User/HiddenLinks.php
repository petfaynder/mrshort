<?php

namespace App\Livewire\User;

use App\Models\Link;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class HiddenLinks extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $hiddenLinks;

    public function mount()
    {
        $this->loadHiddenLinks();
    }

    public function render()
    {
        return view('livewire.user.hidden-links', [
            'hiddenLinks' => $this->hiddenLinks,
        ]);
    }

    public function unhideLink($linkId)
    {
        $link = Auth::user()->links()->find($linkId);

        if ($link) {
            $link->is_hidden = false;
            $link->save();
            $this->loadHiddenLinks();
            session()->flash('message', 'Bağlantı başarıyla görünür yapıldı.');
        }
    }

    protected function loadHiddenLinks()
    {
        $this->hiddenLinks = Auth::user()->links()->where('is_hidden', true)->get();
    }
}
