<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Link Transition Page</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    
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
    
    <script src="/ads.js" onerror="window.triggerAdblockDetected('ads_js_blocked')"></script>
    <script src="/pagead/js/adsbygoogle.js" onerror="window.triggerAdblockDetected('adsense_blocked')"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    
    <?php
        $captchaService = app(\App\Services\CaptchaService::class);
        $captchaEnabled = setting('captcha_enabled', false);
        $captchaOnShortlink = setting('captcha_on_shortlink', false);
        $captchaVerified = session('captcha_verified_' . $link->code);
        $isFirstStep = ($stepNumber ?? 1) === 1;
        $showCaptcha = $isFirstStep && $captchaEnabled && $captchaOnShortlink && !$captchaVerified;
    ?>
    
    <?php if($showCaptcha): ?>
        <?php echo $captchaService->getScript(); ?>

    <?php endif; ?>
    
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
    
    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-indigo-600"><?php echo e(config('app.name')); ?></h1>
            
            
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-full">
                    <div class="relative w-8 h-8">
                        <svg class="w-full h-full" viewBox="0 0 36 36">
                            <circle class="text-gray-300 dark:text-gray-600" cx="18" cy="18" fill="transparent" r="15" stroke="currentColor" stroke-width="3"></circle>
                            <circle class="text-indigo-600 timer-ring" cx="18" cy="18" fill="transparent" id="timer-progress-small" r="15" stroke="currentColor" stroke-dasharray="94.2" stroke-dashoffset="0" stroke-linecap="round" stroke-width="3"></circle>
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-xs font-bold" id="timer-countdown-small"><?php echo e($adStep->wait_time ?? 10); ?></span>
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">seconds</span>
                </div>
            </div>
        </div>
        
        
        <div class="h-1 bg-gray-200 dark:bg-gray-700">
            <div id="progress-bar" class="h-full bg-indigo-600 progress-bar" style="width: 100%"></div>
        </div>
    </header>

    
    <main class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            
            <div class="hidden lg:block lg:col-span-2">
                <div class="skyscraper-container">
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-2">
                        <p class="text-xs text-gray-500 text-center mb-2">Advertisement</p>
                        <?php $leftSkyscraper = $adsData->shift(); ?>
                        <div class="w-[160px] h-[600px] mx-auto flex items-center justify-center ad-block" data-ad-id="<?php echo e($leftSkyscraper->id ?? ''); ?>" data-ad-type="<?php echo e($leftSkyscraper ? ($leftSkyscraper->ad_type->value ?? '') : ''); ?>">
                            <?php if($leftSkyscraper): ?>
                                <?php echo $__env->make('partials.ad_display', ['ad' => $leftSkyscraper], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php else: ?>
                                <div class="bg-gray-100 dark:bg-gray-700 w-full h-full flex items-center justify-center rounded">
                                    <span class="text-gray-400 text-xs text-center">160x600<br>Skyscraper</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="lg:col-span-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 md:p-8">
                    
                    
                    <div class="mb-6">
                        <p class="text-xs text-gray-500 text-center mb-2">Advertisement</p>
                        <?php $topAd = $adsData->shift(); ?>
                        <div class="w-full max-w-[728px] h-[90px] mx-auto flex items-center justify-center rounded-lg overflow-hidden ad-block" data-ad-id="<?php echo e($topAd->id ?? ''); ?>" data-ad-type="<?php echo e($topAd ? ($topAd->ad_type->value ?? '') : ''); ?>">
                            <?php if($topAd): ?>
                                <?php echo $__env->make('partials.ad_display', ['ad' => $topAd], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php else: ?>
                                <div class="bg-gray-100 dark:bg-gray-700 w-full h-full flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">728x90 Leaderboard</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    
                    <div class="text-center mb-6">
                        <div class="relative w-24 h-24 mx-auto mb-4">
                            <svg class="w-full h-full" viewBox="0 0 120 120">
                                <circle class="text-gray-200 dark:text-gray-700" cx="60" cy="60" fill="transparent" r="54" stroke="currentColor" stroke-width="10"></circle>
                                <circle class="text-indigo-600 timer-ring" cx="60" cy="60" fill="transparent" id="timer-progress" r="54" stroke="currentColor" stroke-dasharray="339.292" stroke-dashoffset="0" stroke-linecap="round" stroke-width="10"></circle>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center text-3xl font-bold text-indigo-600" id="timer-countdown"><?php echo e($adStep->wait_time ?? 10); ?></div>
                        </div>
                        <p class="text-lg font-medium text-gray-900 dark:text-white mb-1">Your link is almost ready!</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Please wait while we prepare your destination...</p>
                    </div>
                    
                    
                    <div class="mb-6">
                        <?php $middleAd = $adsData->shift(); ?>
                        <div class="w-[300px] h-[250px] mx-auto flex items-center justify-center rounded-lg overflow-hidden ad-block" data-ad-id="<?php echo e($middleAd->id ?? ''); ?>" data-ad-type="<?php echo e($middleAd ? ($middleAd->ad_type->value ?? '') : ''); ?>">
                            <?php if($middleAd): ?>
                                <?php echo $__env->make('partials.ad_display', ['ad' => $middleAd], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php else: ?>
                                <div class="bg-gray-100 dark:bg-gray-700 w-full h-full flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">300x250 Banner</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    
                    <?php if($showCaptcha): ?>
                    <div class="mb-6 flex justify-center">
                        <?php echo $captchaService->getWidget(); ?>

                    </div>
                    <?php endif; ?>
                    
                    
                    <button id="get-link-btn" disabled class="w-full bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 font-semibold py-4 px-6 rounded-xl cursor-not-allowed transition-all duration-300 flex items-center justify-center gap-2 text-lg">
                        <span class="material-icons animate-spin">hourglass_empty</span>
                        Please wait...
                    </button>
                    
                    
                    <div class="mt-6">
                        <?php $bottomAd = $adsData->shift(); ?>
                        <div class="w-[300px] h-[250px] mx-auto flex items-center justify-center rounded-lg overflow-hidden ad-block" data-ad-id="<?php echo e($bottomAd->id ?? ''); ?>" data-ad-type="<?php echo e($bottomAd ? ($bottomAd->ad_type->value ?? '') : ''); ?>">
                            <?php if($bottomAd): ?>
                                <?php echo $__env->make('partials.ad_display', ['ad' => $bottomAd], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php else: ?>
                                <div class="bg-gray-100 dark:bg-gray-700 w-full h-full flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">300x250 Banner</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                
                <?php echo $__env->make('partials.info_section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            
            
            <div class="hidden lg:block lg:col-span-2">
                <div class="skyscraper-container">
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-2">
                        <p class="text-xs text-gray-500 text-center mb-2">Advertisement</p>
                        <?php $rightSkyscraper = $adsData->shift(); ?>
                        <div class="w-[160px] h-[600px] mx-auto flex items-center justify-center ad-block" data-ad-id="<?php echo e($rightSkyscraper->id ?? ''); ?>" data-ad-type="<?php echo e($rightSkyscraper ? ($rightSkyscraper->ad_type->value ?? '') : ''); ?>">
                            <?php if($rightSkyscraper): ?>
                                <?php echo $__env->make('partials.ad_display', ['ad' => $rightSkyscraper], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php else: ?>
                                <div class="bg-gray-100 dark:bg-gray-700 w-full h-full flex items-center justify-center rounded">
                                    <span class="text-gray-400 text-xs text-center">160x600<br>Skyscraper</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-8">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-gray-500">© <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.</p>
            <div class="flex space-x-6 mt-2 md:mt-0">
                <a class="text-sm text-gray-500 hover:text-indigo-600" href="#">Privacy Policy</a>
                <a class="text-sm text-gray-500 hover:text-indigo-600" href="#">Terms of Use</a>
            </div>
        </div>
    </footer>
</div>


<?php if(isset($userPopupAd) && $userPopupAd): ?>
<script>
    (function() {
        let popunderOpened = false;
        const popunderUrl = "<?php echo e($userPopupAd['ad_data']['url'] ?? ''); ?>";
        const popunderId = <?php echo e($userPopupAd['id'] ?? 0); ?>;
        
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
                        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Content-Type': 'application/json' },
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
<?php endif; ?>

<script>
    // Timer variables
    const countdownElement = document.getElementById('timer-countdown');
    const countdownSmall = document.getElementById('timer-countdown-small');
    const progressCircle = document.getElementById('timer-progress');
    const progressCircleSmall = document.getElementById('timer-progress-small');
    const progressBar = document.getElementById('progress-bar');
    const getLinkBtn = document.getElementById('get-link-btn');
    
    const totalTime = <?php echo e($adStep->wait_time ?? 10); ?>;
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
        const requiresCaptcha = <?php echo e($showCaptcha ? 'true' : 'false'); ?>;
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
            
            fetch('/go/<?php echo e($link->code); ?>/captcha', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
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
        const currentStep = <?php echo e($stepNumber); ?>;
        const totalSteps = <?php echo e($campaignOrTemplate->campaignTemplateSteps->count()); ?>;
        const linkCode = "<?php echo e($link->code); ?>";
        const campaignTemplateId = <?php echo e($campaignOrTemplate->id ?? 'null'); ?>;

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
                    headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Content-Type': 'application/json' },
                    body: JSON.stringify({})
                }).catch(error => console.error('Ad click tracking error:', error));
            });
        });
    });
</script>

</body>
</html>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/ad_banner_page.blade.php ENDPATH**/ ?>