<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ config('app.name') }} — Content Loading</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])

<script>
    window.adblockDetected = false;
    window.adblockCallbacks = [];
    window.onAdblockDetected = function(cb) { window.adblockCallbacks.push(cb); if (window.adblockDetected) cb(); };
    window.triggerAdblockDetected = function(method) {
        if (window.adblockDetected) return;
        window.adblockDetected = true;
        window.adblockCallbacks.forEach(function(cb) { try { cb(); } catch(e) {} });
    };
</script>
<script src="/ads.js" onerror="window.triggerAdblockDetected('ads_js_blocked')"></script>
<script src="/pagead/js/adsbygoogle.js" onerror="window.triggerAdblockDetected('adsense_blocked')"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet"/>

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
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --primary:#DC2626;--accent:#EF4444;--accent2:#F87171;
  --bg:#0A0000;--header-bg:rgba(10,0,0,0.96);--card-bg:rgba(16,4,4,0.9);
  --border:rgba(220,38,38,0.2);--text:#F5F0F0;--muted:rgba(245,240,240,0.62);
  --subtle-text:rgba(245,240,240,0.65);--ad-bg:rgba(8,0,0,0.8);
  --card-bg-info:rgba(14,4,4,0.78);--border-color:rgba(220,38,38,0.18);
}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;min-height:100vh;}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(220,38,38,.12) 0%,transparent 70%);pointer-events:none;z-index:0;}
body::after{content:'';position:fixed;bottom:0;left:0;right:0;height:35%;background:radial-gradient(ellipse 80% 60% at 50% 100%,rgba(220,38,38,.07) 0%,transparent 70%);pointer-events:none;z-index:0;}
.page-wrap{position:relative;z-index:1;min-height:100vh;display:flex;flex-direction:column;}

/* AdBlock Modal */
#adblock-modal{position:fixed;inset:0;z-index:200;align-items:center;justify-content:center;background:rgba(0,0,0,.92);backdrop-filter:blur(8px);display:none;}
#adblock-modal.show{display:flex;}
.adblock-inner{background:#100404;border:1px solid rgba(220,38,38,.3);border-radius:8px;padding:2.5rem 2rem;max-width:440px;width:calc(100% - 2rem);text-align:center;animation:shake .5s ease-in-out;}
@keyframes shake{0%,100%{transform:translateX(0);}25%{transform:translateX(-6px);}75%{transform:translateX(6px);}}
.adblock-icon{width:64px;height:64px;background:rgba(220,38,38,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;}
.adblock-icon svg{width:30px;height:30px;stroke:#EF4444;}
.adblock-inner h2{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--text);margin-bottom:.6rem;}
.adblock-inner p{font-size:.9rem;color:var(--muted);line-height:1.7;margin-bottom:1.5rem;font-weight:300;}
.adblock-btn{background:transparent;color:var(--accent);border:1px solid rgba(220,38,38,.4);padding:.85rem 2rem;border-radius:4px;font-family:'Inter',sans-serif;font-weight:600;font-size:.92rem;cursor:pointer;transition:all .2s;}
.adblock-btn:hover{background:rgba(220,38,38,.08);}

/* Header */
header{background:var(--header-bg);border-bottom:1px solid var(--border);backdrop-filter:blur(14px);position:sticky;top:0;z-index:50;}
.header-inner{max-width:1280px;margin:0 auto;padding:.9rem 1.25rem;display:flex;align-items:center;justify-content:space-between;}
.logo{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--text);letter-spacing:.04em;}
.logo span{color:var(--accent);font-style:italic;}
.header-timer{display:flex;align-items:center;gap:.65rem;background:rgba(220,38,38,.06);border:1px solid var(--border);padding:.45rem 1rem;border-radius:999px;}
.header-timer-ring{position:relative;width:32px;height:32px;flex-shrink:0;}
.header-timer-ring svg{width:100%;height:100%;transform:rotate(-90deg);}
.ht-track{fill:none;stroke:rgba(220,38,38,.1);stroke-width:3;}
.ht-fill{fill:none;stroke:var(--accent);stroke-width:3;stroke-linecap:round;stroke-dasharray:75.4;stroke-dashoffset:0;transition:stroke-dashoffset 1s linear;}
.header-timer-num{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:var(--accent);}
.header-timer-label{font-size:.88rem;color:var(--muted);}
.progress-strip{height:2px;background:rgba(220,38,38,.07);}
.progress-strip-fill{height:100%;background:linear-gradient(90deg,var(--primary),var(--accent));transition:width 1s linear;}

/* Layout */
main{max-width:1280px;margin:0 auto;width:100%;padding:1.5rem 1rem;flex:1;}
.three-col{display:grid;grid-template-columns:1fr;gap:1.25rem;}
@media(min-width:1024px){.three-col{grid-template-columns:180px 1fr 180px;}}
.col-skyscraper{display:none;}
@media(min-width:1024px){.col-skyscraper{display:block;}}
.sky-card{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:.6rem;position:sticky;top:calc(3.5rem + 1rem);}
.ad-label-small{font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(245,240,240,.18);text-align:center;margin-bottom:.5rem;}
.sky-slot{width:160px;height:600px;display:flex;align-items:center;justify-content:center;background:var(--ad-bg);border:1px dashed rgba(220,38,38,.12);border-radius:3px;overflow:hidden;}
.ad-placeholder-text{font-size:.72rem;color:rgba(245,240,240,.15);text-align:center;}
.center-card{background:var(--card-bg);border:1px solid var(--border);border-radius:8px;padding:1.75rem 1.5rem;backdrop-filter:blur(10px);}
@media(min-width:640px){.center-card{padding:2rem;}}
.ad-leaderboard{margin-bottom:1.75rem;}
.leaderboard-slot{width:100%;max-width:728px;height:90px;margin:0 auto;display:flex;align-items:center;justify-content:center;background:var(--ad-bg);border:1px dashed rgba(220,38,38,.12);border-radius:3px;overflow:hidden;}
@media(max-width:480px){.leaderboard-slot{height:70px;}}

/* Timer */
.timer-area{text-align:center;margin-bottom:1.75rem;}
.timer-circle{position:relative;width:108px;height:108px;margin:0 auto 1.25rem;}
@media(min-width:640px){.timer-circle{width:120px;height:120px;}}
.tc-svg{width:100%;height:100%;transform:rotate(-90deg);}
.tc-track{fill:none;stroke:rgba(220,38,38,.08);stroke-width:10;}
.tc-fill{fill:none;stroke:var(--accent);stroke-width:10;stroke-linecap:round;stroke-dasharray:339.3;stroke-dashoffset:0;transition:stroke-dashoffset 1s linear;filter:drop-shadow(0 0 6px rgba(220,38,38,.5));}
.tc-num{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:2.8rem;font-weight:900;color:var(--accent);}
.timer-heading{font-family:'Playfair Display',serif;font-size:clamp(1.3rem,3vw,1.75rem);font-weight:700;color:var(--text);margin-bottom:.5rem;line-height:1.2;}
.timer-heading em{color:var(--accent);font-style:italic;}
.timer-sub{font-size:.9rem;color:var(--muted);font-weight:300;line-height:1.65;}
.rose-divider{height:1px;background:linear-gradient(90deg,transparent,rgba(220,38,38,.28),transparent);margin:1.25rem 0;}
.ad-medium{display:flex;justify-content:center;margin-bottom:1.75rem;}
.medium-slot{width:300px;height:250px;display:flex;align-items:center;justify-content:center;background:var(--ad-bg);border:1px dashed rgba(220,38,38,.12);border-radius:3px;overflow:hidden;}
@media(max-width:340px){.medium-slot{width:260px;height:210px;}}

/* Captcha */
.captcha-area{margin-bottom:1.75rem;display:flex;justify-content:center;}
.captcha-box{background:rgba(14,4,4,.95);border:1px solid var(--border);border-radius:5px;padding:1rem 1.5rem;display:flex;align-items:center;gap:.85rem;min-width:280px;max-width:340px;width:100%;}
.captcha-check{width:26px;height:26px;border:1px solid rgba(220,38,38,.3);border-radius:3px;flex-shrink:0;}
.captcha-text{font-size:.92rem;color:var(--muted);}
.captcha-logo{margin-left:auto;font-size:.62rem;color:rgba(245,240,240,.18);text-align:right;flex-shrink:0;line-height:1.4;}

/* Button */
.get-link-btn{width:100%;padding:1.1rem 1.5rem;background:rgba(14,4,4,.9);border:1px solid rgba(220,38,38,.18);border-radius:6px;color:rgba(245,240,240,.25);font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;cursor:not-allowed;display:flex;align-items:center;justify-content:center;gap:.75rem;transition:all .35s;position:relative;overflow:hidden;}
.get-link-btn.active{background:transparent;border:1px solid rgba(220,38,38,.45);color:var(--accent);cursor:pointer;box-shadow:0 0 30px rgba(220,38,38,.08),inset 0 0 30px rgba(220,38,38,.03);}
.get-link-btn.active::after{content:'';position:absolute;top:0;left:-100%;width:60%;height:100%;background:linear-gradient(90deg,transparent,rgba(220,38,38,.07),transparent);animation:btnshine 2.5s infinite;}
@keyframes btnshine{to{left:160%;}}
.spinner{width:20px;height:20px;border:2.5px solid rgba(245,240,240,.08);border-top-color:rgba(220,38,38,.5);border-radius:50%;animation:spin .8s linear infinite;flex-shrink:0;}
@keyframes spin{to{transform:rotate(360deg);}}
.ad-bottom{display:flex;justify-content:center;margin-top:1.75rem;}

/* Info section */
.info-section{display:grid;grid-template-columns:1fr;gap:1rem;margin-top:1.5rem;}
@media(min-width:580px){.info-section{grid-template-columns:repeat(3,1fr);}}
.info-card{background:var(--card-bg-info);border:1px solid var(--border-color);border-radius:6px;padding:1.4rem;}
.info-card .icon{width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;}
.info-card .icon.red{background:rgba(220,38,38,.1);color:#F87171;}
.info-card .icon.blue{background:rgba(59,130,246,.1);color:#60A5FA;}
.info-card .icon.green{background:rgba(34,197,94,.09);color:#4ADE80;}
.info-card h3{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--text);margin-bottom:.5rem;}
.info-card p{font-size:.88rem;color:var(--subtle-text);line-height:1.7;font-weight:300;}
.cta-section{background:var(--card-bg-info);border:1px solid var(--border-color);border-radius:6px;padding:1.75rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-top:1rem;}
.cta-section h2{font-family:'Playfair Display',serif;font-size:clamp(1rem,2.5vw,1.25rem);font-weight:700;color:var(--text);}
.cta-section p{font-size:.88rem;color:var(--subtle-text);margin-top:.25rem;font-weight:300;}
.cta-button{background:var(--primary);color:var(--text);text-decoration:none;padding:.8rem 1.75rem;border-radius:5px;font-family:'Inter',sans-serif;font-weight:600;font-size:.92rem;transition:all .2s;white-space:nowrap;display:inline-block;}
.cta-button:hover{background:var(--accent);}
footer{background:var(--header-bg);border-top:1px solid var(--border);}
.footer-inner{max-width:1280px;margin:0 auto;padding:1.1rem 1.25rem;display:flex;flex-direction:column;align-items:center;gap:.75rem;font-size:.82rem;color:var(--muted);}
@media(min-width:640px){.footer-inner{flex-direction:row;justify-content:space-between;}}
.footer-links{display:flex;gap:1.5rem;flex-wrap:wrap;}
.footer-links a{color:var(--muted);text-decoration:none;transition:color .2s;}
.footer-links a:hover{color:var(--accent);}
.ad-block{transition:transform .3s ease,box-shadow .3s ease;}
.ad-block:hover{transform:translateY(-2px);}
</style>
</head>
<body>

{{-- AdBlock Modal --}}
<div id="adblock-modal">
  <div class="adblock-inner">
    <div class="adblock-icon"><svg fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M4.93 4.93l14.14 14.14" stroke-width="2" stroke-linecap="round"/></svg></div>
    <h2>AdBlock Detected!</h2>
    <p>Please disable your ad blocker to continue. Our service relies on advertisements to remain free.</p>
    <button class="adblock-btn" onclick="location.reload()">I've Disabled AdBlock – Reload</button>
  </div>
</div>

<div class="page-wrap">
  <header>
    <div class="header-inner">
      <div class="logo">Mr<span>Short</span></div>
      <div class="header-timer">
        <div class="header-timer-ring">
          <svg viewBox="0 0 30 30"><circle class="ht-track" cx="15" cy="15" r="12"/><circle class="ht-fill" id="timer-progress-small" cx="15" cy="15" r="12"/></svg>
          <div class="header-timer-num" id="timer-countdown-small">{{ $adStep->wait_time ?? 10 }}</div>
        </div>
        <span class="header-timer-label">seconds</span>
      </div>
    </div>
    <div class="progress-strip"><div class="progress-strip-fill" id="progress-bar" style="width:100%"></div></div>
  </header>

  {{-- Ad slot assignment --}}
  @php
    $getAdSize = fn($ad) => $ad->ad_data['size'] ?? null;
    $ads_728x90   = $adsData->filter(fn($a) => $getAdSize($a) === '728x90')->values();
    $ads_300x250  = $adsData->filter(fn($a) => $getAdSize($a) === '300x250')->values();
    $ads_160x600  = $adsData->filter(fn($a) => $getAdSize($a) === '160x600')->values();
    $ads_320x50   = $adsData->filter(fn($a) => $getAdSize($a) === '320x50')->values();
    $ads_unknown  = $adsData->filter(fn($a) => !in_array($getAdSize($a), ['728x90','300x250','160x600','320x50']))->values();
    $ads_300x250  = $ads_300x250->merge($ads_unknown)->values();
    $leftSkyscraper  = $ads_160x600->shift();
    $topAd           = $ads_728x90->shift() ?? $ads_320x50->shift();
    $middleAd        = $ads_300x250->shift();
    $bottomAd        = $ads_300x250->shift();
    $rightSkyscraper = $ads_160x600->shift();
  @endphp

  <main>
    <div class="three-col">
      {{-- Left Skyscraper --}}
      <div class="col-skyscraper">
        <div class="sky-card">
          <p class="ad-label-small">Advertisement</p>
          <div class="sky-slot ad-block" data-ad-id="{{ $leftSkyscraper->id ?? '' }}" data-ad-type="{{ $leftSkyscraper ? ($leftSkyscraper->ad_type->value ?? '') : '' }}">
            @if($leftSkyscraper)
              @include('partials.ad_display', ['ad' => $leftSkyscraper])
            @else
              <span class="ad-placeholder-text">160×600<br>Skyscraper</span>
            @endif
          </div>
        </div>
      </div>

      {{-- Center --}}
      <div>
        <div class="center-card">
          {{-- Leaderboard --}}
          <div class="ad-leaderboard">
            <p class="ad-label-small">Advertisement</p>
            <div class="leaderboard-slot ad-block" data-ad-id="{{ $topAd->id ?? '' }}" data-ad-type="{{ $topAd ? ($topAd->ad_type->value ?? '') : '' }}">
              @if($topAd)
                @include('partials.ad_display', ['ad' => $topAd])
              @else
                <span class="ad-placeholder-text">728×90 Leaderboard</span>
              @endif
            </div>
          </div>

          {{-- Timer --}}
          <div class="timer-area">
            <div class="timer-circle">
              <svg class="tc-svg" viewBox="0 0 120 120"><circle class="tc-track" cx="60" cy="60" r="54"/><circle class="tc-fill" id="timer-progress" cx="60" cy="60" r="54"/></svg>
              <div class="tc-num" id="timer-countdown">{{ $adStep->wait_time ?? 10 }}</div>
            </div>
            <h1 class="timer-heading">Your destination is <em>almost ready</em></h1>
            <p class="timer-sub">Please wait while we prepare your exclusive content...</p>
          </div>

          <div class="rose-divider"></div>

          {{-- Middle Ad --}}
          <div class="ad-medium">
            <div class="medium-slot ad-block" data-ad-id="{{ $middleAd->id ?? '' }}" data-ad-type="{{ $middleAd ? ($middleAd->ad_type->value ?? '') : '' }}">
              @if($middleAd)
                @include('partials.ad_display', ['ad' => $middleAd])
              @else
                <span class="ad-placeholder-text">300×250 Banner</span>
              @endif
            </div>
          </div>

          {{-- Captcha --}}
          @if($showCaptcha)
          <div class="captcha-area">
            {!! $captchaService->getWidget() !!}
          </div>
          @else
          <div class="captcha-area">
            <div class="captcha-box">
              <div class="captcha-check"></div>
              <span class="captcha-text">I'm not a robot</span>
              <span class="captcha-logo">reCAPTCHA<br>Privacy · Terms</span>
            </div>
          </div>
          @endif

          {{-- Button --}}
          <button id="get-link-btn" class="get-link-btn">
            <div class="spinner" id="btn-spinner"></div>
            <span id="btn-text">Please wait...</span>
          </button>

          {{-- Bottom Ad --}}
          <div class="ad-bottom">
            <div class="medium-slot ad-block" data-ad-id="{{ $bottomAd->id ?? '' }}" data-ad-type="{{ $bottomAd ? ($bottomAd->ad_type->value ?? '') : '' }}">
              @if($bottomAd)
                @include('partials.ad_display', ['ad' => $bottomAd])
              @else
                <span class="ad-placeholder-text">300×250 Banner</span>
              @endif
            </div>
          </div>
        </div>

        {{-- Info / CTA --}}
        @include('partials.info_section')
      </div>

      {{-- Right Skyscraper --}}
      <div class="col-skyscraper">
        <div class="sky-card">
          <p class="ad-label-small">Advertisement</p>
          <div class="sky-slot ad-block" data-ad-id="{{ $rightSkyscraper->id ?? '' }}" data-ad-type="{{ $rightSkyscraper ? ($rightSkyscraper->ad_type->value ?? '') : '' }}">
            @if($rightSkyscraper)
              @include('partials.ad_display', ['ad' => $rightSkyscraper])
            @else
              <span class="ad-placeholder-text">160×600<br>Skyscraper</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <div class="footer-inner">
      <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
      <div class="footer-links">
        <a href="{{ route('privacy.policy') }}">Privacy Policy</a>
        <a href="{{ route('terms.of.service') }}">Terms of Use</a>
        <a href="{{ route('dmca.complaint', ['linkCode' => $link->code]) }}" target="_blank" rel="noopener" style="color:rgba(245,240,240,.28);display:flex;align-items:center;gap:.3rem;">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          Report DMCA
        </a>
      </div>
    </div>
  </footer>
</div>

{{-- Popup Ad --}}
@if(isset($userPopupAd) && $userPopupAd)
<script>
(function() {
    let popunderOpened = false;
    const popunderUrl = "{{ $userPopupAd['ad_data']['url'] ?? '' }}";
    const popunderId = {{ $userPopupAd['id'] ?? 0 }};
    if (popunderUrl) {
        document.addEventListener('click', function openPopunder(e) {
            if (popunderOpened) return;
            if (e.target.closest('button,a,input,select,textarea')) return;
            popunderOpened = true;
            if (popunderId) {
                fetch(`/ads/track-click/popup/${popunderId}?userPopupCampaignId=${popunderId}`, {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }, body: JSON.stringify({})
                }).catch(e => console.error(e));
            }
            const w = window.open(popunderUrl, '_blank', 'noopener,noreferrer');
            if (w) { window.focus(); setTimeout(() => window.focus(), 100); }
            document.removeEventListener('click', openPopunder);
        }, { once: false });
    }
})();
</script>
@endif

<script>
const countdownEl = document.getElementById('timer-countdown');
const countdownSmall = document.getElementById('timer-countdown-small');
const progressCircle = document.getElementById('timer-progress');
const progressCircleSmall = document.getElementById('timer-progress-small');
const progressBar = document.getElementById('progress-bar');
const getLinkBtn = document.getElementById('get-link-btn');
const btnSpinner = document.getElementById('btn-spinner');
const btnText = document.getElementById('btn-text');

const totalTime = {{ $adStep->wait_time ?? 10 }};
let timeLeft = totalTime;
let timerPaused = false;

const CIRC = 339.3, HTC = 75.4;
progressCircle.style.strokeDashoffset = 0;
progressCircleSmall.style.strokeDashoffset = 0;

function setProgress(pct) {
    progressCircle.style.strokeDashoffset = CIRC * (1 - pct/100);
    progressCircleSmall.style.strokeDashoffset = HTC * (1 - pct/100);
    progressBar.style.width = pct + '%';
}

function proceedToNextStep() {
    const requiresCaptcha = {{ $showCaptcha ? 'true' : 'false' }};
    if (requiresCaptcha) {
        let captchaResponse = null;
        const t = document.querySelector('[name="cf-turnstile-response"]');
        const g = document.querySelector('[name="g-recaptcha-response"]');
        const h = document.querySelector('[name="h-captcha-response"]');
        if (t) captchaResponse = t.value;
        if (g) captchaResponse = g.value;
        if (h) captchaResponse = h.value;
        if (!captchaResponse) { alert('Please complete the captcha verification first.'); return; }
        fetch('/go/{{ $link->code }}/captcha', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ 'cf-turnstile-response': t?.value, 'g-recaptcha-response': g?.value, 'h-captcha-response': h?.value })
        }).then(r => r.json()).then(data => { if (data.success) navigateToNext(); else alert(data.message || 'Captcha failed.'); }).catch(() => alert('Captcha failed.'));
        return;
    }
    navigateToNext();
}

function navigateToNext() {
    const currentStep = {{ $stepNumber }};
    const totalSteps = {{ $campaignOrTemplate->campaignTemplateSteps->count() }};
    const linkCode = "{{ $link->code }}";
    const ctId = {{ $campaignOrTemplate->id ?? 'null' }};
    let nextUrl = currentStep < totalSteps ? `/link/${linkCode}/step/${currentStep+1}?campaignTemplateId=${ctId}` : `/link/${linkCode}/complete`;
    window.location.href = nextUrl;
}

setProgress(100);
const interval = setInterval(() => {
    if (timerPaused) return;
    timeLeft--;
    countdownEl.textContent = timeLeft;
    countdownSmall.textContent = timeLeft;
    setProgress(100 * timeLeft / totalTime);
    if (timeLeft <= 0) {
        clearInterval(interval);
        btnSpinner.style.display = 'none';
        btnText.textContent = 'Get Your Link →';
        getLinkBtn.classList.add('active');
        getLinkBtn.onclick = proceedToNextStep;
    }
}, 1000);

document.addEventListener('DOMContentLoaded', function() {
    function showAdblockModal() {
        timerPaused = true;
        const m = document.getElementById('adblock-modal');
        m.style.display = 'flex';
        m.classList.add('show');
    }
    if (window.adblockDetected) showAdblockModal();
    else window.onAdblockDetected(showAdblockModal);
    if (typeof AdBlockDetector !== 'undefined') AdBlockDetector.onDetected(showAdblockModal);
    setTimeout(function() { if (!window.adsLoaded || !window.googleAdsLoaded) window.triggerAdblockDetected('variable_check'); }, 1500);
    document.querySelectorAll('.ad-block').forEach(el => {
        el.addEventListener('click', function() {
            const adId = el.dataset.adId, adType = el.dataset.adType;
            if (!adId || !adType) return;
            fetch(`/ads/track-click/${adType}/${adId}`, { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body:JSON.stringify({}) }).catch(e => console.error(e));
        });
    });
});
</script>

@if(!empty($thirdPartyAdCodes) && is_array($thirdPartyAdCodes))
    @foreach($thirdPartyAdCodes as $adCode)
        @if(!empty($adCode['code']))
            @php
                $snippet = trim($adCode['code']);
                $isHtml = preg_match('/^\s*<(script|iframe|div|span|ins|a|img|style)/i', $snippet);
            @endphp
            @if($isHtml)
                {!! $snippet !!}
            @else
                <script>(function(){try{ {!! $snippet !!} }catch(e){console.error(e);}})()</script>
            @endif
        @endif
    @endforeach
@endif

</body>
</html>
