<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\AdCampaign;

class AdCampaigns extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $adCampaigns;

    public function mount()
    {
        $this->adCampaigns = AdCampaign::where('user_id', auth()->id())->get();
    }

    public function deleteCampaign($campaignId)
    {
        $campaign = AdCampaign::where('user_id', auth()->id())->findOrFail($campaignId);
        $campaign->delete();
        session()->flash('success', 'Reklam kampanyası başarıyla silindi.');
        $this->adCampaigns = AdCampaign::where('user_id', auth()->id())->get(); // Listeyi güncelle
    }

    public function render()
    {
        return view('livewire.user.ad-campaigns', [
            'adCampaigns' => $this->adCampaigns,
        ]);
    }
}
