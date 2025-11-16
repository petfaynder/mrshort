<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\AdSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\LinkClick;
use GeoIp2\Database\Reader;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use App\Models\AdCampaign; // Added
use App\Models\CampaignTemplate; // Added
use App\Models\CampaignTemplateStep; // Added
use App\Models\CampaignTemplateAd; // Added
use App\Enums\CampaignType;
use App\Enums\FrequencyCapUnit; // Add this import
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon; // Add this import

class LinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
        ]);

        $code = Str::random(6); // Generate a random short code

        $link = Link::create([
            'user_id' => auth()->id(), // Assuming users are authenticated
            'original_url' => $request->input('original_url'),
            'code' => $code,
        ]);

        return redirect('/')->with('success', 'Bağlantı kısaltıldı: ' . $link->shortLink());
    }

    public function redirect(Request $request, Agent $agent, string $code) // Inject Agent
    {
        $link = Link::where('code', $code)->first();

        if ($link) {
            $countryId = null;
            $countryIsoCode = null;
            $city = null;
            $cpmRate = 0.0000; // Initialize $cpmRate
            $deviceType = 'Unknown';
            $os = 'Unknown';
            $browser = 'Unknown';
            $isBot = false;
            $recentClickCount = 0; // Initialize $recentClickCount

            // Get GeoIP information
            $clientIp = $this->getClientIp($request);
            \Log::info('LinkController: Tespit edilen istemci IP adresi.', ['ip' => $clientIp]);


            $databasePath = Storage::path('private/geoip/GeoLite2-Country.mmdb'); // Corrected path to the Country database
            \Log::info('GeoLite2-Country.mmdb kontrol ediliyor.', ['path' => $databasePath]);

            if (file_exists($databasePath)) {
                try {
                    $reader = new Reader($databasePath);
                    \Log::info('GeoIP lookup başlatılıyor.', ['ip' => $clientIp]);
                    $record = $reader->country($clientIp);
                    $countryIsoCode = $record->country->isoCode;
                    \Log::info('GeoIP ülke kodu tespit edildi.', ['ip' => $clientIp, 'country_iso_code' => $countryIsoCode]);

                    // Ülke ISO koduna göre Country modelini bul
                    $countryModel = \App\Models\Country::where('iso_code', $countryIsoCode)->first();
                    if ($countryModel) {
                        $countryId = $countryModel->id;
                        \Log::info('Ülke veritabanında bulundu.', ['country_id' => $countryId, 'country_name' => $countryModel->name]);
                    } else {
                        \Log::warning('Ülke veritabanında bulunamadı.', ['iso_code' => $countryIsoCode]);
                    }
                } catch (\Exception $e) {
                    \Log::error('GeoIP sorgusu başarısız oldu.', ['ip' => $request->ip(), 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                }
            } else {
                \Log::warning('GeoLite2-Country.mmdb dosyası bulunamadı.', ['path' => $databasePath]);
            }

            // Determine device, OS, browser, and bot status using Jenssegers/Agent
            if ($agent->isDesktop()) {
                $deviceType = 'Desktop';
            } elseif ($agent->isTablet()) {
                $deviceType = 'Tablet';
            } elseif ($agent->isMobile()) {
                $deviceType = 'Mobile';
            } else {
                $deviceType = 'Other';
            }

            $os = $agent->platform();
            $browser = $agent->browser();
            $isBot = $agent->isRobot();

            // TODO: Implement logic to get recent click count for frequency capping.
            // For now, it's initialized to 0 and incremented by 1 in the create method.
            // This needs to be properly implemented based on user/link click history.
            // $recentClickCount = LinkClick::where('link_id', $link->id)
            //     ->where('ip_address', $clientIp)
            //     ->where('created_at', '>=', Carbon::now()->subHours(24)) // Example: last 24 hours
            //     ->count();

            // Eğer hala bir oran bulunamazsa, genel varsayılan bir oran kullan
            if ($cpmRate == 0.0000) {
                $cpmRate = 0.001; // Genel varsayılan CPM oranı (örneğin 1000 gösterim başına 1$)
            }

            // Save detailed click information
            $linkClick = $link->clicks()->create([
                'ip_address' => $clientIp,
                'country_id' => $countryId,
                'cpm_rate' => $cpmRate, // Hesaplanan CPM oranını kaydet
                'country' => $countryIsoCode, // Eski 'country' sütununa ISO kodu kaydedelim
                'city' => $city, // Will be null for Country database
                'referrer' => $request->header('referer') ?? 'Doğrudan Erişim',
                'device_type' => $deviceType,
                'os' => $os,
                'browser' => $browser,
                'is_bot' => $isBot,
                'recent_click_count' => $recentClickCount + 1, // Mevcut tıklamayı da say
            ]);

            // Kullanıcının kazancını güncelle
            if ($link->user) {
                $user = $link->user;
                // CPM oranına göre kazancı hesapla (örneğin, 1000 gösterim başına $cpmRate)
                $earning = $cpmRate / 1000; // Tek bir tıklama için kazanç

                $user->link_earnings += $earning;
                $user->earnings = $user->link_earnings + $user->referral_earnings;
                $user->save();

                // Referans kazancını işle
                if ($user && $user->referred_by_user_id) {
                    $referrerUser = \App\Models\User::find($user->referred_by_user_id);
                    if ($referrerUser) {
                        $referralCommissionRate = 0.15; // %15 referans komisyon oranı (ayarlardan alınabilir)
                        $commissionAmount = $earning * $referralCommissionRate;

                        $referrerUser->referral_earnings += $commissionAmount;
                        $referrerUser->earnings = $referrerUser->link_earnings + $referrerUser->referral_earnings;
                        $referrerUser->save();
                    } // Closing if ($referrerUser)
                } // Closing if ($user && $user->referred_by_user_id)
            } // Closing if ($link->user)

            // Prioritize the campaign template associated with the link.
            $selectedCampaignTemplate = $link->campaignTemplate;

            // If no campaign is assigned or the assigned one is inactive, find an active default.
            if (!$selectedCampaignTemplate || !$selectedCampaignTemplate->is_active) {
                $selectedCampaignTemplate = CampaignTemplate::where('is_active', true)->first();
            }

            if ($selectedCampaignTemplate) {
                $routeParams = [
                    'link' => $link->code,
                    'stepNumber' => 1, // Always start at step 1
                    'campaignTemplateId' => $selectedCampaignTemplate->id,
                ];
                \Log::info('Redirecting to ad step.', ['routeParams' => $routeParams]);
                return redirect()->route('link.ad_step', $routeParams);
            } else {
                // If no active campaigns are found at all, redirect to the original URL.
                return Redirect::to($link->original_url);
            }
        }

        // If link not found, redirect to homepage with error
        return redirect('/')->with('error', 'Geçersiz kısa kod.');
    }

    /**
     * Reklam adımlarını gösterir.
     */
    public function showAdStep(Request $request, Link $link, int $stepNumber)
    {
        $campaignTemplateId = $request->query('campaignTemplateId');
        $userPopupCampaignId = $request->query('userPopupCampaignId'); // Rastgele seçilen pop-up kampanya ID'si

        $adStepToDisplay = null;
        $campaignTemplate = null;

        if ($campaignTemplateId) {
            $campaignTemplate = CampaignTemplate::with('campaignTemplateSteps.campaignTemplateAds')->find($campaignTemplateId);
            if ($campaignTemplate) {
                $adStepToDisplay = $campaignTemplate->campaignTemplateSteps()->where('step_number', $stepNumber)->first();
            }
        }

        if (!$link || !$campaignTemplate || !$adStepToDisplay) {
            return redirect('/')->with('error', 'Geçersiz link, kampanya veya adım.');
        }

        // Zamanlama kontrolü (CampaignTemplate üzerinden)
        $now = Carbon::now();
        if ($campaignTemplate->start_date && $now->isBefore($campaignTemplate->start_date)) {
            return Redirect::to($link->original_url);
        }
        if ($campaignTemplate->end_date && $now->isAfter($campaignTemplate->end_date)) {
            return Redirect::to($link->original_url);
        }
        // Günlük tıklama limiti kontrolü (CampaignTemplate üzerinden)
        // CampaignTemplate'in kendi total_clicks veya daily_click_limit sütunları yoksa bu kontrol kaldırılmalı
        // veya Link modelindeki tıklamalar üzerinden yapılmalı.
        // Şimdilik bu kontrolü kaldırıyoruz, daha sonra CampaignTemplate modeline eklenebilir.
        // if ($campaignTemplate->daily_click_limit && $campaignTemplate->total_clicks >= $campaignTemplate->daily_click_limit) {
        //     return Redirect::to($link->original_url);
        // }

        // Adım gösterim sayısını artır (CampaignTemplate adımları için)
        // CampaignTemplateStep modelinde impressions sütunu yoksa bu satır kaldırılmalı
        // veya CampaignTemplateStep modeline impressions sütunu eklenmeli.
        // Şimdilik CampaignTemplateStep'in impressions'ı olmadığını varsayıyoruz.
        // if ($adStepToDisplay) {
        //     $adStepToDisplay->increment('impressions');
        //     $campaignTemplate->increment('total_impressions');
        // }

        // Reklam verilerini al (banner/interstitial)
        $adsData = $adStepToDisplay->campaignTemplateAds; // Artık her zaman CampaignTemplateAds

        // Kullanıcı tarafından oluşturulan pop-up reklamı al (rastgele seçilen kampanya üzerinden)
        $userPopupAd = null;
        if ($adStepToDisplay->show_linked_popup && $userPopupCampaignId) {
            $randomPopupCampaign = AdCampaign::find($userPopupCampaignId);
            if ($randomPopupCampaign && isset($randomPopupCampaign->targeting_rules['popup_url'])) {
                $userPopupAd = [
                    'id' => $randomPopupCampaign->id,
                    'ad_type' => \App\Enums\AdType::Popup,
                    'ad_data' => [
                        'title' => $randomPopupCampaign->targeting_rules['popup_title'] ?? $randomPopupCampaign->name,
                        'content' => $randomPopupCampaign->targeting_rules['popup_content'] ?? 'Bu bir kullanıcı pop-up reklamıdır.',
                        'url' => $randomPopupCampaign->targeting_rules['popup_url'],
                    ],
                ];
            }
        }

        // Adım türüne göre ilgili view'i yükle
        $viewName = 'ad_step_placeholder'; // Varsayılan placeholder view

        if ($adStepToDisplay->step_type === \App\Enums\StepType::Interstitial) {
            $viewName = 'ad_interstitial';
        } elseif ($adStepToDisplay->step_type === \App\Enums\StepType::BannerPage) {
            $viewName = 'ad_banner_page';
        }

        return view($viewName, [
            'link' => $link,
            'campaignOrTemplate' => $campaignTemplate, // Kampanya şablonunu gönder
            'adStep' => $adStepToDisplay,
            'stepNumber' => $stepNumber,
            'originalUrl' => $link->original_url,
            'adsData' => $adsData, // Adım için reklam verileri (banner/interstitial)
            'userPopupAd' => $userPopupAd, // Kullanıcı tarafından oluşturulan pop-up reklam (varsa)
            'isFromTemplate' => true, // Her zaman şablondan geldiği için true
        ]);
    }

    /**
     * Reklam tıklamalarını takip eder.
     */
    public function trackAdClick(Request $request, string $adType, int $adId)
    {
        if ($adType === 'banner' || $adType === 'popup' || $adType === 'html' || $adType === 'third_party') {
            // Check if it's a CampaignTemplateAd
            $ad = CampaignTemplateAd::find($adId);
            if ($ad) {
                // CampaignTemplateAd'lerin tıklamalarını doğrudan takip etmiyoruz,
                // çünkü bunlar sadece şablon tanımları. Gerçek tıklamalar AdCampaign'ler üzerinden olur.
                // Ancak, eğer şablon reklamlarının da kendi tıklama sayacı olması isteniyorsa,
                // CampaignTemplateAd modeline 'clicks' sütunu eklenip burada artırılabilir.
                // Şimdilik sadece logluyoruz veya başka bir metrik güncelliyoruz.
                \Log::info('CampaignTemplateAd clicked (template only, no direct click count).', ['ad_id' => $adId, 'ad_type' => $adType]);

                // Eğer tıklanan bir pop-up kampanyası ise, ilgili AdCampaign'in tıklama sayısını artır.
                // Bu kısım, LinkController'daki pop-up seçimi mantığına göre güncellenmelidir.
                // Şimdilik, eğer ad_type 'popup' ise ve bu bir AdCampaign'den geliyorsa,
                // AdCampaign'in total_clicks'ini artırabiliriz.
                if ($adType === 'popup' && $request->has('userPopupCampaignId')) {
                    $popupCampaign = AdCampaign::find($request->query('userPopupCampaignId'));
                    if ($popupCampaign) {
                        $popupCampaign->increment('total_clicks');
                        \Log::info('Popup AdCampaign clicked.', ['campaign_id' => $popupCampaign->id]);
                    }
                }

                return response()->json(['success' => true, 'message' => 'Template ad click tracked (no direct increment).']);
            }
        }
    }


    /**
     * Get the client's IP address from various headers.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function getClientIp(Request $request): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_REAL_IP',        // Nginx proxy, etc.
            'HTTP_X_FORWARDED_FOR',  // Standard proxy
            'REMOTE_ADDR'            // Fallback
        ];

        foreach ($headers as $header) {
            if ($request->server($header)) {
                // X-Forwarded-For can contain a comma-separated list of IPs.
                // The client's IP is typically the first one.
                $ip = $request->server($header);
                $ip = trim(explode(',', $ip)[0]);

                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    \Log::info('LinkController: IP adresi bulundu.', ['header' => $header, 'ip' => $ip]);
                    return $ip;
                }
            }
        }

        // Fallback to Laravel's default ip() method
        $fallbackIp = $request->ip();
        \Log::warning('LinkController: Güvenilir IP başlığı bulunamadı, request->ip() kullanılıyor.', ['ip' => $fallbackIp]);
        return $fallbackIp;
    }

    public function debugIp(Request $request)
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ];

        $ipData = [];
        foreach ($headers as $header) {
            $ipData[$header] = $request->server($header) ?? 'Not Set';
        }

        $ipData['determined_ip'] = $this->getClientIp($request);
        $ipData['laravel_request_ip'] = $request->ip();

        return response()->json($ipData);
    }
}
