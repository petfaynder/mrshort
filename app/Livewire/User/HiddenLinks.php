<?php

namespace App\Livewire\User;

use App\Models\Link;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class HiddenLinks extends Component
{
    use WithPagination;

    protected $layout = 'components.user-dashboard-layout';

    public $search = '';
    public $sortStr = 'newest';
    public $selectedLinks = [];
    public $selectAll = false;

    // Reset pagination when filters change
    public function updatedSearch() { $this->resetPage(); }
    public function updatedSortStr() { $this->resetPage(); }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedLinks = $this->getHiddenLinksQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedLinks = [];
        }
    }

    public function updatedSelectedLinks()
    {
        $this->selectAll = false;
    }

    public function render()
    {
        return view('livewire.user.hidden-links', [
            'hiddenLinks' => $this->getHiddenLinksQuery()->paginate(10),
        ]);
    }

    protected function getHiddenLinksQuery()
    {
        $query = Auth::user()->links()->where('is_hidden', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('original_url', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('title', 'like', '%' . $this->search . '%');
            });
        }

        switch ($this->sortStr) {
            case 'oldest':
                $query->oldest();
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        return $query;
    }

    public function unhideLink($linkId)
    {
        $link = Auth::user()->links()->find($linkId);

        if ($link) {
            $link->is_hidden = false;
            $link->save();
            session()->flash('message', 'Link successfully made visible.');
        }
    }

    public function unhideSelected()
    {
        if (empty($this->selectedLinks)) return;

        Auth::user()->links()->whereIn('id', $this->selectedLinks)->update(['is_hidden' => false]);
        
        $this->selectedLinks = [];
        $this->selectAll = false;
        
        session()->flash('message', 'Selected links successfully made visible.');
    }
}
