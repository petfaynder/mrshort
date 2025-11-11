<!DOCTYPE html>
<html>
<head>
    <title>Reklam Adımı {{ $stepNumber }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: sans-serif;
        }
        .ad-container {
            margin-bottom: 20px;
            text-align: center;
        }
        .countdown {
            font-size: 24px;
            margin-top: 20px;
        }
        /* Pop-up stilini ekleyelim */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .popup-content {
            background: white;
            padding: 20px;
            position: relative;
        }
        .popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Reklam Adımı {{ $stepNumber }}</h1>

    <div class="ad-container">
        {{-- Adıma ait reklamları göster --}}
        @foreach ($stepAds as $stepAd)
            @if ($stepAd->ad_type === \App\Enums\AdType::ThirdParty)
                {{-- Üçüncü parti reklam kodu --}}
                <div data-step-ad-id="{{ $stepAd->id }}">
                    <h3>Üçüncü Parti Reklam</h3>
                    {!! $stepAd->ad_code !!}
                </div>
            @elseif ($stepAd->ad_type === \App\Enums\AdType::Banner)
                {{-- Banner reklam --}}
                <div data-step-ad-id="{{ $stepAd->id }}">
                    <h3>Banner Reklam</h3>
                    {{-- TODO: Banner boyutlarına göre stil eklenecek --}}
                    {!! $stepAd->ad_code !!}
                </div>
            @elseif ($stepAd->ad_type === \App\Enums\AdType::Interstitial)
                 {{-- Interstitial reklam --}}
                 <div data-step-ad-id="{{ $stepAd->id }}">
                     <h3>Interstitial Reklam</h3>
                     {!! $stepAd->ad_code !!}
                 </div>
            @endif
            {{-- TODO: Diğer reklam türleri (Popup hariç) buraya eklenecek --}}
        @endforeach
    </div>

    {{-- Pop-up reklamı göster (varsa) --}}
    @if ($popupAd)
        <div id="popup-ad" class="popup-overlay">
            <div class="popup-content">
                @if ($popupAd->ad_type === \App\Enums\AdType::Popup && isset($popupAd->ad_settings['target_url']))
                    {{-- Kullanıcı pop-up (URL yönlendirme) --}}
                    <p>Pop-up Reklam: Yönlendiriliyorsunuz...</p>
                    <script>
                        // Kullanıcı pop-up'ı için tıklama takibi
                        document.getElementById('popup-ad').addEventListener('click', function(event) {
                             // Pop-up içeriğine tıklanmadığından emin olun
                            if (event.target.id === 'popup-ad' || event.target.classList.contains('popup-content')) {
                                return;
                            }
                            fetch(`/ads/{{ $popupAd->id }}/click`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({})
                            }).catch(error => console.error('Pop-up tıklama takibi sırasında hata oluştu:', error));
                        });
                        window.open("{{ $popupAd->ad_settings['target_url'] }}", "_blank");
                    </script>
                @elseif ($popupAd->ad_type === \App\Enums\AdType::ThirdParty)
                     {{-- Üçüncü parti pop-up kodu --}}
                     <div data-step-ad-id="{{ $popupAd->id }}">
                         {!! $popupAd->ad_code !!}
                     </div>
                @endif
                {{-- Pop-up kapatma butonu --}}
                <button onclick="document.getElementById('popup-ad').style.display = 'none';" class="popup-close">X</button>
            </div>
        </div>
    @endif


    <div class="countdown" id="countdown">Yönlendiriliyor...</div>

    <script>
        const waitTime = {{ $adStep->wait_time ?? 5 }}; // Adım bekleme süresi
        let timeLeft = waitTime;
        const countdownElement = document.getElementById('countdown');
        const originalUrl = "{{ $originalUrl }}";
        const currentStep = {{ $stepNumber }};
        const totalSteps = {{ $campaign->adSteps->count() }};
        const linkCode = "{{ $link->code }}";
        const campaignId = {{ $campaign->id }};

        function updateCountdown() {
            if (timeLeft > 0) {
                countdownElement.innerText = `Yönlendiriliyor... ${timeLeft} saniye`;
                timeLeft--;
            } else {
                clearInterval(timer);
                // Bir sonraki adıma veya orijinal linke yönlendir
                if (currentStep < totalSteps) {
                    window.location.href = `/link/${linkCode}/step/${currentStep + 1}?campaignId=${campaignId}`;
                } else {
                    window.location.href = originalUrl;
                }
            }
        }

        const timer = setInterval(updateCountdown, 1000);
        updateCountdown(); // İlk sayımı hemen yap

        // Reklam tıklamalarını takip etmek için JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Ad container içindeki reklamlara tıklama dinleyicisi ekle
            const adContainers = document.querySelectorAll('.ad-container [data-step-ad-id]');

            adContainers.forEach(adElement => {
                adElement.addEventListener('click', function(event) {
                    const stepAdId = adElement.dataset.stepAdId;
                     if (stepAdId) {
                        fetch(`/ads/${stepAdId}/click`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({})
                        }).catch(error => console.error('Reklam tıklama takibi sırasında hata oluştu:', error));
                    }
                });
            });

            // Pop-up reklam için tıklama dinleyicisi ekle (eğer varsa ve üçüncü parti ise)
            const popupAdElement = document.querySelector('#popup-ad [data-step-ad-id]');
            if (popupAdElement) {
                 popupAdElement.addEventListener('click', function(event) {
                    const stepAdId = popupAdElement.dataset.stepAdId;
                     if (stepAdId) {
                        fetch(`/ads/${stepAdId}/click`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({})
                        }).catch(error => console.error('Pop-up reklam tıklama takibi sırasında hata oluştu:', error));
                    }
                });
            }
        });
    </script>
</body>
</html>