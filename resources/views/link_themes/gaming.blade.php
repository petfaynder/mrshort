<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ config('app.name') }} — Loading...</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script>
    window.adblockDetected = false; window.adblockCallbacks = [];
    window.onAdblockDetected = function(cb){window.adblockCallbacks.push(cb);if(window.adblockDetected)cb();};
    window.triggerAdblockDetected = function(m){if(window.adblockDetected)return;window.adblockDetected=true;window.adblockCallbacks.forEach(function(cb){try{cb();}catch(e){}});};
</script>
<script src="/ads.js" onerror="window.triggerAdblockDetected('ads_js_blocked')"></script>
<script src="/pagead/js/adsbygoogle.js" onerror="window.triggerAdblockDetected('adsense_blocked')"></script>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Share+Tech+Mono&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet"/>
@php
    $captchaService = app(\App\Services\CaptchaService::class);
    $captchaEnabled = setting('captcha_enabled', false);
    $captchaOnShortlink = setting('captcha_on_shortlink', false);
    $captchaVerified = session('captcha_verified_' . $link->code);
    $isFirstStep = ($stepNumber ?? 1) === 1;
    $showCaptcha = $isFirstStep && $captchaEnabled && $captchaOnShortlink && !$captchaVerified;
@endphp
@if($showCaptcha){!! $captchaService->getScript() !!}@endif
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--primary:#7C3AED;--accent:#BF00FF;--accent2:#00FFFF;--neon-green:#39FF14;--bg:#06060F;--header-bg:rgba(6,6,15,0.94);--card-bg:rgba(10,10,24,0.88);--border:rgba(124,58,237,0.28);--text:#E0D8FF;--muted:rgba(224,216,255,0.6);--subtle-text:rgba(224,216,255,0.62);--ad-bg:rgba(8,8,22,0.7);--card-bg-info:rgba(10,10,24,0.72);--border-color:rgba(124,58,237,0.22);}
html,body{min-height:100%;}
body{font-family:'Barlow',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;}
body::before{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(124,58,237,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,.06) 1px,transparent 1px);background-size:44px 44px;animation:gridMove 25s linear infinite;pointer-events:none;z-index:0;}
@keyframes gridMove{0%{background-position:0 0,0 0;}100%{background-position:0 44px,44px 0;}}
body::after{content:'';position:fixed;top:0;left:0;right:0;height:40%;background:radial-gradient(ellipse 80% 55% at 50% -5%,rgba(191,0,255,.18) 0%,transparent 70%);pointer-events:none;z-index:0;}
.scanlines{position:fixed;inset:0;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,.03) 2px,rgba(0,0,0,.03) 4px);pointer-events:none;z-index:1;}
.page-wrap{position:relative;z-index:2;min-height:100vh;display:flex;flex-direction:column;}
#adblock-modal{position:fixed;inset:0;z-index:200;align-items:center;justify-content:center;background:rgba(0,0,0,.9);backdrop-filter:blur(6px);display:none;}
#adblock-modal.show{display:flex;}
.adblock-inner{background:#0C0C20;border:1px solid var(--accent);border-radius:8px;padding:2.5rem 2rem;max-width:440px;width:calc(100% - 2rem);text-align:center;animation:shake .5s ease-in-out;clip-path:polygon(0 0,calc(100% - 14px) 0,100% 14px,100% 100%,14px 100%,0 calc(100% - 14px));}
@keyframes shake{0%,100%{transform:translateX(0);}25%{transform:translateX(-6px);}75%{transform:translateX(6px);}}
.adblock-icon{width:64px;height:64px;background:rgba(191,0,255,.12);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;}
.adblock-icon svg{width:30px;height:30px;stroke:#BF00FF;}
.adblock-inner h2{font-family:'Orbitron',sans-serif;font-size:1.15rem;color:var(--text);margin-bottom:.6rem;letter-spacing:.08em;}
.adblock-inner p{font-size:.88rem;color:var(--muted);line-height:1.7;margin-bottom:1.5rem;font-family:'Share Tech Mono',monospace;}
.adblock-btn{background:transparent;color:var(--accent);border:1px solid var(--accent);padding:.85rem 2rem;font-family:'Orbitron',sans-serif;font-weight:700;font-size:.78rem;letter-spacing:.12em;cursor:pointer;text-transform:uppercase;transition:all .2s;}
.adblock-btn:hover{background:rgba(191,0,255,.1);}
header{background:var(--header-bg);border-bottom:1px solid var(--border);backdrop-filter:blur(14px);position:sticky;top:0;z-index:50;}
.header-inner{max-width:1280px;margin:0 auto;padding:.9rem 1.25rem;display:flex;align-items:center;justify-content:space-between;}
.logo{font-family:'Orbitron',sans-serif;font-size:1.4rem;font-weight:900;color:var(--text);letter-spacing:.1em;text-shadow:0 0 15px rgba(191,0,255,.5);}
.logo span{color:var(--accent);}
.header-timer{display:flex;align-items:center;gap:.65rem;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.3);padding:.45rem 1rem;border-radius:3px;font-family:'Share Tech Mono',monospace;}
.header-timer-ring{position:relative;width:32px;height:32px;flex-shrink:0;}
.header-timer-ring svg{width:100%;height:100%;transform:rotate(-90deg);}
.ht-track{fill:none;stroke:rgba(124,58,237,.15);stroke-width:3;}
.ht-fill{fill:none;stroke:var(--accent);stroke-width:3;stroke-linecap:round;stroke-dasharray:75.4;stroke-dashoffset:0;transition:stroke-dashoffset 1s linear;filter:drop-shadow(0 0 4px rgba(191,0,255,.7));}
.header-timer-num{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:var(--accent);}
.header-timer-label{font-size:.88rem;color:var(--muted);letter-spacing:.06em;}
.progress-strip{height:3px;background:rgba(124,58,237,.08);}
.progress-strip-fill{height:100%;background:linear-gradient(90deg,var(--primary),var(--accent),var(--accent2));transition:width 1s linear;box-shadow:0 0 10px rgba(191,0,255,.5);}
main{max-width:1280px;margin:0 auto;width:100%;padding:1.5rem 1rem;flex:1;}
.three-col{display:grid;grid-template-columns:1fr;gap:1.25rem;}
@media(min-width:1024px){.three-col{grid-template-columns:170px 1fr 170px;}}
.col-skyscraper{display:none;}
@media(min-width:1024px){.col-skyscraper{display:block;}}
.sky-card{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;padding:.6rem;position:sticky;top:1rem;}
.ad-label-small{font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(224,216,255,.18);text-align:center;margin-bottom:.5rem;font-family:'Share Tech Mono',monospace;}
.sky-slot{width:160px;height:600px;display:flex;align-items:center;justify-content:center;background:var(--ad-bg);border:1px dashed rgba(124,58,237,.18);border-radius:2px;overflow:hidden;}
.ad-placeholder-text{font-size:.72rem;color:rgba(224,216,255,.16);text-align:center;letter-spacing:.06em;font-family:'Share Tech Mono',monospace;}
.center-card{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:1.75rem 1.5rem;backdrop-filter:blur(10px);position:relative;}
.center-card::before{content:'';position:absolute;inset:-1px;border-radius:6px;background:linear-gradient(135deg,rgba(191,0,255,.07),transparent 50%,rgba(0,255,255,.04));pointer-events:none;}
@media(min-width:640px){.center-card{padding:2rem;}}
.ad-leaderboard{margin-bottom:1.75rem;}
.leaderboard-slot{width:100%;max-width:728px;height:90px;margin:0 auto;display:flex;align-items:center;justify-content:center;background:var(--ad-bg);border:1px dashed rgba(124,58,237,.18);border-radius:3px;overflow:hidden;}
@media(max-width:480px){.leaderboard-slot{height:70px;}}
.timer-area{text-align:center;margin-bottom:1.75rem;}
.timer-circle{position:relative;width:100px;height:100px;margin:0 auto 1.25rem;}
@media(min-width:640px){.timer-circle{width:110px;height:110px;}}
.tc-svg{width:100%;height:100%;transform:rotate(-90deg);}
.tc-track{fill:none;stroke:rgba(124,58,237,.1);stroke-width:10;}
.tc-fill{fill:none;stroke:var(--accent);stroke-width:10;stroke-linecap:round;stroke-dasharray:339.3;stroke-dashoffset:0;transition:stroke-dashoffset 1s linear;filter:drop-shadow(0 0 8px rgba(191,0,255,.6));}
.tc-num{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:'Orbitron',sans-serif;font-size:2.2rem;font-weight:900;color:var(--accent);}
.prompt-wrap{display:flex;align-items:center;justify-content:center;gap:.5rem;margin-bottom:.5rem;}
.prompt-sym{font-family:'Share Tech Mono',monospace;font-size:1.3rem;color:var(--neon-green);text-shadow:0 0 8px var(--neon-green);}
.timer-heading{font-family:'Orbitron',sans-serif;font-size:clamp(1rem,2.5vw,1.25rem);letter-spacing:.08em;text-transform:uppercase;color:var(--text);}
.timer-sub{font-family:'Share Tech Mono',monospace;font-size:.88rem;color:var(--muted);margin-top:.4rem;}
.timer-sub .highlight{color:var(--accent2);}
.ad-medium{display:flex;justify-content:center;margin-bottom:1.75rem;}
.medium-slot{width:300px;height:250px;display:flex;align-items:center;justify-content:center;background:var(--ad-bg);border:1px dashed rgba(124,58,237,.18);border-radius:3px;overflow:hidden;}
@media(max-width:340px){.medium-slot{width:260px;height:210px;}}
.captcha-area{margin-bottom:1.75rem;display:flex;justify-content:center;}
.captcha-box{background:rgba(10,10,24,.9);border:1px solid rgba(124,58,237,.25);border-radius:3px;padding:1rem 1.5rem;display:flex;align-items:center;gap:.85rem;min-width:280px;max-width:340px;width:100%;}
.captcha-check{width:26px;height:26px;border:1px solid rgba(124,58,237,.4);border-radius:2px;flex-shrink:0;background:rgba(124,58,237,.05);}
.captcha-text{font-family:'Share Tech Mono',monospace;font-size:.92rem;color:var(--muted);}
.captcha-logo{margin-left:auto;font-size:.62rem;color:rgba(224,216,255,.18);letter-spacing:.04em;font-family:'Share Tech Mono',monospace;text-align:right;flex-shrink:0;}
.get-link-btn{width:100%;padding:1.1rem 1.5rem;background:rgba(15,10,35,.88);border:1px solid rgba(124,58,237,.25);border-radius:3px;color:rgba(224,216,255,.28);font-family:'Orbitron',sans-serif;font-size:.92rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;cursor:not-allowed;display:flex;align-items:center;justify-content:center;gap:.75rem;transition:all .35s;position:relative;overflow:hidden;clip-path:polygon(0 0,calc(100% - 12px) 0,100% 12px,100% 100%,12px 100%,0 calc(100% - 12px));}
.get-link-btn.active{background:transparent;border:1px solid var(--accent);color:var(--accent);cursor:pointer;box-shadow:0 0 25px rgba(191,0,255,.18),inset 0 0 25px rgba(191,0,255,.04);}
.get-link-btn.active::after{content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:linear-gradient(45deg,transparent,rgba(191,0,255,.12),transparent);transform:rotate(45deg);animation:btnshine 2.5s infinite;}
@keyframes btnshine{from{left:-50%;}to{left:150%;}}
.spinner{width:20px;height:20px;border:2.5px solid rgba(224,216,255,.08);border-top-color:var(--accent);border-radius:50%;animation:spin .8s linear infinite;flex-shrink:0;}
@keyframes spin{to{transform:rotate(360deg);}}
.ad-bottom{display:flex;justify-content:center;margin-top:1.75rem;}
.info-section{display:grid;grid-template-columns:1fr;gap:1rem;margin-top:1.5rem;}
@media(min-width:580px){.info-section{grid-template-columns:repeat(3,1fr);}}
.info-card{background:var(--card-bg-info);border:1px solid var(--border-color);border-radius:4px;padding:1.4rem;}
.info-card .icon{width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;}
.info-card .icon.red{background:rgba(239,68,68,.1);color:#F87171;}
.info-card .icon.blue{background:rgba(0,255,255,.08);color:var(--accent2);}
.info-card .icon.green{background:rgba(57,255,20,.08);color:var(--neon-green);}
.info-card h3{font-family:'Orbitron',sans-serif;font-size:.92rem;font-weight:700;color:var(--text);margin-bottom:.5rem;letter-spacing:.06em;}
.info-card p{font-size:.88rem;color:var(--subtle-text);line-height:1.7;}
.cta-section{background:var(--card-bg-info);border:1px solid var(--border-color);border-radius:4px;padding:1.75rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-top:1rem;}
.cta-section h2{font-family:'Orbitron',sans-serif;font-size:clamp(.9rem,2vw,1.05rem);font-weight:900;color:var(--text);letter-spacing:.06em;}
.cta-section p{font-size:.86rem;color:var(--subtle-text);margin-top:.25rem;font-family:'Share Tech Mono',monospace;}
.cta-button{background:transparent;color:var(--accent);border:1px solid var(--accent);text-decoration:none;padding:.8rem 1.75rem;font-family:'Orbitron',sans-serif;font-weight:700;font-size:.8rem;letter-spacing:.12em;text-transform:uppercase;transition:all .2s;white-space:nowrap;display:inline-block;}
.cta-button:hover{background:rgba(191,0,255,.1);}
footer{background:var(--header-bg);border-top:1px solid var(--border);}
.footer-inner{max-width:1280px;margin:0 auto;padding:1.1rem 1.25rem;display:flex;flex-direction:column;align-items:center;gap:.75rem;font-size:.82rem;color:var(--muted);font-family:'Share Tech Mono',monospace;}
@media(min-width:640px){.footer-inner{flex-direction:row;justify-content:space-between;}}
.footer-links{display:flex;gap:1.5rem;flex-wrap:wrap;}
.footer-links a{color:var(--muted);text-decoration:none;transition:color .2s;}
.footer-links a:hover{color:var(--accent);}
.ad-block{transition:transform .3s ease;}
.ad-block:hover{transform:translateY(-2px);}
</style>
</head>
<body>
<div class="scanlines"></div>
<div id="adblock-modal">
  <div class="adblock-inner">
    <div class="adblock-icon"><svg fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M4.93 4.93l14.14 14.14" stroke-width="2" stroke-linecap="round"/></svg></div>
    <h2>// AdBlock Detected</h2>
    <p>Please disable your ad blocker to continue. Our service relies on advertisements to remain free.</p>
    <button class="adblock-btn" onclick="location.reload()">[ Disable AdBlock – Reload ]</button>
  </div>
</div>
<div class="page-wrap">
  <header>
    <div class="header-inner">
      <div class="logo">MR<span>SHORT</span></div>
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
      <div class="col-skyscraper">
        <div class="sky-card">
          <p class="ad-label-small">// advertisement</p>
          <div class="sky-slot ad-block" data-ad-id="{{ $leftSkyscraper->id ?? '' }}" data-ad-type="{{ $leftSkyscraper ? ($leftSkyscraper->ad_type->value ?? '') : '' }}">
            @if($leftSkyscraper) @include('partials.ad_display', ['ad' => $leftSkyscraper]) @else <span class="ad-placeholder-text">160×600<br>Skyscraper</span> @endif
          </div>
        </div>
      </div>
      <div>
        <div class="center-card">
          <div class="ad-leaderboard">
            <p class="ad-label-small">// advertisement</p>
            <div class="leaderboard-slot ad-block" data-ad-id="{{ $topAd->id ?? '' }}" data-ad-type="{{ $topAd ? ($topAd->ad_type->value ?? '') : '' }}">
              @if($topAd) @include('partials.ad_display', ['ad' => $topAd]) @else <span class="ad-placeholder-text">728×90 Leaderboard</span> @endif
            </div>
          </div>
          <div class="timer-area">
            <div class="timer-circle">
              <svg class="tc-svg" viewBox="0 0 120 120"><circle class="tc-track" cx="60" cy="60" r="54"/><circle class="tc-fill" id="timer-progress" cx="60" cy="60" r="54"/></svg>
              <div class="tc-num" id="timer-countdown">{{ $adStep->wait_time ?? 10 }}</div>
            </div>
            <div class="prompt-wrap"><span class="prompt-sym">&gt;_</span><span class="timer-heading">LOADING DESTINATION...</span></div>
            <p class="timer-sub">Stand by — link ready in <span class="highlight">T-<span id="ts-countdown">{{ $adStep->wait_time ?? 10 }}</span>s</span></p>
          </div>
          <div class="ad-medium">
            <div class="medium-slot ad-block" data-ad-id="{{ $middleAd->id ?? '' }}" data-ad-type="{{ $middleAd ? ($middleAd->ad_type->value ?? '') : '' }}">
              @if($middleAd) @include('partials.ad_display', ['ad' => $middleAd]) @else <span class="ad-placeholder-text">300×250 Banner</span> @endif
            </div>
          </div>
          @if($showCaptcha)
          <div class="captcha-area">{!! $captchaService->getWidget() !!}</div>
          @else
          <div class="captcha-area">
            <div class="captcha-box">
              <div class="captcha-check"></div>
              <span class="captcha-text">// I'm not a robot</span>
              <span class="captcha-logo">reCAPTCHA<br>Privacy · Terms</span>
            </div>
          </div>
          @endif
          <button id="get-link-btn" class="get-link-btn">
            <div class="spinner" id="btn-spinner"></div>
            <span id="btn-text">PLEASE WAIT...</span>
          </button>
          <div class="ad-bottom">
            <div class="medium-slot ad-block" data-ad-id="{{ $bottomAd->id ?? '' }}" data-ad-type="{{ $bottomAd ? ($bottomAd->ad_type->value ?? '') : '' }}">
              @if($bottomAd) @include('partials.ad_display', ['ad' => $bottomAd]) @else <span class="ad-placeholder-text">300×250 Banner</span> @endif
            </div>
          </div>
        </div>
        <div class="info-section">
          <div class="info-card">
            <div class="icon red"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
            <h3>CREATE ACCOUNT</h3><p>Creating an account would not take you more than 3 minutes. Email, username and password only.</p>
          </div>
          <div class="info-card">
            <div class="icon blue"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg></div>
            <h3>SHORTEN LINKS</h3><p>After you create an account, use our powerful tools to shorten links that you want to share.</p>
          </div>
          <div class="info-card">
            <div class="icon green"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
            <h3>EARN MONEY</h3><p>Share links and get paid for each visit based on our competitive payout rates.</p>
          </div>
        </div>
        <div class="cta-section">
          <div><h2>READY TO START EARNING?</h2><p>Register your account. System is 100% free.</p></div>
          <a href="{{ route('register') }}" class="cta-button">[ SIGN UP ]</a>
        </div>
      </div>
      <div class="col-skyscraper">
        <div class="sky-card">
          <p class="ad-label-small">// advertisement</p>
          <div class="sky-slot ad-block" data-ad-id="{{ $rightSkyscraper->id ?? '' }}" data-ad-type="{{ $rightSkyscraper ? ($rightSkyscraper->ad_type->value ?? '') : '' }}">
            @if($rightSkyscraper) @include('partials.ad_display', ['ad' => $rightSkyscraper]) @else <span class="ad-placeholder-text">160×600<br>Skyscraper</span> @endif
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer>
    <div class="footer-inner">
      <span>© {{ date('Y') }} MRSHORT_SYS</span>
      <div class="footer-links">
        <a href="{{ route('privacy.policy') }}">Privacy</a>
        <a href="{{ route('terms.of.service') }}">Terms</a>
        <a href="{{ route('dmca.complaint', ['linkCode' => $link->code]) }}" target="_blank" rel="noopener" style="color:rgba(224,216,255,.28);">Report DMCA</a>
      </div>
    </div>
  </footer>
</div>
@if(isset($userPopupAd) && $userPopupAd)
<script>(function(){let p=false;const u="{{ $userPopupAd['ad_data']['url'] ?? '' }}",id={{ $userPopupAd['id'] ?? 0 }};if(u){document.addEventListener('click',function o(e){if(p)return;if(e.target.closest('button,a,input,select,textarea'))return;p=true;if(id){fetch(`/ads/track-click/popup/${id}?userPopupCampaignId=${id}`,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({})}).catch(e=>console.error(e));}const w=window.open(u,'_blank','noopener,noreferrer');if(w){window.focus();setTimeout(()=>window.focus(),100);}document.removeEventListener('click',o);},{once:false});}})()</script>
@endif
<script>
const countdownEl=document.getElementById('timer-countdown'),countdownSmall=document.getElementById('timer-countdown-small'),tsCD=document.getElementById('ts-countdown'),progressCircle=document.getElementById('timer-progress'),progressCircleSmall=document.getElementById('timer-progress-small'),progressBar=document.getElementById('progress-bar'),getLinkBtn=document.getElementById('get-link-btn'),btnSpinner=document.getElementById('btn-spinner'),btnText=document.getElementById('btn-text');
const totalTime={{ $adStep->wait_time ?? 10 }};let timeLeft=totalTime,timerPaused=false;
const CIRC=339.3,HTC=75.4;progressCircle.style.strokeDashoffset=0;progressCircleSmall.style.strokeDashoffset=0;
function setProgress(pct){progressCircle.style.strokeDashoffset=CIRC*(1-pct/100);progressCircleSmall.style.strokeDashoffset=HTC*(1-pct/100);progressBar.style.width=pct+'%';}
function proceedToNextStep(){const rc={{ $showCaptcha ? 'true' : 'false' }};if(rc){let cr=null;const t=document.querySelector('[name="cf-turnstile-response"]'),g=document.querySelector('[name="g-recaptcha-response"]'),h=document.querySelector('[name="h-captcha-response"]');if(t)cr=t.value;if(g)cr=g.value;if(h)cr=h.value;if(!cr){alert('Please complete the captcha verification first.');return;}fetch('/go/{{ $link->code }}/captcha',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({'cf-turnstile-response':t?.value,'g-recaptcha-response':g?.value,'h-captcha-response':h?.value})}).then(r=>r.json()).then(d=>{if(d.success)navigateToNext();else alert(d.message||'Captcha failed.');}).catch(()=>alert('Captcha failed.'));return;}navigateToNext();}
function navigateToNext(){const cs={{ $stepNumber }},ts={{ $campaignOrTemplate->campaignTemplateSteps->count() }},lc="{{ $link->code }}",ct={{ $campaignOrTemplate->id ?? 'null' }};window.location.href=cs<ts?`/link/${lc}/step/${cs+1}?campaignTemplateId=${ct}`:`/link/${lc}/complete`;}
setProgress(100);
const interval=setInterval(()=>{if(timerPaused)return;timeLeft--;countdownEl.textContent=timeLeft;if(countdownSmall)countdownSmall.textContent=timeLeft;if(tsCD)tsCD.textContent=timeLeft;setProgress(100*timeLeft/totalTime);if(timeLeft<=0){clearInterval(interval);btnSpinner.style.display='none';btnText.textContent='[ ✓ DESTINATION READY — PROCEED ]';getLinkBtn.classList.add('active');getLinkBtn.onclick=proceedToNextStep;}},1000);
document.addEventListener('DOMContentLoaded',function(){function showAdblockModal(){timerPaused=true;const m=document.getElementById('adblock-modal');m.style.display='flex';m.classList.add('show');}if(window.adblockDetected)showAdblockModal();else window.onAdblockDetected(showAdblockModal);if(typeof AdBlockDetector!=='undefined')AdBlockDetector.onDetected(showAdblockModal);setTimeout(function(){if(!window.adsLoaded||!window.googleAdsLoaded)window.triggerAdblockDetected('variable_check');},1500);document.querySelectorAll('.ad-block').forEach(el=>{el.addEventListener('click',function(){const adId=el.dataset.adId,adType=el.dataset.adType;if(!adId||!adType)return;fetch(`/ads/track-click/${adType}/${adId}`,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({})}).catch(e=>console.error(e));});});});
</script>
@if(!empty($thirdPartyAdCodes)&&is_array($thirdPartyAdCodes))
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
