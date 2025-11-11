<?php

namespace App\Services;

use App\Models\AdCampaign;
use App\Models\CampaignTemplate;
use App\Models\CampaignTemplateStep; // Added
use App\Models\CampaignTemplateAd; // Added
use App\Enums\CampaignType;
use App\Enums\StepType;
use App\Enums\AdType;
use Illuminate\Support\Facades\DB;

class CampaignSeeder
{
    /**
     * Demo kampanyaları oluştur
     */
    public function createDemoCampaigns(): array
    {
        $createdCampaigns = [];

        // Demo şablonları oluştur
        $this->createDemoTemplates();

        // Demo kampanyalar oluştur
        $createdCampaigns[] = $this->createDemoCampaign([
            'name' => 'Teknoloji Ürünleri Kampanyası',
            'template' => 'brand_awareness',
            'targeting' => [
                'countries' => ['TR', 'US'],
                'age_ranges' => ['18-24', '25-34'],
                'interests' => ['teknoloji', 'bilim'],
                'devices' => ['Desktop', 'Mobile'],
            ],
            'budget' => 50.00,
        ]);

        $createdCampaigns[] = $this->createDemoCampaign([
            'name' => 'Mobil Uygulama Tanıtımı',
            'template' => 'traffic_drive',
            'targeting' => [
                'countries' => ['TR'],
                'devices' => ['Mobile'],
                'age_ranges' => ['18-34'],
                'behavioral_categories' => ['online_shopper', 'tech_enthusiast'],
            ],
            'budget' => 25.00,
        ]);

        $createdCampaigns[] = $this->createDemoCampaign([
            'name' => 'Eğitim Platformu',
            'template' => 'lead_generation',
            'targeting' => [
                'countries' => ['TR', 'DE', 'US'],
                'age_ranges' => ['25-44'],
                'interests' => ['eğitim', 'teknoloji'],
                'behavioral_categories' => ['student', 'business_professional'],
            ],
            'budget' => 75.00,
        ]);

        // Demo istatistik verileri ekle
        $this->generateDemoStatistics();

        return $createdCampaigns;
    }

    /**
     * Demo şablonları oluştur
     */
    protected function createDemoTemplates(): void
    {
        $templates = [
            [
                'name' => 'Hızlı Başlat',
                'slug' => 'quick_start',
                'category' => 'quick_start',
                'description' => 'Yeni başlayanlar için optimize edilmiş temel kampanya şablonu',
                'steps' => [
                    [
                        'step_type' => 'interstitial',
                        'wait_time' => 5,
                        'show_popup' => false,
                        'ads' => [
                            [
                                'type' => 'banner_ad',
                                'data' => [
                                    'banner_size' => '728x90',
                                    'banner_responsive' => true,
                                ]
                            ]
                        ]
                    ]
                ],
                'targeting_rules' => [
                    'countries' => ['TR', 'US', 'DE'],
                    'devices' => ['Desktop', 'Mobile'],
                ],
                'default_budget' => 25.00,
                'estimated_performance' => [
                    'estimated_ctr' => 2.1,
                    'estimated_cpc' => 0.85,
                    'estimated_reach' => 150000,
                    'estimated_conversions' => 3150,
                ],
            ],
            [
                'name' => 'Marka Bilinirliği',
                'slug' => 'brand_awareness',
                'category' => 'brand_awareness',
                'description' => 'Marka bilinirliğini artırmaya odaklanan kampanya şablonu',
                'steps' => [
                    [
                        'step_type' => 'banner_page',
                        'wait_time' => 8,
                        'show_popup' => true,
                        'ads' => [
                            [
                                'type' => 'banner_ad',
                                'data' => [
                                    'banner_size' => '300x250',
                                    'banner_responsive' => true,
                                ]
                            ],
                            [
                                'type' => 'popup_ad',
                                'data' => [
                                    'popup_title' => 'Markamızı Keşfedin',
                                    'popup_size' => 'medium',
                                    'popup_delay' => 3,
                                ]
                            ]
                        ]
                    ]
                ],
                'targeting_rules' => [
                    'countries' => ['TR'],
                    'age_ranges' => ['18-24', '25-34', '35-44'],
                ],
                'default_budget' => 50.00,
                'estimated_performance' => [
                    'estimated_ctr' => 1.8,
                    'estimated_cpc' => 1.20,
                    'estimated_reach' => 300000,
                    'estimated_conversions' => 5400,
                ],
            ],
            [
                'name' => 'Potansiyel Müşteri Kazanımı',
                'slug' => 'lead_generation',
                'category' => 'lead_generation',
                'description' => 'Potansiyel müşteri kazanımına yönelik kampanya şablonu',
                'steps' => [
                    [
                        'step_type' => 'interstitial',
                        'wait_time' => 6,
                        'show_popup' => false,
                        'ads' => [
                            [
                                'type' => 'html_ad',
                                'data' => [
                                    'html_content' => '<div class="lead-form"><h3>Ücretsiz Danışmanlık</h3><p>Form içeriği burada yer alacak</p></div>',
                                    'css_styles' => '.lead-form { background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 10px; color: white; }',
                                ]
                            ]
                        ]
                    ]
                ],
                'targeting_rules' => [
                    'countries' => ['TR', 'US'],
                    'age_ranges' => ['25-34', '35-44', '45-54'],
                    'interests' => ['teknoloji', 'finans', 'eğitim'],
                ],
                'default_budget' => 75.00,
                'estimated_performance' => [
                    'estimated_ctr' => 3.2,
                    'estimated_cpc' => 2.50,
                    'estimated_reach' => 80000,
                    'estimated_conversions' => 2560,
                ],
            ],
        ];

        foreach ($templates as $templateData) {
            CampaignTemplate::firstOrCreate(
                ['slug' => $templateData['slug']],
                $templateData
            );
        }
    }

    /**
     * Demo kampanya oluştur
     */
    protected function createDemoCampaign(array $config): AdCampaign
    {
        $template = CampaignTemplate::where('category', $config['template'])->first();

        // Kampanya oluştur
        $campaign = AdCampaign::create([
            'name' => $config['name'],
            'campaign_type' => CampaignType::Admin,
            'is_active' => true,
            'targeting_rules' => $config['targeting'],
            'total_impressions' => rand(1000, 10000),
            'total_clicks' => rand(50, 500),
        ]);

        // Şablon adımlarını kampanyaya ekle (CampaignTemplateStep ve CampaignTemplateAd kullanarak)
        if ($template && isset($template->steps)) {
            foreach ($template->steps as $index => $stepData) {
                $campaignTemplateStep = CampaignTemplateStep::create([
                    'campaign_template_id' => $template->id, // CampaignTemplate'e bağlı
                    'step_number' => $index + 1,
                    'step_type' => $stepData['step_type'] ?? StepType::Interstitial,
                    'wait_time' => $stepData['wait_time'] ?? 5,
                    'show_popup' => $stepData['show_popup'] ?? false,
                    // 'impressions' sütunu CampaignTemplateStep modelinde yok, bu yüzden kaldırıldı.
                ]);

                // Demo reklamları ekle
                if (isset($stepData['ads']) && is_array($stepData['ads'])) {
                    foreach ($stepData['ads'] as $adData) {
                        CampaignTemplateAd::create([
                            'campaign_template_step_id' => $campaignTemplateStep->id,
                            'ad_type' => $this->mapAdType($adData['type']),
                            'ad_data' => $adData['data'] ?? [], // ad_code yerine ad_data kullanılıyor
                            // 'impressions' ve 'clicks' sütunları CampaignTemplateAd modelinde yok, bu yüzden kaldırıldı.
                        ]);
                    }
                }
            }
        }

        return $campaign;
    }

    /**
     * Demo istatistik verileri oluştur
     */
    protected function generateDemoStatistics(): void
    {
        // Son 30 gün için rastgele günlük veriler oluştur
        $days = 30;
        $campaigns = AdCampaign::all();

        foreach ($campaigns as $campaign) {
            for ($i = 0; $i < $days; $i++) {
                $date = now()->subDays($i)->format('Y-m-d');

                // Rastgele gösterim ve tıklama verileri
                $baseImpressions = rand(100, 1000);
                $ctr = rand(15, 50) / 1000; // %1.5 - %5 CTR
                $clicks = (int) ($baseImpressions * $ctr);

                // Demo LinkClick kayıtları oluştur
                $this->createDemoLinkClicks($campaign, $date, $baseImpressions, $clicks);
            }
        }
    }

    /**
     * Demo link tıklamaları oluştur
     */
    protected function createDemoLinkClicks(AdCampaign $campaign, string $date, int $impressions, int $clicks): void
    {
        // AdStep ve StepAd modelleri kaldırıldığı için bu kısım yeniden düzenlenmeli.
        // Şimdilik demo tıklamaları doğrudan CampaignTemplateAd'lere veya genel kampanya seviyesine kaydedilebilir.
        // Ancak, CampaignTemplateAd'ler şablon olduğu için doğrudan tıklama sayacı tutmak mantıklı olmayabilir.
        // Bu nedenle, bu metodun işlevselliği şimdilik devre dışı bırakılıyor veya basitleştiriliyor.
        // Gerçek tıklama takibi LinkController'da CampaignTemplateAd üzerinden yapılacaktır.

        // Geçici olarak bu kısmı devre dışı bırakıyoruz.
        // Eğer demo istatistikleri hala isteniyorsa, CampaignTemplate ve CampaignTemplateStep/Ad
        // modelleri üzerinden yeni bir mantık geliştirilmelidir.

        // $campaignTemplate = $campaign->campaignTemplate; // Eğer AdCampaign'in bir CampaignTemplate'i varsa
        // if (!$campaignTemplate) {
        //     return;
        // }

        // $campaignTemplateSteps = $campaignTemplate->campaignTemplateSteps()->with('campaignTemplateAds')->get();

        // if ($campaignTemplateSteps->isEmpty()) {
        //     return;
        // }

        // for ($i = 0; $i < $impressions; $i++) {
        //     $randomStep = $campaignTemplateSteps->random();
        //     $randomAd = $randomStep->campaignTemplateAds->random();

        //     // Rastgele IP ve ülke
        //     $countries = ['TR', 'US', 'DE', 'FR', 'GB'];
        //     $countryCode = $countries[array_rand($countries)];
        //     $ip = '192.168.' . rand(1, 255) . '.' . rand(1, 255);

        //     // Rastgele cihaz bilgileri
        //     $devices = ['Desktop', 'Mobile', 'Tablet'];
        //     $device = $devices[array_rand($devices)];

        //     // LinkClick oluştur
        //     DB::table('link_clicks')->insert([
        //         'link_id' => 1, // Demo link
        //         'ip_address' => $ip,
        //         'country' => $countryCode,
        //         'city' => 'Demo City',
        //         'device_type' => $device,
        //         'os' => 'Demo OS',
        //         'browser' => 'Demo Browser',
        //         'referrer' => 'Demo Referrer',
        //         'is_bot' => false,
        //         'recent_click_count' => rand(1, 5),
        //         'cpm_rate' => rand(100, 500) / 100,
        //         // 'step_ad_id' => $randomAd->id, // step_ad_id artık yok
        //         'created_at' => $date . ' ' . rand(0, 23) . ':' . rand(0, 59) . ':' . rand(0, 59),
        //         'updated_at' => now(),
        //     ]);
        // }

        // // Kampanya toplam gösterim ve tıklama sayılarını güncelle
        // $campaign->increment('total_impressions', $impressions);
        // $campaign->increment('total_clicks', $clicks);
    }

    /**
     * Reklam türünü eşleştir
     */
    protected function mapAdType(string $type): AdType
    {
        return match($type) {
            'banner_ad' => AdType::Banner,
            'popup_ad' => AdType::Popup,
            'third_party_code' => AdType::ThirdParty,
            'html_ad' => AdType::Html, // HTML ad type'ı eklendi
            default => AdType::Banner
        };
    }

    /**
     * Demo reklam kodu oluştur
     */
    protected function generateDemoAdCode(array $adData): string
    {
        return match($adData['type']) {
            'banner_ad' => $this->generateDemoBannerCode($adData['data'] ?? []),
            'popup_ad' => $this->generateDemoPopupCode($adData['data'] ?? []),
            'html_ad' => $adData['data']['content'] ?? '<div>Demo HTML İçeriği</div>', // ad_data.content olarak güncellendi
            'third_party_code' => $adData['data']['code'] ?? '<!-- Third Party Demo Reklam Kodu -->', // Third party ad code eklendi
            default => '<!-- Demo Reklam Kodu -->'
        };
    }

    /**
     * Demo banner kodu oluştur
     */
    protected function generateDemoBannerCode(array $data): string
    {
        $size = $data['banner_size'] ?? '728x90';
        $url = $data['banner_url'] ?? 'https://example.com';

        return <<<HTML
<div style="width: {$size}; height: auto; background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-family: Arial, sans-serif; cursor: pointer;" onclick="window.open('{$url}', '_blank')">
    <div style="text-align: center;">
        <h3 style="margin: 0; font-size: 16px;">Demo Banner</h3>
        <p style="margin: 5px 0 0 0; font-size: 12px;">Reklam açıklaması burada</p>
    </div>
</div>
HTML;
    }

    /**
     * Demo popup kodu oluştur
     */
    protected function generateDemoPopupCode(array $data): string
    {
        $title = $data['popup_title'] ?? 'Demo Popup';
        $content = $data['popup_content'] ?? 'Bu bir demo popup reklamıdır.';

        return <<<HTML
<div style="background: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 400px;">
    <h3 style="margin-top: 0; color: #333;">{$title}</h3>
    <p style="color: #666; line-height: 1.5;">{$content}</p>
    <button onclick="window.open('https://example.com', '_blank')" style="background: #667eea; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Daha Fazla Bilgi</button>
</div>
HTML;
    }
}
