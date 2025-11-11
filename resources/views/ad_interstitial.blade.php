<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Link Transition Page</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#4F46E5",
                        "background-light": "#F9FAFB",
                        "background-dark": "#111827",
                        "card-light": "#FFFFFF",
                        "card-dark": "#1F2937",
                        "text-light": "#1F2937",
                        "text-dark": "#F9FAFB",
                        "text-secondary-light": "#6B7280",
                        "text-secondary-dark": "#9CA3AF",
                        "border-light": "#E5E7EB",
                        "border-dark": "#374151"
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        lg: "1rem",
                        xl: "1.5rem"
                    },
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        .timer-ring {
            transform: rotate(-90deg);
            transition: stroke-dashoffset 1s linear;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
<div class="min-h-screen flex flex-col items-center justify-center p-4">
    <header class="w-full max-w-6xl mx-auto flex justify-between items-center py-6 px-4">
        <h1 class="text-3xl font-bold text-primary">{{ config('app.name') }}</h1>
        <div class="flex items-center space-x-4">
            <button class="hidden bg-gray-200 dark:bg-gray-700 text-text-light dark:text-text-dark font-semibold py-2 px-4 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300" id="skip-ad-btn">
                Skip Ad
            </button>
        </div>
    </header>

    <main class="flex-grow flex flex-col items-center justify-center w-full">
        <div class="bg-card-light dark:bg-card-dark p-8 rounded-xl shadow-lg w-full max-w-lg text-center">
            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-4">Advertisement</p>
            
            @php $topAd = $adsData->shift(); @endphp
            <div class="w-[300px] h-[250px] mx-auto mb-6 flex items-center justify-center rounded-lg ad-block" data-ad-id="{{ $topAd->id ?? '' }}" data-ad-type="{{ $topAd->ad_type->value ?? '' }}">
                @if($topAd)
                    @include('partials.ad_display', ['ad' => $topAd])
                @else
                    <div class="bg-gray-200 dark:bg-gray-700 w-full h-full flex items-center justify-center">
                        <span class="text-text-secondary-light dark:text-text-secondary-dark">300x250 Ad</span>
                    </div>
                @endif
            </div>

            <p class="text-lg font-medium text-text-light dark:text-text-dark mb-2">Your link will be ready in a few seconds.</p>
            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-8">Discover cool stuff while you wait!</p>

            <div class="relative w-32 h-32 mx-auto mb-8">
                <svg class="w-full h-full" viewBox="0 0 120 120">
                    <circle class="text-border-light dark:text-border-dark" cx="60" cy="60" fill="transparent" r="54" stroke="currentColor" stroke-width="12"></circle>
                    <circle class="text-primary timer-ring" cx="60" cy="60" fill="transparent" id="timer-progress" r="54" stroke="currentColor" stroke-dasharray="339.292" stroke-dashoffset="0" stroke-linecap="round" stroke-width="12"></circle>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center text-4xl font-bold text-primary" id="timer-countdown">{{ $adStep->wait_time ?? 10 }}</div>
            </div>

            @php $bottomAd = $adsData->shift(); @endphp
            <div class="w-[300px] h-[250px] mx-auto mb-8 flex items-center justify-center rounded-lg ad-block" data-ad-id="{{ $bottomAd->id ?? '' }}" data-ad-type="{{ $bottomAd->ad_type->value ?? '' }}">
                 @if($bottomAd)
                    @include('partials.ad_display', ['ad' => $bottomAd])
                @else
                    <div class="bg-gray-200 dark:bg-gray-700 w-full h-full flex items-center justify-center">
                        <span class="text-text-secondary-light dark:text-text-secondary-dark">300x250 Ad</span>
                    </div>
                @endif
            </div>

            <button class="w-full bg-primary/20 dark:bg-primary/20 text-primary font-semibold py-3 px-6 rounded-lg cursor-not-allowed transition-all duration-300" disabled id="get-link-btn">
                Please wait...
            </button>
        </div>
        
        @include('partials.info_section')

    </main>

    <footer class="w-full max-w-6xl mx-auto py-6 px-4">
        <div class="flex flex-col md:flex-row justify-between items-center border-t border-border-light dark:border-border-dark pt-6">
            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a class="text-sm text-text-secondary-light dark:text-text-secondary-dark hover:text-primary" href="#">Privacy Policy</a>
                <a class="text-sm text-text-secondary-light dark:text-text-secondary-dark hover:text-primary" href="#">Terms of Use</a>
            </div>
        </div>
    </footer>
</div>

<script>
    const countdownElement = document.getElementById('timer-countdown');
    const progressCircle = document.getElementById('timer-progress');
    const getLinkBtn = document.getElementById('get-link-btn');
    const skipAdBtn = document.getElementById('skip-ad-btn');
    
    const totalTime = {{ $adStep->wait_time ?? 10 }};
    let timeLeft = totalTime;
    
    const radius = progressCircle.r.baseVal.value;
    const circumference = 2 * Math.PI * radius;
    
    progressCircle.style.strokeDasharray = `${circumference} ${circumference}`;
    progressCircle.style.strokeDashoffset = circumference;

    function setProgress(percent) {
        const offset = circumference - (percent / 100) * circumference;
        progressCircle.style.strokeDashoffset = offset;
    }

    function proceedToNextStep() {
        let nextUrl;
        const currentStep = {{ $stepNumber }};
        const totalSteps = {{ $campaignOrTemplate->campaignTemplateSteps->count() }};
        const linkCode = "{{ $link->code }}";
        const campaignTemplateId = {{ $campaignOrTemplate->id ?? 'null' }};

        if (currentStep < totalSteps) {
            const params = new URLSearchParams({ campaignTemplateId });
            nextUrl = `/link/${linkCode}/step/${currentStep + 1}?${params.toString()}`;
        } else {
            nextUrl = "{{ $originalUrl }}";
        }
        window.location.href = nextUrl;
    }

    setProgress(100 * (timeLeft / totalTime));

    const interval = setInterval(() => {
        timeLeft--;
        countdownElement.textContent = timeLeft;
        setProgress(100 * (timeLeft / totalTime));
        
        if (timeLeft <= 5) {
            skipAdBtn.classList.remove('hidden');
        }

        if (timeLeft <= 0) {
            clearInterval(interval);
            countdownElement.innerHTML = `<span class="material-icons text-4xl">check</span>`;
            getLinkBtn.disabled = false;
            getLinkBtn.classList.remove('bg-primary/20', 'dark:bg-primary/20', 'text-primary', 'cursor-not-allowed');
            getLinkBtn.classList.add('bg-primary', 'text-white', 'hover:bg-primary/90');
            getLinkBtn.innerHTML = 'Get Link <span class="material-icons text-base ml-1">arrow_forward</span>';
            getLinkBtn.onclick = proceedToNextStep;
            skipAdBtn.classList.add('hidden');
        }
    }, 1000);

    skipAdBtn.onclick = () => {
        clearInterval(interval);
        proceedToNextStep();
    };

    // Ad click tracking
    document.addEventListener('DOMContentLoaded', function() {
        const trackAdClick = (adId, adType) => {
            if (!adId || !adType) return;
            fetch(`/ads/track-click/${adType}/${adId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            }).catch(error => console.error('Ad click tracking error:', error));
        };

        document.querySelectorAll('.ad-block').forEach(adElement => {
            adElement.addEventListener('click', function(event) {
                const adId = adElement.dataset.adId;
                const adType = adElement.dataset.adType;
                trackAdClick(adId, adType);
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find all iframes within .html-content or .third-party-content and remove them
        document.querySelectorAll('.html-content iframe, .third-party-content iframe').forEach(iframe => {
            iframe.parentNode.removeChild(iframe);
        });
    });
</script>

</body>
</html>
