<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Link Transition Page</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Inline AdBlock Detection - Runs before Vite bundle --}}
    <script>
        window.adblockDetected = false;
        window.adblockCallbacks = [];
        window.onAdblockDetected = function(cb) { 
            window.adblockCallbacks.push(cb);
            if (window.adblockDetected) cb();
        };
        window.triggerAdblockDetected = function(method) {
            if (window.adblockDetected) return;
            window.adblockDetected = true;
            console.log('[AdBlock] Detected via:', method);
            window.adblockCallbacks.forEach(function(cb) { try { cb(); } catch(e) {} });
        };
    </script>
    {{-- Load bait script - adblockers block this --}}
    <script src="/ads.js" onerror="window.triggerAdblockDetected('ads_js_blocked')"></script>
    <script src="/pagead/js/adsbygoogle.js" onerror="window.triggerAdblockDetected('adsense_blocked')"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    
    @php
        $captchaService = app(\App\Services\CaptchaService::class);
        $captchaEnabled = setting('captcha_enabled', false);
        $captchaOnShortlink = setting('captcha_on_shortlink', false);
        $captchaVerified = session('captcha_verified_' . $link->code);
        $isFirstStep = ($stepNumber ?? 1) === 1;
        $showCaptcha = $isFirstStep && $captchaEnabled && $captchaOnShortlink && !$captchaVerified;
    @endphp
    
    @if($showCaptcha)
        {!! $captchaService->getScript() !!}
    @endif
    
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --bg-light: #F9FAFB;
            --bg-dark: #111827;
            --card-light: #FFFFFF;
            --card-dark: #1F2937;
            --text-light: #1F2937;
            --text-dark: #F9FAFB;
            --text-secondary-light: #6B7280;
            --text-secondary-dark: #9CA3AF;
            --border-light: #E5E7EB;
            --border-dark: #374151;
        }
        
        body { font-family: 'Poppins', sans-serif; }
        
        /* Timer ring animation */
        .timer-ring {
            transform: rotate(-90deg);
            transition: stroke-dashoffset 1s linear;
        }
        
        /* Animated CTA button */
        .btn-glow {
            position: relative;
            overflow: hidden;
        }
        .btn-glow::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            animation: btn-shine 3s infinite;
        }
        .btn-glow:disabled::before { display: none; }
        
        @keyframes btn-shine {
            0% { left: -50%; }
            100% { left: 150%; }
        }
        
        /* Progress bar */
        .progress-bar {
            transition: width 1s linear;
        }
        
        /* Ad hover effect */
        .ad-block {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .ad-block:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* AdBlock warning */
        .adblock-warning {
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        /* Skyscraper container */
        .skyscraper-container {
            position: sticky;
            top: 1rem;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

{{-- AdBlock Warning Modal (hidden by default) --}}
<div id="adblock-modal" class="fixed inset-0 z-50 items-center justify-center bg-black/70 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-8 max-w-md mx-4 text-center adblock-warning">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-icons text-red-500 text-3xl">block</span>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">AdBlock Detected!</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            Please disable your ad blocker to continue. Our service relies on advertisements to remain free.
        </p>
        <button onclick="location.reload()" class="bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-indigo-700 transition-colors">
            I've Disabled AdBlock – Reload
        </button>
    </div>
</div>

<div class="min-h-screen">
    {{-- Header with Timer Badge --}}
    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-indigo-600">{{ config('app.name') }}</h1>
            
            {{-- Timer Badge --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-full">
                    <div class="relative w-8 h-8">
                        <svg class="w-full h-full" viewBox="0 0 36 36">
                            <circle class="text-gray-300 dark:text-gray-600" cx="18" cy="18" fill="transparent" r="15" stroke="currentColor" stroke-width="3"></circle>
                            <circle class="text-indigo-600 timer-ring" cx="18" cy="18" fill="transparent" id="timer-progress-small" r="15" stroke="currentColor" stroke-dasharray="94.2" stroke-dashoffset="0" stroke-linecap="round" stroke-width="3"></circle>
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-xs font-bold" id="timer-countdown-small">{{ $adStep->wait_time ?? 10 }}</span>
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">seconds</span>
                </div>
            </div>
        </div>
        
        {{-- Progress Bar --}}
        <div class="h-1 bg-gray-200 dark:bg-gray-700">
            <div id="progress-bar" class="h-full bg-indigo-600 progress-bar" style="width: 100%"></div>
        </div>
    </header>

    {{-- Main 3-Column Layout --}}
    <main class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Left Skyscraper Ad (Desktop) --}}
            <div class="hidden lg:block lg:col-span-2">
                <div class="skyscraper-container">
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-2">
                        <p class="text-xs text-gray-500 text-center mb-2">Advertisement</p>
                        @php $leftSkyscraper = $adsData->shift(); @endphp
                        <div class="w-[160px] h-[600px] mx-auto flex items-center justify-center overflow-hidden ad-block" data-ad-id="{{ $leftSkyscraper->id ?? '' }}" data-ad-type="{{ $leftSkyscraper ? ($leftSkyscraper->ad_type->value ?? '') : '' }}">
                            @if($leftSkyscraper)
                                @include('partials.ad_display', ['ad' => $leftSkyscraper])
                            @else
                                <div class="bg-gray-100 dark:bg-gray-700 w-full h-full flex items-center justify-center rounded">
                                    <span class="text-gray-400 text-xs text-center">160x600<br>Skyscraper</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Center Content --}}
            <div class="lg:col-span-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 md:p-8">
                    
                    {{-- Top Banner Ad --}}
                    <div class="mb-6">
                        <p class="text-xs text-gray-500 text-center mb-2">Advertisement</p>
                        @php $topAd = $adsData->shift(); @endphp
                        <div class="w-full max-w-[728px] h-[90px] mx-auto flex items-center justify-center rounded-lg overflow-hidden ad-block" data-ad-id="{{ $topAd->id ?? '' }}" data-ad-type="{{ $topAd ? ($topAd->ad_type->value ?? '') : '' }}">
                            @if($topAd)
                                @include('partials.ad_display', ['ad' => $topAd])
                            @else
                                <div class="bg-gray-100 dark:bg-gray-700 w-full h-full flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">728x90 Leaderboard</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Timer and Message --}}
                    <div class="text-center mb-6">
                        <div class="relative w-24 h-24 mx-auto mb-4">
                            <svg class="w-full h-full" viewBox="0 0 120 120">
                                <circle class="text-gray-200 dark:text-gray-700" cx="60" cy="60" fill="transparent" r="54" stroke="currentColor" stroke-width="10"></circle>
                                <circle class="text-indigo-600 timer-ring" cx="60" cy="60" fill="transparent" id="timer-progress" r="54" stroke="currentColor" stroke-dasharray="339.292" stroke-dashoffset="0" stroke-linecap="round" stroke-width="10"></circle>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center text-3xl font-bold text-indigo-600" id="timer-countdown">{{ $adStep->wait_time ?? 10 }}</div>
                        </div>
                        <p class="text-lg font-medium text-gray-900 dark:text-white mb-1">Your link is almost ready!</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Please wait while we prepare your destination...</p>
                    </div>
                    
                    {{-- Middle Banner Ad --}}
                    <div class="mb-6">
                        @php $middleAd = $adsData->shift(); @endphp
                        <div class="w-[300px] h-[250px] mx-auto flex items-center justify-center rounded-lg overflow-hidden ad-block" data-ad-id="{{ $middleAd->id ?? '' }}" data-ad-type="{{ $middleAd ? ($middleAd->ad_type->value ?? '') : '' }}">
                            @if($middleAd)
                                @include('partials.ad_display', ['ad' => $middleAd])
                            @else
                                <div class="bg-gray-100 dark:bg-gray-700 w-full h-full flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">300x250 Banner</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Captcha --}}
                    @if($showCaptcha)
                    <div class="mb-6 flex justify-center">
                        {!! $captchaService->getWidget() !!}
                    </div>
                    @endif
                    
                    {{-- Get Link Button (Animated) --}}
                    <button id="get-link-btn" disabled class="w-full bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 font-semibold py-4 px-6 rounded-xl cursor-not-allowed transition-all duration-300 flex items-center justify-center gap-2 text-lg">
                        <span class="material-icons animate-spin">hourglass_empty</span>
                        Please wait...
                    </button>
                    
                    {{-- Bottom Banner Ad --}}
                    <div class="mt-6">
                        @php $bottomAd = $adsData->shift(); @endphp
                        <div class="w-[300px] h-[250px] mx-auto flex items-center justify-center rounded-lg overflow-hidden ad-block" data-ad-id="{{ $bottomAd->id ?? '' }}" data-ad-type="{{ $bottomAd ? ($bottomAd->ad_type->value ?? '') : '' }}">
                            @if($bottomAd)
                                @include('partials.ad_display', ['ad' => $bottomAd])
                            @else
                                <div class="bg-gray-100 dark:bg-gray-700 w-full h-full flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">300x250 Banner</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Info Section --}}
                @include('partials.info_section')
            </div>
            
            {{-- Right Skyscraper Ad (Desktop) --}}
            <div class="hidden lg:block lg:col-span-2">
                <div class="skyscraper-container">
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-2">
                        <p class="text-xs text-gray-500 text-center mb-2">Advertisement</p>
                        @php $rightSkyscraper = $adsData->shift(); @endphp
                        <div class="w-[160px] h-[600px] mx-auto flex items-center justify-center overflow-hidden ad-block" data-ad-id="{{ $rightSkyscraper->id ?? '' }}" data-ad-type="{{ $rightSkyscraper ? ($rightSkyscraper->ad_type->value ?? '') : '' }}">
                            @if($rightSkyscraper)
                                @include('partials.ad_display', ['ad' => $rightSkyscraper])
                            @else
                                <div class="bg-gray-100 dark:bg-gray-700 w-full h-full flex items-center justify-center rounded">
                                    <span class="text-gray-400 text-xs text-center">160x600<br>Skyscraper</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-8">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-gray-500">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <div class="flex space-x-6 mt-2 md:mt-0">
                <a class="text-sm text-gray-500 hover:text-indigo-600" href="{{ route('privacy.policy') }}">Privacy Policy</a>
                <a class="text-sm text-gray-500 hover:text-indigo-600" href="{{ route('terms.of.service') }}">Terms of Use</a>
                <a class="text-sm text-gray-400 hover:text-red-500 flex items-center gap-1" href="{{ route('dmca.complaint', ['linkCode' => $link->code]) }}" target="_blank" rel="noopener">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Report DMCA
                </a>
            </div>
        </div>
    </footer>
</div>

{{-- Pop-under Ad Script --}}
@if(isset($userPopupAd) && $userPopupAd)
<script>
    (function() {
        let popunderOpened = false;
        const popunderUrl = "{{ $userPopupAd['ad_data']['url'] ?? '' }}";
        const popunderId = {{ $userPopupAd['id'] ?? 0 }};
        
        if (popunderUrl) {
            document.addEventListener('click', function openPopunder(e) {
                if (popunderOpened) return;
                const target = e.target;
                const isInteractiveElement = target.closest('button, a, input, select, textarea');
                if (isInteractiveElement) return;
                
                popunderOpened = true;
                
                if (popunderId) {
                    fetch(`/ads/track-click/popup/${popunderId}?userPopupCampaignId=${popunderId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                        body: JSON.stringify({})
                    }).catch(error => console.error('Popunder tracking error:', error));
                }
                
                const popunderWindow = window.open(popunderUrl, '_blank', 'noopener,noreferrer');
                if (popunderWindow) {
                    window.focus();
                    setTimeout(() => window.focus(), 100);
                }
                document.removeEventListener('click', openPopunder);
            }, { once: false });
        }
    })();
</script>
@endif

<script>
    // Timer variables
    const countdownElement = document.getElementById('timer-countdown');
    const countdownSmall = document.getElementById('timer-countdown-small');
    const progressCircle = document.getElementById('timer-progress');
    const progressCircleSmall = document.getElementById('timer-progress-small');
    const progressBar = document.getElementById('progress-bar');
    const getLinkBtn = document.getElementById('get-link-btn');
    
    const totalTime = {{ $adStep->wait_time ?? 10 }};
    let timeLeft = totalTime;
    let timerPaused = false;
    
    // Circle progress calculations
    const radius = 54;
    const circumference = 2 * Math.PI * radius;
    const radiusSmall = 15;
    const circumferenceSmall = 2 * Math.PI * radiusSmall;
    
    progressCircle.style.strokeDasharray = `${circumference} ${circumference}`;
    progressCircleSmall.style.strokeDasharray = `${circumferenceSmall} ${circumferenceSmall}`;

    function setProgress(percent) {
        const offset = circumference - (percent / 100) * circumference;
        const offsetSmall = circumferenceSmall - (percent / 100) * circumferenceSmall;
        progressCircle.style.strokeDashoffset = offset;
        progressCircleSmall.style.strokeDashoffset = offsetSmall;
        progressBar.style.width = `${percent}%`;
    }

    function proceedToNextStep() {
        const requiresCaptcha = {{ $showCaptcha ? 'true' : 'false' }};
        if (requiresCaptcha) {
            let captchaResponse = null;
            const turnstileWidget = document.querySelector('[name="cf-turnstile-response"]');
            const recaptchaWidget = document.querySelector('[name="g-recaptcha-response"]');
            const hcaptchaWidget = document.querySelector('[name="h-captcha-response"]');
            
            if (turnstileWidget) captchaResponse = turnstileWidget.value;
            if (recaptchaWidget) captchaResponse = recaptchaWidget.value;
            if (hcaptchaWidget) captchaResponse = hcaptchaWidget.value;
            
            if (!captchaResponse) {
                alert('Please complete the captcha verification first.');
                return;
            }
            
            fetch('/go/{{ $link->code }}/captcha', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    'cf-turnstile-response': turnstileWidget?.value,
                    'g-recaptcha-response': recaptchaWidget?.value,
                    'h-captcha-response': hcaptchaWidget?.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) navigateToNext();
                else alert(data.message || 'Captcha verification failed. Please try again.');
            })
            .catch(error => {
                console.error('Captcha verification error:', error);
                alert('Captcha verification failed. Please try again.');
            });
            return;
        }
        navigateToNext();
    }
    
    function navigateToNext() {
        const currentStep = {{ $stepNumber }};
        const totalSteps = {{ $campaignOrTemplate->campaignTemplateSteps->count() }};
        const linkCode = "{{ $link->code }}";
        const campaignTemplateId = {{ $campaignOrTemplate->id ?? 'null' }};

        let nextUrl;
        if (currentStep < totalSteps) {
            nextUrl = `/link/${linkCode}/step/${currentStep + 1}?campaignTemplateId=${campaignTemplateId}`;
        } else {
            nextUrl = `/link/${linkCode}/complete`;
        }
        window.location.href = nextUrl;
    }

    setProgress(100);

    const interval = setInterval(() => {
        if (timerPaused) return;
        
        timeLeft--;
        countdownElement.textContent = timeLeft;
        countdownSmall.textContent = timeLeft;
        setProgress(100 * (timeLeft / totalTime));

        if (timeLeft <= 0) {
            clearInterval(interval);
            countdownElement.innerHTML = `<span class="material-icons text-3xl">check</span>`;
            countdownSmall.innerHTML = `<span class="material-icons text-sm">check</span>`;
            
            // Activate button with glow effect
            getLinkBtn.disabled = false;
            getLinkBtn.classList.remove('bg-gray-300', 'dark:bg-gray-600', 'text-gray-500', 'dark:text-gray-400', 'cursor-not-allowed');
            getLinkBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'text-white', 'btn-glow');
            getLinkBtn.innerHTML = '<span>Get Your Link</span><span class="material-icons ml-2">arrow_forward</span>';
            getLinkBtn.onclick = proceedToNextStep;
        }
    }, 1000);

    // AdBlock detection - using inline detection API
    document.addEventListener('DOMContentLoaded', function() {
        function showAdblockModal() {
            timerPaused = true;
            document.getElementById('adblock-modal').classList.remove('hidden');
            document.getElementById('adblock-modal').classList.add('flex');
        }
        
        // Check inline detection first
        if (window.adblockDetected) {
            showAdblockModal();
        } else {
            // Register for future detection
            window.onAdblockDetected(showAdblockModal);
        }
        
        // Also check for Vite module detection
        if (typeof AdBlockDetector !== 'undefined') {
            AdBlockDetector.onDetected(showAdblockModal);
        }
        
        // Final check - if bait scripts didn't load, variables won't be set
        setTimeout(function() {
            if (!window.adsLoaded || !window.googleAdsLoaded) {
                window.triggerAdblockDetected('variable_check');
            }
        }, 1500);
        
        // Ad click tracking
        document.querySelectorAll('.ad-block').forEach(adElement => {
            adElement.addEventListener('click', function() {
                const adId = adElement.dataset.adId;
                const adType = adElement.dataset.adType;
                if (!adId || !adType) return;
                fetch(`/ads/track-click/${adType}/${adId}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                    body: JSON.stringify({})
                }).catch(error => console.error('Ad click tracking error:', error));
            });
        });
    });
</script>

{{-- Third Party Ad Codes from Site Settings --}}
@if(!empty($thirdPartyAdCodes) && is_array($thirdPartyAdCodes))
    @foreach($thirdPartyAdCodes as $adCode)
        @if(!empty($adCode['code']))
<!-- Third Party Ad: {{ $adCode['name'] ?? 'Unknown' }} - Step {{ $stepNumber }} -->
@php
    $snippet = trim($adCode['code']);
    $isHtml = preg_match('/^\s*<(script|iframe|div|span|ins|a|img|style)/i', $snippet);
@endphp

@if($isHtml)
{!! $snippet !!}
@else
<script>
    (function() {
        try {
            {!! $snippet !!}
        } catch(e) {
            console.error('Third party ad error ({{ $adCode['name'] ?? 'unknown' }}):', e);
        }
    })();
</script>
@endif
        @endif
    @endforeach
@endif

</body>
</html>
