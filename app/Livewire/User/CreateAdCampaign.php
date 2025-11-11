<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\AdCampaign;
use App\Models\Country;
use App\Models\CampaignTemplate; // Added for potential future use
use App\Models\CampaignTemplateStep; // Added for potential future use
use App\Models\CampaignTemplateAd; // Added for potential future use
use App\Models\CpmRate;
use App\Models\CpmTier; // Add this import
use Illuminate\Support\Facades\Auth;
use App\Enums\CampaignType;
use App\Enums\StepType;
use App\Enums\AdType;
use Carbon\Carbon; // Add this import

class CreateAdCampaign extends Component
{
    protected $layout = 'components.user-dashboard-layout';

    public $name = '';
    public $popup_url = '';
    public $desired_clicks = 1000;
    public $calculated_cost = 0;
    public $selectedCountries = [];
    public $selectedAgeRanges = []; // Yeni: Hedef yaş grupları

    // Zamanlama ve Limitler
    public $start_date;
    public $end_date;
    public $daily_click_limit;
    public $budget = 0; // Yeni: Toplam bütçe
    public $run_until_budget_depleted = false; // Yeni: Bakiye bitene kadar devam et

    // Yeni: Hedefleme seçenekleri
    public $selectedDevices = [];
    public $selectedOs = [];

    // Trafik Bilgileri (Salt okunur)
    public $estimated_traffic = 0;
    public $available_traffic = 0;

    // Sabit değerler
    public $campaign_type = 'user';
    public $is_active = false; // Admin onayı bekleniyor
    public $daily_budget = 1; // Minimum başlangıç bütçesi
    public $bidding_strategy = 'cpm'; // Pop-up'lar için CPM daha uygun olabilir

    protected $rules = [
        'name' => 'required|string|max:255',
        'popup_url' => 'required|url|max:2048',
        'desired_clicks' => 'required|integer|min:1000',
        'selectedCountries' => 'required|array|min:1', // Ülke seçimi zorunlu
        'selectedAgeRanges' => 'required|array|min:1', // Yaş grubu seçimi zorunlu
        'selectedDevices' => 'required|array|min:1', // Cihaz seçimi zorunlu
        'selectedOs' => 'required|array|min:1', // İşletim sistemi seçimi zorunlu
        'daily_budget' => 'nullable|numeric|min:0', // Günlük bütçe 0 olabilir (limitsiz)
        'budget' => 'nullable|numeric|min:0', // Toplam bütçe 0 olabilir (limitsiz)
        'run_until_budget_depleted' => 'boolean',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date|required_if:run_until_budget_depleted,false',
        'daily_click_limit' => 'nullable|integer|min:0', // Günlük tıklama limiti 0 olabilir (limitsiz)
    ];

    public function mount()
    {
        $this->start_date = Carbon::now()->format('Y-m-d');
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

    public function calculateCostAndTraffic()
    {
        $baseAdvertiserCpmRate = CpmRate::whereNull('country_id')->whereNull('cpm_tier_id')->first()?->advertiser_rate ?? 1.00; // Genel varsayılan Reklamveren CPM
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

        // Maliyet hesaplama
        $estimatedImpressions = $this->desired_clicks; // Basitlik adına tıklama = gösterim varsayımı
        $this->calculated_cost = ($estimatedImpressions / 1000) * $averageAdvertiserCpmRate;
        $this->daily_budget = max(1, ceil($this->calculated_cost / 30)); // Aylık maliyetin 1/30'u günlük bütçe, min 1$

        // Tahmini ve Mevcut Trafik hesaplaması (Basit bir örnek)
        // Gerçekte bu, daha karmaşık algoritmalar ve veritabanı sorguları gerektirecektir.
        $this->available_traffic = 0;
        if (!empty($this->selectedCountries)) {
            // Her ülke için varsayılan bir trafik değeri atayalım
            $trafficPerCountry = 100000; // Örnek
            $this->available_traffic = count($this->selectedCountries) * $trafficPerCountry;
        } else {
            $this->available_traffic = 500000; // Tüm ülkeler için varsayılan
        }
        // Yaş grupları da trafiği etkileyebilir, şimdilik basit tutalım
        $this->estimated_traffic = min($this->desired_clicks * 2, $this->available_traffic); // İstenen tıklamanın 2 katı veya mevcut trafikten az
    }

    public function createCampaign()
    {
        $this->validate();

        $targetingRules = [
            'countries' => $this->selectedCountries,
            'age_ranges' => $this->selectedAgeRanges,
            // Cihaz, tarayıcı vb. gibi diğer hedeflemeler admin tarafından yönetilecek
        ];

        $targetingRules = [
            'countries' => $this->selectedCountries,
            'age_ranges' => $this->selectedAgeRanges,
            'devices' => $this->selectedDevices,
            'os' => $this->selectedOs,
        ];

        $campaign = AdCampaign::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'campaign_type' => $this->campaign_type,
            'is_active' => $this->is_active, // Admin onayı bekleniyor
            'targeting_rules' => $targetingRules,
            'daily_budget' => $this->daily_budget,
            'budget' => $this->budget,
            'run_until_budget_depleted' => $this->run_until_budget_depleted,
            'bidding_strategy' => $this->bidding_strategy,
            'total_impressions' => 0,
            'total_clicks' => 0,
            'start_date' => $this->start_date,
            'end_date' => $this->run_until_budget_depleted ? null : $this->end_date, // Bakiye bitene kadar devam ediyorsa bitiş tarihi null
            'daily_click_limit' => $this->daily_click_limit,
            'estimated_traffic' => $this->estimated_traffic,
            'available_traffic' => $this->available_traffic,
        ]);

        // Pop-up reklam içeriğini doğrudan AdCampaign modelinde saklayalım.
        // Bunun için ad_campaigns tablosuna 'popup_ad_data' (json) ve 'is_popup_campaign' (boolean)
        // gibi sütunlar eklenmesi gerekecektir.
        // Şimdilik bu kısmı yorum satırı yapıp, daha sonra migrasyon ile sütunları ekleyeceğiz.
        // $campaign->update([
        //     'is_popup_campaign' => true,
        //     'popup_ad_data' => [
        //         'title' => $this->name,
        //         'content' => 'Bu bir kullanıcı pop-up reklamıdır.',
        //         'url' => $this->popup_url,
        //     ],
        // ]);
        // Geçici olarak, pop-up URL'sini AdCampaign'in targeting_rules'ına ekleyebiliriz
        // veya AdCampaign modeline yeni bir sütun ekleyebiliriz.
        // Kullanıcının isteği doğrultusunda, AdCampaign'in kendisi bir pop-up kampanyası olarak işaretlenecek
        // ve pop-up verileri doğrudan AdCampaign'de saklanacaktır.
        // Bu, daha sonra CampaignTemplateResource'da bu pop-up'ları seçmek için kullanılacaktır.

        // Şimdilik, AdCampaign'in targeting_rules'ına pop-up URL'sini ekleyelim.
        // Ancak bu ideal bir çözüm değil, yeni bir migrasyon ile özel sütunlar eklemek daha doğru olacaktır.
        $campaign->update([
            'targeting_rules' => array_merge($targetingRules, [
                'popup_url' => $this->popup_url,
                'is_popup_campaign' => true, // Bu kampanyanın bir pop-up kampanyası olduğunu işaretle
                'popup_title' => $this->name,
                'popup_content' => 'Bu bir kullanıcı pop-up reklamıdır.',
            ]),
        ]);

        session()->flash('success', '🎉 Reklam kampanyanız oluşturuldu ve admin onayı bekleniyor!');

        return redirect()->route('user.ads.index');
    }

    public function render()
    {
        return view('livewire.user.create-ad-campaign', [
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
