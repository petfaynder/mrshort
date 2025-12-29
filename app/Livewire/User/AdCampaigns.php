<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdCampaign;

class AdCampaigns extends Component
{
    use WithPagination;

    protected $layout = 'components.user-dashboard-layout';

    public $search = '';
    public $status = '';
    public $type = '';

    public function deleteCampaign($campaignId)
    {
        $campaign = AdCampaign::where('user_id', auth()->id())->findOrFail($campaignId);
        $campaign->delete();
        
        // Redirect with flash message
        return redirect()->route('user.ads.index')->with('success', 'Ad campaign successfully deleted.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = AdCampaign::where('user_id', auth()->id());

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->status !== '') {
            if ($this->status === 'active') {
                $query->where('is_active', true);
            } elseif ($this->status === 'paused') {
                $query->where('is_active', false);
            }
        }

        // Type filtering is placeholder as requested fields don't exist yet, 
        // but we keep the structure for future implementation.
        if ($this->type !== '') {
             // Placeholder logic
        }

        return view('livewire.user.ad-campaigns', [
            'adCampaigns' => $query->latest()->paginate(10),
        ]);
    }
}
