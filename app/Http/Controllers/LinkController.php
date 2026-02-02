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
use App\Services\VisitorDetectionService;
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
        
        // Validate URL against banned words, disallowed domains
        $validator = app(\App\Services\LinkValidationService::class);
        $errors = $validator->validate($request->input('original_url'));
        
        if (!empty($errors)) {
            return redirect('/')->withErrors(['original_url' => $errors[0]]);
        }
        
        $safetyErrors = $validator->checkUrlSafety($request->input('original_url'));
        if (!empty($safetyErrors)) {
            return redirect('/')->withErrors(['original_url' => $safetyErrors[0]]);
        }

        $codeLength = setting('link_code_length', 6);
        $code = Str::random($codeLength);

        $link = Link::create([
            'user_id' => auth()->id(),
            'original_url' => $request->input('original_url'),
            'code' => $code,
        ]);

        return redirect('/')->with('success', 'Link shortened: ' . $link->shortLink());
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);
        
        // Validate URL
        $validator = app(\App\Services\LinkValidationService::class);
        $errors = $validator->validate($request->input('url'));
        
        if (!empty($errors)) {
            return response()->json(['success' => false, 'message' => $errors[0]], 400);
        }
        
        $safetyErrors = $validator->checkUrlSafety($request->input('url'));
        if (!empty($safetyErrors)) {
            return response()->json(['success' => false, 'message' => $safetyErrors[0]], 400);
        }

        $codeLength = setting('link_code_length', 6);
        $code = Str::random($codeLength);

        $link = Link::create([
            'user_id' => auth()->id(),
            'original_url' => $request->input('url'),
            'code' => $code,
        ]);

        return response()->json([
            'success' => true,
            'short_link' => route('shortlink.redirect', $link->code),
            'code' => $link->code,
            'original_url' => $link->original_url
        ]);
    }

    /**
     * Show captcha verification page for shortlink
     * Now redirects to main shortlink route which has captcha overlay in interstitial
     */
    public function showCaptcha(string $code)
    {
        $link = Link::where('code', $code)->first();
        
        if (!$link) {
            abort(404);
        }
        
        // Redirect to the main shortlink route - captcha overlay is in interstitial page
        return redirect()->route('shortlink.redirect', $code);
    }
    
    /**
     * Verify captcha and redirect to original link
     */
    public function verifyCaptcha(Request $request, string $code)
    {
        $link = Link::where('code', $code)->first();
        
        if (!$link) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Link not found'], 404);
            }
            abort(404);
        }
        
        // Get redirect URL (from interstitial page)
        $redirectTo = $request->input('redirect_to') ?: route('shortlink.redirect', $code);
        
        // Verify captcha
        $captchaService = app(\App\Services\CaptchaService::class);
        $tokenField = $captchaService->getTokenFieldName();
        $token = $request->input($tokenField);
        
        if (!$captchaService->verify($token)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Captcha verification failed. Please try again.']);
            }
            return redirect($redirectTo)
                ->with('captcha_error', 'Captcha verification failed. Please try again.');
        }
        
        // Store verification in session
        session()->put('captcha_verified_' . $code, true);
        
        // Return JSON for AJAX or redirect for form
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect($redirectTo);
    }

    public function redirect(Request $request, Agent $agent, string $code) // Inject Agent
    {
        $link = Link::where('code', $code)->first();

        if ($link) {
            // Note: Captcha is now shown as overlay in interstitial page (ad_interstitial.blade.php)
            // No redirect needed here - the interstitial view handles captcha display
            
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
            \Log::info('LinkController: Detected client IP address.', ['ip' => $clientIp]);


            $databasePath = storage_path('app/private/geoip/GeoLite2-Country.mmdb'); // Corrected path
            \Log::info('Checking GeoLite2-Country.mmdb.', ['path' => $databasePath]);

            if (file_exists($databasePath)) {
                try {
                    $reader = new Reader($databasePath);
                    \Log::info('Starting GeoIP lookup.', ['ip' => $clientIp]);
                    $record = $reader->country($clientIp);
                    $countryIsoCode = $record->country->isoCode;
                    \Log::info('GeoIP country code detected.', ['ip' => $clientIp, 'country_iso_code' => $countryIsoCode]);

                    // Find Country model by ISO code
                    $countryModel = \App\Models\Country::where('iso_code', $countryIsoCode)->first();
                    if ($countryModel) {
                        $countryId = $countryModel->id;
                        \Log::info('Country found in database.', ['country_id' => $countryId, 'country_name' => $countryModel->name]);
                        
                        // Get country-specific CPM rate (publisher rate)
                        $countryCpmRate = \App\Models\CpmRate::where('country_id', $countryId)->first();
                        if ($countryCpmRate && $countryCpmRate->publisher_rate > 0) {
                            $cpmRate = (float) $countryCpmRate->publisher_rate;
                            \Log::info('Using country-specific CPM rate.', ['country_id' => $countryId, 'cpm_rate' => $cpmRate]);
                        }
                    } else {
                        \Log::warning('Country not found in database.', ['iso_code' => $countryIsoCode]);
                    }
                } catch (\Exception $e) {
                    \Log::error('GeoIP query failed.', ['ip' => $request->ip(), 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                }
            } else {
                \Log::warning('GeoLite2-Country.mmdb file not found.', ['path' => $databasePath]);
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

            // Check paid views per day limit from settings (SYSTEM-WIDE IP check)
            // This ensures each unique IP can only generate paid views X times per day across ALL links
            $paidViewsPerDay = (int) setting('paid_views_per_day', 1);
            $todayClicksFromIp = LinkClick::where('ip_address', $clientIp)
                ->whereDate('created_at', today())
                ->count();
            
            $shouldPay = $todayClicksFromIp < $paidViewsPerDay;
            
            // Check if referrer is blocked
            $blockedReferrers = array_filter(array_map('trim', explode(',', setting('block_referrer_domains', ''))));
            $referrer = $request->header('referer');
            if ($referrer && !empty($blockedReferrers)) {
                $referrerHost = parse_url($referrer, PHP_URL_HOST);
                foreach ($blockedReferrers as $blocked) {
                    if ($referrerHost && stripos($referrerHost, $blocked) !== false) {
                        $shouldPay = false;
                        break;
                    }
                }
            }
            
            // Don't pay for bot traffic
            if ($isBot) {
                $shouldPay = false;
            }

            // Use default CPM from settings if not set
            if ($cpmRate == 0.0000) {
                $cpmRate = (float) setting('default_cpm_rate', 0.001);
            }
            
            // Fallback: ensure minimum CPM rate if setting is 0+
            if ($cpmRate <= 0) {
                $cpmRate = 0.001; // Minimum fallback CPM rate
                \Log::warning('CPM rate was 0 or negative, using fallback minimum.', ['cpmRate' => $cpmRate]);
            }

            // Store click data in session - will be recorded after all ad steps complete
            $pendingClickData = [
                'link_id' => $link->id,
                'ip_address' => $clientIp,
                'country_id' => $countryId,
                'country_iso_code' => $countryIsoCode,
                'cpm_rate' => $shouldPay ? $cpmRate : 0,
                'city' => $city,
                'referrer' => $request->header('referer') ?? 'Direct Access',
                'device_type' => $deviceType,
                'os' => $os,
                'browser' => $browser,
                'is_bot' => $isBot,
                'recent_click_count' => $recentClickCount + 1,
                'should_pay' => $shouldPay,
                'user_id' => $link->user_id,
                'timestamp' => now()->toDateTimeString(),
            ];
            
            session()->put('pending_click_' . $code, $pendingClickData);

            // Prioritize the campaign template associated with the link.
            $selectedCampaignTemplate = $link->campaignTemplate;

            // Detect visitor info for targeting
            $visitorInfo = VisitorDetectionService::detect($request);
            \Log::info('Visitor detected.', $visitorInfo);

            // If no campaign is assigned or the assigned one is inactive, find a random active template matching targeting
            if (!$selectedCampaignTemplate || !$selectedCampaignTemplate->is_active) {
                // Get all active templates and filter by targeting rules
                $activeTemplates = CampaignTemplate::where('is_active', true)->get();
                
                $matchingTemplates = $activeTemplates->filter(function($template) use ($visitorInfo) {
                    return VisitorDetectionService::matchesTargeting($visitorInfo, $template);
                });
                
                // Select random from matching templates, or fallback to any active if none match
                if ($matchingTemplates->isNotEmpty()) {
                    $selectedCampaignTemplate = $matchingTemplates->random();
                    \Log::info('Selected targeting-matched template.', ['template_id' => $selectedCampaignTemplate->id]);
                } else {
                    // Fallback: no targeting match, use any active template
                    $selectedCampaignTemplate = $activeTemplates->random();
                    \Log::info('No targeting match, using random template.', ['template_id' => $selectedCampaignTemplate?->id]);
                }
            } else {
                // Verify assigned template matches targeting
                if (!VisitorDetectionService::matchesTargeting($visitorInfo, $selectedCampaignTemplate)) {
                    \Log::info('Assigned template does not match targeting, finding alternative.');
                    $matchingTemplates = CampaignTemplate::where('is_active', true)
                        ->get()
                        ->filter(fn($t) => VisitorDetectionService::matchesTargeting($visitorInfo, $t));
                    
                    $selectedCampaignTemplate = $matchingTemplates->isNotEmpty() 
                        ? $matchingTemplates->random() 
                        : $selectedCampaignTemplate; // Keep original if no alternatives
                }
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
     * Record click after all ad steps are completed and redirect to original URL.
     * This ensures clicks are only counted when visitors complete the entire ad flow.
     */
    public function recordClickAndRedirect(Request $request, Link $link)
    {
        $code = $link->code;
        $pendingClickData = session()->get('pending_click_' . $code);
        
        // Security: Only record if session has valid pending click data
        if (!$pendingClickData || $pendingClickData['link_id'] !== $link->id) {
            \Log::warning('recordClickAndRedirect: No valid pending click data found.', ['code' => $code]);
            return Redirect::to($link->original_url);
        }
        
        // Prevent duplicate recordings
        session()->forget('pending_click_' . $code);
        
        // === CLICK REDUCTION SYSTEM ===
        $isSkipped = false;
        $originalCpmRate = $pendingClickData['cpm_rate'];
        $originalShouldPay = $pendingClickData['should_pay'];
        
        if ($originalShouldPay && (bool) setting('click_reduction_enabled', false)) {
            $userId = $pendingClickData['user_id'];
            $guaranteeCount = (int) setting('click_guarantee_count', 5);
            
            // Count user's total paid clicks (not skipped)
            $userTotalPaidClicks = LinkClick::whereHas('link', fn($q) => $q->where('user_id', $userId))
                ->where('cpm_rate', '>', 0)
                ->where('is_skipped', false)
                ->count();
            
            // First X paid clicks are guaranteed
            if ($userTotalPaidClicks >= $guaranteeCount) {
                $ratio = (float) setting('click_record_ratio', 100) / 100;
                $randomValue = mt_rand(1, 10000) / 10000;
                $isSkipped = $randomValue > $ratio;
            }
        }
        
        // If skipped, set cpm_rate to 0 and mark as skipped
        $recordCpmRate = $isSkipped ? 0 : $originalCpmRate;
        $recordShouldPay = $isSkipped ? false : $originalShouldPay;
        // === END CLICK REDUCTION ===
        
        // Record the click to database
        $linkClick = $link->clicks()->create([
            'ip_address' => $pendingClickData['ip_address'],
            'country_id' => $pendingClickData['country_id'],
            'cpm_rate' => $recordCpmRate,
            'country' => $pendingClickData['country_iso_code'],
            'city' => $pendingClickData['city'],
            'referrer' => $pendingClickData['referrer'],
            'device_type' => $pendingClickData['device_type'],
            'os' => $pendingClickData['os'],
            'browser' => $pendingClickData['browser'],
            'is_bot' => $pendingClickData['is_bot'],
            'recent_click_count' => $pendingClickData['recent_click_count'],
            'is_skipped' => $isSkipped,
        ]);
        
        \Log::info('Click recorded after ad flow completion.', [
            'link_id' => $link->id,
            'click_id' => $linkClick->id,
            'should_pay' => $recordShouldPay,
            'is_skipped' => $isSkipped
        ]);
        
        // Update user earnings ONLY if this is a paid view and NOT skipped
        if ($pendingClickData['user_id'] && $recordShouldPay && !$isSkipped) {
            $user = \App\Models\User::find($pendingClickData['user_id']);
            if ($user) {
                $cpmRate = $pendingClickData['cpm_rate'];
                $earning = $cpmRate / 1000; // Earning for a single click

                // Apply VIP Bonus
                if ($user->vipLevel && $user->vipLevel->cpm_bonus_percent > 0) {
                    $vipBonus = $earning * ($user->vipLevel->cpm_bonus_percent / 100);
                    $earning += $vipBonus;
                }

                // Apply Telegram Traffic Bonus (+10%)
                if ($user->hasTelegramBonus()) {
                    $telegramBonusService = app(\App\Services\TelegramBonusService::class);
                    $telegramBonus = $earning * ($telegramBonusService->getCpmBonusMultiplier() - 1);
                    $earning += $telegramBonus;
                    \Log::info('Telegram bonus applied.', [
                        'user_id' => $user->id,
                        'telegram_bonus' => $telegramBonus,
                    ]);
                }

                $user->link_earnings += $earning;
                $user->earnings = $user->link_earnings + $user->referral_earnings;
                
                // Track monthly earnings for VIP
                $user->increment('monthly_earnings', $earning);
                
                $user->save();

                // Process referral earnings if enabled
                if ($user->referred_by_user_id && setting('enable_referrals', true)) {
                    $referrerUser = \App\Models\User::find($user->referred_by_user_id);
                    if ($referrerUser) {
                        $referralCommissionRate = (float) setting('referral_commission_rate', 15) / 100;
                        $commissionAmount = $earning * $referralCommissionRate;

                        $referrerUser->referral_earnings += $commissionAmount;
                        $referrerUser->earnings = $referrerUser->link_earnings + $referrerUser->referral_earnings;
                        $referrerUser->save();
                    }
                }

                // Telegram Traffic Verification - Increment counter and check
                if ($user->telegram_bonus_enabled) {
                    $telegramBonusService = app(\App\Services\TelegramBonusService::class);
                    $newClickCount = $telegramBonusService->incrementVerificationCounter($user);
                    
                    if ($telegramBonusService->needsVerification($user->fresh())) {
                        // Dispatch verification job
                        \App\Jobs\VerifyTelegramTrafficJob::dispatch($user->fresh());
                        \Log::info('Telegram verification job dispatched.', [
                            'user_id' => $user->id,
                            'click_count' => $newClickCount,
                        ]);
                    }
                }
                
                \Log::info('User earnings updated after ad completion.', [
                    'user_id' => $user->id,
                    'earning' => $earning
                ]);
            }
        }

        
        // Redirect to the original destination URL
        return Redirect::to($link->original_url);
    }

    /**
     * Reklam adımlarını gösterir.
     */
    public function showAdStep(Request $request, Link $link, int $stepNumber)
    {
        $campaignTemplateId = $request->query('campaignTemplateId');

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

        // Reklam verilerini al (banner/interstitial) - popup hariç
        $adsData = $adStepToDisplay->campaignTemplateAds->filter(function($ad) {
            return $ad->ad_type !== \App\Enums\AdType::Popup;
        });

        // === POPUP SELECTION LOGIC ===
        // Priority: JS popup + URL popup can coexist. Only URL vs URL needs weighted selection.
        $popupsToShow = [];
        
        // 1. Check for JS popup code (from campaign template ads)
        $jsPopupCode = null;
        if ($adStepToDisplay->show_popup || $adStepToDisplay->show_linked_popup) {
            $jsPopupAd = $adStepToDisplay->campaignTemplateAds
                ->where('ad_type', \App\Enums\AdType::Popup)
                ->filter(fn($ad) => isset($ad->ad_data['js_code']) && !empty($ad->ad_data['js_code']))
                ->first();
            
            if ($jsPopupAd) {
                $jsPopupCode = $jsPopupAd->ad_data['js_code'];
                $popupsToShow[] = [
                    'type' => 'js',
                    'code' => $jsPopupCode,
                    'id' => $jsPopupAd->id,
                ];
                \Log::info('JS popup code found.', ['popup_id' => $jsPopupAd->id]);
            }
        }
        
        // 2. Check for URL popups (Admin and User)
        $adminPopupUrl = null;
        $userPopupUrl = null;
        $userPopupCampaignId = null;
        
        // Admin URL popup from campaign template
        if ($adStepToDisplay->show_popup || $adStepToDisplay->show_linked_popup) {
            $adminPopupAd = $adStepToDisplay->campaignTemplateAds
                ->where('ad_type', \App\Enums\AdType::Popup)
                ->filter(fn($ad) => isset($ad->ad_data['url']) && !empty($ad->ad_data['url']) && empty($ad->ad_data['js_code']))
                ->first();
            
            if ($adminPopupAd) {
                $adminPopupUrl = $adminPopupAd->ad_data['url'];
            }
        }
        
        // User URL popup from approved AdCampaign
        if (setting('popup_user_campaigns_enabled', true)) {
            // Detect visitor info for targeting
            $popupVisitorInfo = VisitorDetectionService::detect($request);
            
            // Check if link owner has Telegram bonus enabled
            $linkOwnerHasTelegramBonus = $link->user?->telegram_bonus_enabled ?? false;
            
            // Get all matching campaigns
            $matchingCampaigns = AdCampaign::where('campaign_type', 'user')
                ->where('approval_status', 'approved')
                ->where('is_active', true)
                ->get()
                ->filter(function($campaign) use ($popupVisitorInfo) {
                    $rules = $campaign->targeting_rules ?? [];
                    
                    // Check country targeting
                    if (!empty($rules['countries'])) {
                        if (!in_array($popupVisitorInfo['country'], $rules['countries'])) {
                            return false;
                        }
                    }
                    
                    // Check device targeting
                    if (!empty($rules['devices'])) {
                        if (!in_array($popupVisitorInfo['device'], $rules['devices'])) {
                            return false;
                        }
                    }
                    
                    // Check OS targeting
                    if (!empty($rules['os'])) {
                        if (!in_array($popupVisitorInfo['os'], $rules['os'])) {
                            return false;
                        }
                    }
                    
                    return true;
                });
            
            // Priority-based campaign selection
            $userPopupCampaign = null;
            
            if ($linkOwnerHasTelegramBonus) {
                // Telegram-bonus users: prioritize Telegram promotion campaigns
                $userPopupCampaign = $matchingCampaigns
                    ->where('is_telegram_promotion', true)
                    ->first();
                
                // Fallback to any campaign if no Telegram campaign found
                if (!$userPopupCampaign) {
                    $userPopupCampaign = $matchingCampaigns->first();
                }
                
                if ($userPopupCampaign?->is_telegram_promotion) {
                    \Log::info('Telegram campaign prioritized for Telegram-bonus user.', [
                        'campaign_id' => $userPopupCampaign->id,
                        'link_user_id' => $link->user_id,
                    ]);
                }
            } else {
                // Non-Telegram users: prefer non-Telegram campaigns first
                $userPopupCampaign = $matchingCampaigns
                    ->where('is_telegram_promotion', false)
                    ->first();
                
                // Fallback to any campaign (including Telegram) if none found
                if (!$userPopupCampaign) {
                    $userPopupCampaign = $matchingCampaigns->first();
                }
            }
            
            if ($userPopupCampaign && isset($userPopupCampaign->targeting_rules['url'])) {
                $userPopupUrl = $userPopupCampaign->targeting_rules['url'];
                $userPopupCampaignId = $userPopupCampaign->id;
                \Log::info('User popup campaign found.', ['campaign_id' => $userPopupCampaign->id]);
            }
        }
        
        // 3. Select URL popup (weighted selection if both exist)
        $selectedUrlPopup = null;
        if ($adminPopupUrl && $userPopupUrl) {
            // Both exist - weighted random selection
            $adminWeight = (int) setting('popup_admin_weight', 70);
            $selectedUrlPopup = (rand(1, 100) <= $adminWeight)
                ? ['url' => $adminPopupUrl, 'source' => 'admin', 'campaign_id' => null]
                : ['url' => $userPopupUrl, 'source' => 'user', 'campaign_id' => $userPopupCampaignId];
            \Log::info('URL popup selected by weight.', ['source' => $selectedUrlPopup['source'], 'admin_weight' => $adminWeight]);
        } elseif ($adminPopupUrl) {
            $selectedUrlPopup = ['url' => $adminPopupUrl, 'source' => 'admin', 'campaign_id' => null];
        } elseif ($userPopupUrl) {
            $selectedUrlPopup = ['url' => $userPopupUrl, 'source' => 'user', 'campaign_id' => $userPopupCampaignId];
        }
        
        if ($selectedUrlPopup) {
            $popupsToShow[] = [
                'type' => 'url',
                'url' => $selectedUrlPopup['url'],
                'source' => $selectedUrlPopup['source'],
                'campaign_id' => $selectedUrlPopup['campaign_id'],
            ];
        }
        
        // Legacy variable for backward compatibility
        $userPopupAd = null;
        foreach ($popupsToShow as $popup) {
            if ($popup['type'] === 'url') {
                $userPopupAd = [
                    'id' => $popup['campaign_id'] ?? 0,
                    'ad_type' => \App\Enums\AdType::Popup,
                    'ad_data' => [
                        'url' => $popup['url'],
                        'source' => $popup['source'] ?? 'admin',
                    ],
                ];
                break;
            }
        }

        // Adım türüne göre ilgili view'i yükle
        $viewName = 'ad_step_placeholder'; // Varsayılan placeholder view


        if ($adStepToDisplay->step_type === \App\Enums\StepType::Interstitial) {
            $viewName = 'ad_interstitial';
        } elseif ($adStepToDisplay->step_type === \App\Enums\StepType::BannerPage) {
            $viewName = 'ad_banner_page';
        }

        // Get third-party ad codes for this step from Site Settings
        $thirdPartyAdCodes = setting("thirdparty_ads_step_{$stepNumber}", []);
        // Decode if stored as JSON string
        if (is_string($thirdPartyAdCodes)) {
            $thirdPartyAdCodes = json_decode($thirdPartyAdCodes, true) ?? [];
        }

        return view($viewName, [
            'link' => $link,
            'campaignOrTemplate' => $campaignTemplate,
            'adStep' => $adStepToDisplay,
            'stepNumber' => $stepNumber,
            'originalUrl' => $link->original_url,
            'adsData' => $adsData,
            'userPopupAd' => $userPopupAd,
            'popupsToShow' => $popupsToShow, // New: array of all popups to show
            'thirdPartyAdCodes' => $thirdPartyAdCodes, // Third-party JS codes for this step
            'isFromTemplate' => true,
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
}
