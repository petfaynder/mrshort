<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Login - Monetize Your Links</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
</head>
<body class="font-display">
<div class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
<div class="flex flex-1 w-full">
<div class="flex flex-col w-full lg:w-1/2 items-center justify-center p-6 sm:p-8 md:p-12 z-10">
<div class="flex flex-col max-w-md w-full gap-6">
<div class="animate-form-item mb-4" style="animation-delay: 0.05s;">
    <a href="{{ url('/') }}" class="inline-block">
        <span class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-[#00bfff] to-[#ff00ff] tracking-tight" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">MrShort</span>
    </a>
</div>
<div class="animate-form-item" style="animation-delay: 0.1s;">
<p class="text-black dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Welcome Back!</p>
<p class="text-slate-500 dark:text-slate-400 text-base mt-2">Log in to your account to continue.</p>
</div>
<div class="flex flex-col sm:flex-row gap-3 w-full animate-form-item" style="animation-delay: 0.2s;">
<a href="{{ route('auth.google') }}" class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-300 hover:scale-105 active:scale-95">
<svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
</svg>
<span class="truncate">Continue with Google</span>
</a>
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

@if (session('error'))
    <div class="animate-form-item items-center gap-3 rounded-lg border border-danger/50 bg-danger/10 p-3 text-sm text-danger dark:text-red-400" style="animation-delay: 0.35s; display: flex;">
        <span class="material-symbols-outlined">error</span>
        <p>{{ session('error') }}</p>
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

    {{-- Captcha Widget --}}
    <div class="animate-form-item" style="animation-delay: 0.65s;">
        <x-captcha form="login" />
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
</div>
</div>
</div>

<script>
    // Password toggle function (global scope for onclick access)
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

    document.addEventListener('DOMContentLoaded', function() {
        const formItems = document.querySelectorAll('.animate-form-item');
        formItems.forEach((item, index) => {
            item.style.animationDelay = `${0.1 * index}s`;
            item.classList.add('animate-form-item');
        });

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

