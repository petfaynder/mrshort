<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Login - Monetize Your Links</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<style type="text/tailwindcss">
        @keyframes form-item-fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-form-item {
            animation: form-item-fade-in 0.5s ease-out forwards;
        }
        @keyframes globe-spin {
            from { transform: rotateY(0deg); }
            to { transform: rotateY(360deg); }
        }
        .animate-globe-spin {
            animation: globe-spin 40s linear infinite;
        }
        @keyframes link-arc-in {
            0% { stroke-dashoffset: 1; opacity: 0; }
            20% { opacity: 1; }
            100% { stroke-dashoffset: 0; opacity: 1; }
        }
        .animate-link-arc-in {
            stroke-dasharray: 1;
            stroke-dashoffset: 1;
            animation: link-arc-in 2s ease-out forwards;
        }
        @keyframes float-up {
            0% { transform: translateY(0) scale(0.5); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateY(-100px) scale(1); opacity: 0; }
        }
        .animate-float-up {
            animation: float-up 5s ease-in-out infinite;
        }
        @keyframes pulse-subtle {
           0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(13, 127, 242, 0.4); }
           50% { transform: scale(1.05); box-shadow: 0 0 15px 5px rgba(13, 127, 242, 0.2); }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 4s ease-in-out infinite;
        }
        .error-message {
            display: none;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #fff;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
        }
    </style>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#0d7ff2",
              "background-light": "#f5f7f8",
              "background-dark": "#101922",
              "danger": "#ef4444",
            },
            fontFamily: {
              "display": ["Space Grotesk", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.5rem", "lg": "1rem", "xl": "1.5rem", "full": "9999px"},
          },
        },
      }
    </script>
</head>
<body class="font-display">
<div class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
<div class="flex flex-1 w-full">
<div class="flex flex-col w-full lg:w-1/2 items-center justify-center p-6 sm:p-8 md:p-12 z-10">
<div class="flex flex-col max-w-md w-full gap-6">
<div class="animate-form-item" style="animation-delay: 0.1s;">
<p class="text-black dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Welcome Back!</p>
<p class="text-slate-500 dark:text-slate-400 text-base mt-2">Log in to your account to continue.</p>
</div>
<div class="flex flex-col sm:flex-row gap-3 w-full animate-form-item" style="animation-delay: 0.2s;">
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-300 hover:scale-105 active:scale-95">
<span class="material-symbols-outlined mr-2">mail</span>
<span class="truncate">Log in with Google</span>
</button>
</div>
<div class="flex items-center gap-4 animate-form-item" style="animation-delay: 0.3s;">
<hr class="w-full border-slate-200 dark:border-slate-700"/>
<p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal text-center">Or</p>
<hr class="w-full border-slate-200 dark:border-slate-700"/>
</div>

@if (session('status'))
    <div class="animate-form-item items-center gap-3 rounded-lg border border-primary/50 bg-primary/10 p-3 text-sm text-primary dark:text-blue-400" style="animation-delay: 0.35s; display: flex;">
        <span class="material-symbols-outlined">check_circle</span>
        <p>{{ session('status') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="animate-form-item items-center gap-3 rounded-lg border border-danger/50 bg-danger/10 p-3 text-sm text-danger dark:text-red-400" style="animation-delay: 0.35s; display: flex;">
        <span class="material-symbols-outlined">error</span>
        <p>Please fix the errors below to continue.</p>
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
    @csrf

    <div class="flex flex-col w-full animate-form-item" style="animation-delay: 0.4s;">
        <label class="flex flex-col w-full">
            <p class="text-black dark:text-white text-base font-medium leading-normal pb-2">Email</p>
            <div class="relative w-full">
                <input name="email" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-black dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('email') ? 'border-danger bg-danger/10' : 'border-slate-200 dark:border-slate-700 bg-transparent dark:bg-transparent' }} h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 text-base font-normal leading-normal transition-all duration-300" placeholder="Enter your email address" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"/>
                @if ($errors->has('email'))
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-danger">error</span>
                @endif
            </div>
        </label>
        @if ($errors->has('email'))
            <p class="error-message flex items-center gap-1.5 mt-1.5 text-sm text-danger dark:text-red-400" style="display: flex;">
                <span class="material-symbols-outlined text-base">arrow_right_alt</span>
                <span>{{ $errors->first('email') }}</span>
            </p>
        @endif
    </div>

    <div class="flex flex-col w-full animate-form-item" style="animation-delay: 0.5s;">
        <label class="flex flex-col w-full">
            <div class="flex justify-between items-center pb-2">
                <p class="text-black dark:text-white text-base font-medium leading-normal">Password</p>
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-primary hover:underline" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <div class="relative w-full">
                <input name="password" id="password" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-black dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('password') ? 'border-danger bg-danger/10' : 'border-slate-200 dark:border-slate-700 bg-transparent dark:bg-transparent' }} h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 text-base font-normal leading-normal transition-all duration-300" placeholder="Enter your password" type="password" required autocomplete="current-password"/>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 cursor-pointer" id="password-toggle-icon" onclick="togglePasswordVisibility()">visibility</span>
                @if ($errors->has('password'))
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-danger">error</span>
                @endif
            </div>
        </label>
        @if ($errors->has('password'))
            <p class="error-message flex items-center gap-1.5 mt-1.5 text-sm text-danger dark:text-red-400" style="display: flex;">
                <span class="material-symbols-outlined text-base">arrow_right_alt</span>
                <span>{{ $errors->first('password') }}</span>
            </p>
        @endif
    </div>

    <div class="flex items-center gap-3 animate-form-item" style="animation-delay: 0.6s;">
        <input class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 text-primary focus:ring-primary/50" id="remember-checkbox" type="checkbox" name="remember"/>
        <label class="text-sm text-slate-600 dark:text-slate-400" for="remember-checkbox">
            Remember me
        </label>
    </div>

    <button type="submit" class="flex min-w-[84px] w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-all duration-300 hover:scale-105 active:scale-95 animate-form-item" style="animation-delay: 0.7s;">
        <span class="truncate">Log In</span>
    </button>
</form>

<p class="text-center text-sm text-slate-600 dark:text-slate-400 animate-form-item" style="animation-delay: 0.8s;">
    Don't have an account? <a class="text-primary hover:underline font-medium" href="{{ route('register') }}">Sign up</a>
</p>
</div>
</div>
<div class="hidden lg:flex w-1/2 bg-slate-900 items-center justify-center p-12 relative overflow-hidden [perspective:1000px]">
<div class="absolute inset-0 bg-grid-slate-700/20 [mask-image:radial-gradient(ellipse_at_center,black_20%,transparent_70%)]"></div>
<div class="z-10 flex flex-col items-center text-center w-full max-w-2xl">
<div class="relative w-full aspect-square max-w-md">
<div class="absolute inset-0 animate-globe-spin" style="transform-style: preserve-3d;">
<svg class="w-full h-full" fill="none" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
<defs>
<radialGradient cx="50%" cy="50%" fx="50%" fy="50%" id="globe-glow" r="50%">
<stop offset="0%" stop-color="#0d7ff2" stop-opacity="0.3"></stop>
<stop offset="100%" stop-color="#0d7ff2" stop-opacity="0"></stop>
</radialGradient>
</defs>
<circle cx="200" cy="200" fill="url(#globe-glow)" r="200"></circle>
<path d="M200,0 A200,200 0 0,1 200,400 A200,200 0 0,1 200,0 Z" stroke="rgba(13, 127, 242, 0.1)" stroke-width="1"></path>
<ellipse cx="200" cy="200" rx="100" ry="200" stroke="rgba(13, 127, 242, 0.1)" stroke-width="1" transform="rotate(90 200 200)"></ellipse>
<ellipse cx="200" cy="200" rx="100" ry="200" stroke="rgba(13, 127, 242, 0.1)" stroke-width="1" transform="rotate(45 200 200)"></ellipse>
<ellipse cx="200" cy="200" rx="100" ry="200" stroke="rgba(13, 127, 242, 0.1)" stroke-width="1" transform="rotate(-45 200 200)"></ellipse>
</svg>
</div>
<div class="absolute inset-0 flex items-center justify-center">
<div class="w-28 h-28 bg-primary/20 rounded-full flex items-center justify-center animate-pulse-subtle backdrop-blur-sm">
<div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center shadow-lg shadow-primary/50">
<span class="material-symbols-outlined text-5xl text-white">link</span>
</div>
</div>
</div>
<svg class="absolute inset-0 w-full h-full opacity-70" fill="none" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
<path class="animate-link-arc-in" d="M 50,150 A 150 150 0 0 1 350,150" stroke="#0d7ff2" stroke-width="2" style="animation-delay: 0.5s;"></path>
<path class="animate-link-arc-in" d="M 70,300 A 150 150 0 0 1 250,50" stroke="#0d7ff2" stroke-width="2" style="animation-delay: 1s;"></path>
<path class="animate-link-arc-in" d="M 330,300 A 150 150 0 0 0 150,50" stroke="#0d7ff2" stroke-width="2" style="animation-delay: 1.5s;"></path>
</svg>
<div class="absolute inset-0 pointer-events-none">
<div class="absolute top-[35%] left-[10%] text-green-400 font-bold text-lg animate-float-up" style="animation-delay: 0.8s;">+$0.05</div>
<div class="absolute top-[25%] left-[65%] text-green-400 font-bold text-xl animate-float-up" style="animation-delay: 1.3s;">+$0.12</div>
<div class="absolute top-[70%] left-[80%] text-green-400 font-bold text-base animate-float-up" style="animation-delay: 1.8s;">+$0.08</div>
<div class="absolute top-[75%] left-[15%] text-green-400 font-bold text-sm animate-float-up" style="animation-delay: 2.1s;">+$0.03</div>
</div>
</div>
<div class="flex flex-col gap-4 mt-8 z-10">
<h1 class="text-4xl font-bold text-white tracking-tight">Monetize Every Link, Globally.</h1>
<p class="text-slate-300 max-w-md text-lg">Watch your earnings grow as your links travel the world. Our platform turns your global audience into real revenue.</p>
</div>
</div>
<span class="absolute top-8 right-8 text-white font-bold text-xl z-20">LOGO</span>
</div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formItems = document.querySelectorAll('.animate-form-item');
        formItems.forEach((item, index) => {
            item.style.animationDelay = `${0.1 * index}s`;
            item.classList.add('animate-form-item');
        });

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = 'visibility';
            }
        }

        // Generic form submission loading animation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner"></span><span class="ml-2">Processing...</span>';
                }
            });
        });
    });
</script>
</body></html>
</final_file_content>

IMPORTANT: For any future changes to this file, use the final_file_content shown above as your reference. This content reflects the current state of the file, including any auto-formatting (e.g., if you used single quotes but the formatter converted them to double quotes). Always base your SEARCH/REPLACE operations on this final version to ensure accuracy.

<environment_details>
# Visual Studio Code Visible Files
resources/views/auth/login.blade.php

# Visual Studio Code Open Tabs
database/migrations/2025_10_26_135922_create_campaign_template_steps_table.php
database/migrations/2025_10_26_135942_create_campaign_template_ads_table.php
database/migrations/2025_10_26_135959_add_campaign_template_id_to_ad_campaigns_table.php
database/migrations/2025_10_26_140014_add_campaign_template_id_to_links_table.php
app/Models/CampaignTemplateStep.php
app/Models/CampaignTemplateAd.php
app/Models/Link.php
database/migrations/2025_10_26_144902_add_advanced_ad_campaign_fields_to_ad_campaigns_table.php
database/migrations/2025_10_26_151347_add_campaign_schedule_to_ad_campaigns_table.php
database/migrations/2025_10_26_162840_add_budget_and_run_until_budget_depleted_to_ad_campaigns_table.php
app/Models/AdCampaign.php
app/Filament/Resources/AdCampaignResource.php
resources/views/livewire/user/create-ad-campaign.blade.php
database/migrations/2025_10_26_171737_add_advertiser_rate_to_cpm_rates_table.php
database/migrations/2025_10_26_171759_rename_default_cpm_rate_and_add_advertiser_rate_to_cpm_tiers_table.php
app/Models/CpmRate.php
app/Models/CpmTier.php
app/Filament/Resources/CpmTierResource.php
resources/views/filament/pages/manage-country-cpm-rates.blade.php
app/Providers/Filament/AdminPanelProvider.php
app/Filament/Pages/ManageCountryCpmRates.php
app/Services/CampaignSeeder.php
app/Filament/Widgets/CampaignPerformanceWidget.php
app/Livewire/User/CreateAdCampaign.php
app/Livewire/User/EditAdCampaign.php
database/migrations/2025_10_26_194354_add_popup_fields_to_campaign_template_steps_table.php
app/Enums/AdType.php
database/migrations/2025_10_26_203307_drop_popup_ad_campaign_id_from_campaign_template_steps_table.php
app/Filament/Resources/CampaignTemplateResource.php
app/Models/CampaignTemplate.php
database/migrations/2025_10_26_215909_add_fields_to_campaign_templates_table.php
routes/web.php
app/Http/Controllers/LinkController.php
resources/views/partials/info_section.blade.php
resources/views/ad_banner_page.blade.php
resources/views/ad_interstitial.blade.php
resources/views/welcome.blade.php
resources/views/auth/register.blade.php
app/Http/Controllers/Auth/RegisteredUserController.php
resources/views/dashboard.blade.php
resources/views/user/dashboard/index.blade.php
app/Http/Controllers/Auth/AuthenticatedSessionController.php
resources/views/auth/login.blade.php
resources/views/partials/ad_display.blade.php
resources/views/livewire/user/edit-ad-campaign.blade.php

# Current Time
10/29/2025, 3:45:04 PM (Europe/Istanbul, UTC+3:00)

# Context Window Usage
82,732 / 1,048.576K tokens used (8%)

# Current Mode
ACT MODE
</environment_details>
