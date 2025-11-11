<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\AdCampaign;
use Illuminate\Support\Facades\Auth;
use App\Enums\CampaignType;
use App\Models\CpmRate;
use App\Models\CpmTier;
use App\Models\Country;
use Carbon\Carbon;

class EditAdCampaign extends Component
{
    protected $layout = 'components.user-dashboard-layout';

    public AdCampaign $adCampaign;
    public $originalPopupUrl; // To track changes for admin approval

    public $name = '';
    public $popup_url = '';
    public $desired_clicks = 1000;
    public $calculated_cost = 0;
    public $selectedCountries = [];
    public $selectedAgeRanges = [];
    public $selectedDevices = [];
    public $selectedOs = [];

    // Zamanlama ve Limitler
    public $start_date;
    public $end_date;
    public $daily_click_limit;
    public $budget = 0;
    public $run_until_budget_depleted = false;

    // Trafik Bilgileri (Salt okunur)
    public $estimated_traffic = 0;
    public $available_traffic = 0;

    // Sabit değerler
    public $campaign_type = 'user';
    public $is_active = false;
    public $daily_budget = 1;
    public $bidding_strategy = 'cpm';

    protected $rules = [
        'name' => 'required|string|max:255',
        'popup_url' => 'required|url|max:2048',
        'desired_clicks' => 'required|integer|min:1000',
        'selectedCountries' => 'required|array|min:1',
        'selectedAgeRanges' => 'required|array|min:1',
        'selectedDevices' => 'required|array|min:1',
        'selectedOs' => 'required|array|min:1',
        'daily_budget' => 'nullable|numeric|min:0',
        'budget' => 'nullable|numeric|min:0',
        'run_until_budget_depleted' => 'boolean',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date|required_if:run_until_budget_depleted,false',
        'daily_click_limit' => 'nullable|integer|min:0',
    ];

    public function mount(AdCampaign $adCampaign)
    {
        $this->adCampaign = $adCampaign;

        if ($this->adCampaign->user_id !== Auth::id()) {
            abort(403);
        }

        $this->name = $this->adCampaign->name;
        $this->campaign_type = $this->adCampaign->campaign_type->value; // Enum'dan string değer al
        $this->is_active = $this->adCampaign->is_active;

        // Targeting rules
        $targetingRules = $this->adCampaign->targeting_rules;
        $this->selectedCountries = $targetingRules['countries'] ?? [];
        $this->selectedAgeRanges = $targetingRules['age_ranges'] ?? [];
        $this->selectedDevices = $targetingRules['devices'] ?? [];
        $this->selectedOs = $targetingRules['os'] ?? [];

        // Scheduling and Limits
        $this->start_date = $this->adCampaign->start_date?->format('Y-m-d');
        $this->end_date = $this->adCampaign->end_date?->format('Y-m-d');
        $this->daily_click_limit = $this->adCampaign->daily_click_limit;
        $this->budget = $this->adCampaign->budget;
        $this->run_until_budget_depleted = $this->adCampaign->run_until_budget_depleted;
        $this->daily_budget = $this->adCampaign->daily_budget;

        // Get popup_url from AdCampaign's targeting_rules
        $this->popup_url = $targetingRules['popup_url'] ?? '';
        $this->originalPopupUrl = $this->popup_url; // Store original URL

        $this->calculateCostAndTraffic();
    }

    public function updatedDesiredClicks()
    {
        $this->calculateCostAndTraffic();
    }

    public function updatedSelectedCountries()
    {
        $this->calculateCostAndTraffic();
    }

    public function updatedSelectedAgeRanges()
    {
        $this->calculateCostAndTraffic();
    }

    public function updatedSelectedDevices()
    {
        $this->calculateCostAndTraffic();
    }

    public function updatedSelectedOs()
    {
        $this->calculateCostAndTraffic();
    }

    public function calculateCostAndTraffic()
    {
        $baseAdvertiserCpmRate = CpmRate::whereNull('country_id')->whereNull('cpm_tier_id')->first()?->advertiser_rate ?? 1.00;
        $averageAdvertiserCpmRate = $baseAdvertiserCpmRate;

        if (!empty($this->selectedCountries)) {
            $totalAdvertiserCpmRate = 0;
            $countryCount = 0;
            foreach ($this->selectedCountries as $isoCode) {
                $country = Country::where('iso_code', $isoCode)->first();
                if ($country) {
                    $cpmRate = CpmRate::where('country_id', $country->id)->first();
                    if ($cpmRate && $cpmRate->advertiser_rate !== null) {
                        $totalAdvertiserCpmRate += $cpmRate->advertiser_rate;
                    } elseif ($country->cpmTier) {
                        $tierCpmRate = CpmRate::where('cpm_tier_id', $country->cpmTier->id)->first();
                        $totalAdvertiserCpmRate += $tierCpmRate->advertiser_rate ?? $country->cpmTier->default_advertiser_cpm_rate;
                    } else {
                        $totalAdvertiserCpmRate += $baseAdvertiserCpmRate;
                    }
                } else {
                    $totalAdvertiserCpmRate += $baseAdvertiserCpmRate;
                }
                $countryCount++;
            }
            $averageAdvertiserCpmRate = $countryCount > 0 ? $totalAdvertiserCpmRate / $countryCount : $baseAdvertiserCpmRate;
        }

        $estimatedImpressions = $this->desired_clicks;
        $this->calculated_cost = ($estimatedImpressions / 1000) * $averageAdvertiserCpmRate;
        // Daily budget calculation might need adjustment based on total budget and duration
        // For now, keep it simple or remove if not directly used for display
        // $this->daily_budget = max(1, ceil($this->calculated_cost / 30));

        $this->available_traffic = 0;
        if (!empty($this->selectedCountries)) {
            $trafficPerCountry = 100000;
            $this->available_traffic = count($this->selectedCountries) * $trafficPerCountry;
        } else {
            $this->available_traffic = 500000;
        }
        $this->estimated_traffic = min($this->desired_clicks * 2, $this->available_traffic);
    }

    public function updateCampaign()
    {
        $this->validate();

        $targetingRules = [
            'countries' => $this->selectedCountries,
            'age_ranges' => $this->selectedAgeRanges,
            'devices' => $this->selectedDevices,
            'os' => $this->selectedOs,
        ];

        $needsAdminApproval = false;
        if ($this->popup_url !== $this->originalPopupUrl) {
            $needsAdminApproval = true;
        }

        $this->adCampaign->update([
            'name' => $this->name,
            'campaign_type' => $this->campaign_type,
            'is_active' => $needsAdminApproval ? false : $this->is_active, // Set to false if URL changed
            'targeting_rules' => $targetingRules,
            'daily_budget' => $this->daily_budget,
            'budget' => $this->budget,
            'run_until_budget_depleted' => $this->run_until_budget_depleted,
            'bidding_strategy' => $this->bidding_strategy,
            'start_date' => $this->start_date,
            'end_date' => $this->run_until_budget_depleted ? null : $this->end_date,
            'daily_click_limit' => $this->daily_click_limit,
            'estimated_traffic' => $this->estimated_traffic,
            'available_traffic' => $this->available_traffic,
        ]);

        // Update the popup URL in AdCampaign's targeting_rules
        $updatedTargetingRules = array_merge($targetingRules, [
            'popup_url' => $this->popup_url,
            'is_popup_campaign' => true, // Ensure it's still marked as a popup campaign
            'popup_title' => $this->name, // Update title as well
            'popup_content' => 'Bu bir kullanıcı pop-up reklamıdır.', // Update content as well
        ]);
        $this->adCampaign->update(['targeting_rules' => $updatedTargetingRules]);

        if ($needsAdminApproval) {
            session()->flash('success', 'Reklam kampanyanız güncellendi. URL değişikliği nedeniyle admin onayı bekleniyor!');
        } else {
            session()->flash('success', 'Reklam kampanyası başarıyla güncellendi.');
        }

        return redirect()->route('user.ads.index');
    }

    public function render()
    {
        return view('livewire.user.edit-ad-campaign', [
            'countries' => Country::whereNotNull('name')->orderBy('name')->get(),
            'ageRanges' => ['18-24', '25-34', '35-44', '45-54', '55+'],
            'deviceOptions' => [
                'desktop' => '💻 Masaüstü',
                'mobile' => '📱 Mobil',
                'tablet' => '📟 Tablet',
            ],
            'osOptions' => [
                'ios' => '🍎 iOS',
                'android' => '🤖 Android',
                'windows' => '🪟 Windows',
                'macos' => '💻 macOS',
                'linux' => '🐧 Linux',
                'other' => 'Diğer',
            ],
        ]);
    }
}
