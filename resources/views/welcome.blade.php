<!DOCTYPE html>
<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Link Monetizer</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/ScrollTrigger.min.js"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0d7ff2",
                        "background-light": "#f5f7f8",
                        "background-dark": "#121212",
                        "electric-blue": "#00BFFF",
                        "bright-magenta": "#FF00FF",
                        "dark-gray": "#1a1a1a",
                        "medium-gray": "#2c2c2c",
                        "hero-bg": "#0B0F1A"
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "1rem",
                        "xl": "1.5rem",
                        "full": "9999px"
                    },
                    keyframes: {
                        'celestial-orbit': {
                           '0%': { transform: 'rotate(0deg) translateX(var(--orbit-radius)) rotate(0deg)' },
                           '100%': { transform: 'rotate(360deg) translateX(var(--orbit-radius)) rotate(-360deg)' }
                        },
                        'float': {
                           '0%, 100%': { transform: 'translateY(0)' },
                           '50%': { transform: 'translateY(-10px)' }
                        },
                        'slide': {
                            '0%': { transform: 'translateX(0)' },
                            '100%': { transform: 'translateX(calc(-250px * 7))' }
                        },
                        'fade-in': {
                            '0%': { opacity: 0, transform: 'translateY(10px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        },
                        'scroll-up': {
                            '0%': { transform: 'translateY(100%)' },
                            '100%': { transform: 'translateY(-100%)' },
                        },
                        'spin': {
                            from: { transform: 'rotate(0deg)' },
                            to: { transform: 'rotate(360deg)' }
                        }
                    },
                    animation: {
                        'celestial-orbit': 'celestial-orbit var(--duration) linear infinite',
                        'float': 'float 4s ease-in-out infinite',
                        'slide': 'slide 40s linear infinite',
                        'fade-in': 'fade-in 0.5s ease-out forwards',
                        'scroll-up': 'scroll-up 15s linear infinite',
                    }
                },
            },
        }
    </script>
<style>
        .hero-section {
            background-color: #0B0F1A;
            position: relative;
        }
        #hero-video {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: translate(-50%, -50%);
            z-index: 0;
            opacity: 0.2;
        }
        @media (prefers-reduced-motion: reduce) {
            #hero-video {
                display: none;
            }
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(circle at 50% 50%, rgba(11, 15, 26, 0) 0%, #0B0F1A 80%);
            z-index: 1;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.1;
            background-size: 40px 40px;
            z-index: 2;
        }
        @media (max-width: 1024px) {
            .hero-section::before {
                 background-image: radial-gradient(circle at 50% 20%, rgba(11, 15, 26, 0.2) 0%, #0B0F1A 70%);
            }
        }
        .activity-feed-item {
            display: none;
        }
        .activity-feed-item.active {
            display: flex;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
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
</head>
<body class="bg-background-light dark:bg-background-dark text-white font-display">
<header class="fixed top-0 left-0 right-0 z-50 bg-hero-bg/80 backdrop-blur-md border-b border-gray-800">
<nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between h-20">
<div class="flex items-center">
<a class="flex-shrink-0" href="{{ url('/') }}">
<span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta">{{ config('app.name', 'Link Monetizer') }}</span>
</a>
</div>
<div class="hidden md:block">
<div class="ml-10 flex items-baseline space-x-4">
<a class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="#">Features</a>
<a class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="#">Rates</a>
<a class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="#">Partners</a>
<a class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="{{ route('user.contact') }}">Contact</a>
</div>
</div>
<div class="flex items-center">
@auth
    <a class="hidden md:block text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="{{ route('dashboard') }}">Dashboard</a>
@else
    <a class="hidden md:block text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="{{ route('login') }}">Login</a>
    <a class="ml-4 inline-block bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold py-2 px-4 rounded-full hover:scale-105 transition-transform duration-300 text-sm" href="{{ route('register') }}">Sign Up</a>
@endauth
</div>
</div>
</nav>
</header>
<div class="relative min-h-screen w-full flex flex-col lg:flex-row overflow-hidden pt-20 hero-section">
<video autoplay="" id="hero-video" loop="" muted="" playsinline="">
<source src="https://assets.mixkit.co/videos/preview/mixkit-abstract-background-of-digital-binary-code-42867-large.mp4" type="video/mp4"/>
</video>
<div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 lg:p-16 text-center lg:text-left z-10 relative">
<div class="w-full max-w-md">
<h1 class="text-4xl md:text-6xl font-bold tracking-tighter leading-tight mb-4 text-gray-50">
                    Shrink. Share. <span class="text-electric-blue">Earn.</span>
</h1>
<p class="text-lg md:text-xl text-gray-300 mb-8">
                    Transform your links into a revenue stream. Get paid for every click with our industry-leading CPM rates.
                </p>
<div class="relative">
<form method="POST" action="{{ route('links.store') }}" class="flex">
    @csrf
    <input name="long_url" class="w-full h-16 bg-gray-800/50 border-2 border-gray-700 rounded-full pl-6 pr-40 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-electric-blue transition-all duration-300" placeholder="Paste your long URL here" type="text" required/>
    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 w-32 h-12 bg-gradient-to-r from-electric-blue to-bright-magenta rounded-full text-white font-bold flex items-center justify-center hover:scale-105 transition-transform duration-300">
        <span class="truncate">Monetize</span>
    </button>
</form>
</div>
</div>
</div>
<div class="w-full lg:w-1/2 flex items-center justify-center p-8 relative z-10">
<div class="absolute inset-0 z-0">
<img class="w-full h-full object-cover opacity-30" data-alt="Abstract 3D rendering of a network globe with glowing lines" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCYDhH8zvhdxlYu3sC8S5ZuWkvA1Md3Ikht6pUwL1kYtRRvoC0fOFWXsk1HkUyJFaK1R-nP1DXcKNG0JgzAEORNlzH2Rln6pyli9-Aw3iZAcULHm2EM1Y5ttUIwj5YEErTabe3wz-aFPur0hxQ5cxMh2RAjNoT6H2qFc3K2On9-ov9goeEmR9B9N1m-KNwPGlaUo67CiKw4vyBbhhxVwnB7xozd51bdsrQp11ZqBGePLDIg-x298lNlr5c0oU62SeRkgaWcSmqs"/>
</div>
<div class="relative z-10 w-full max-w-lg space-y-6">
<div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 bg-gray-900/50 backdrop-blur-sm border border-gray-800">
<p class="text-white text-base font-medium leading-normal flex items-center gap-2"><span class="material-symbols-outlined text-electric-blue">public</span> Links Shortened Today</p>
<p class="text-white tracking-light text-3xl font-bold leading-tight">1,234,567</p>
</div>
<div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 bg-gray-900/50 backdrop-blur-sm border border-gray-800">
<p class="text-white text-base font-medium leading-normal flex items-center gap-2"><span class="material-symbols-outlined text-electric-blue">ads_click</span> Total Clicks</p>
<p class="text-white tracking-light text-3xl font-bold leading-tight">10,987,654</p>
</div>
<div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 bg-gray-900/50 backdrop-blur-sm border border-gray-800">
<p class="text-white text-base font-medium leading-normal flex items-center gap-2"><span class="material-symbols-outlined text-bright-magenta">payments</span> Potential Earnings</p>
<p class="text-white tracking-light text-3xl font-bold leading-tight">$1,234.56</p>
</div>
</div>
</div>
</div>
<div class="bg-background-dark py-20 sm:py-24 px-4 sm:px-8" id="stats-section">
<div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 text-center">
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-8 flex flex-col items-center justify-center">
<span class="material-symbols-outlined text-5xl text-electric-blue mb-4">link</span>
<h3 class="text-2xl font-bold text-gray-300 mb-2">Total Links Shortened</h3>
<p class="text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta" id="links-counter">0</p>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-8 flex flex-col items-center justify-center">
<span class="material-symbols-outlined text-5xl text-bright-magenta mb-4">paid</span>
<h3 class="text-2xl font-bold text-gray-300 mb-2">Total Platform Earnings</h3>
<p class="text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-bright-magenta to-electric-blue"><span id="earnings-prefix">$</span><span id="earnings-counter">0</span></p>
</div>
</div>
</div>
<div class="bg-dark-gray/50 py-20 sm:py-24 px-4 sm:px-8">
<div class="max-w-7xl mx-auto">
<div class="text-center mb-16">
<h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-4 text-shadow-lg">Why Choose Us?</h2>
<p class="text-lg text-gray-300 max-w-3xl mx-auto">Our platform is built to empower your earnings with cutting-edge features and unparalleled support.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center transform hover:-translate-y-2 transition-transform duration-300">
<span class="material-symbols-outlined text-5xl bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta mb-4">monetization_on</span>
<h3 class="text-2xl font-bold text-white mb-2">Easy Monetization</h3>
<p class="text-gray-400">Start earning with just a few clicks. Our intuitive platform makes link monetization accessible to everyone.</p>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center transform hover:-translate-y-2 transition-transform duration-300">
<span class="material-symbols-outlined text-5xl bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta mb-4">analytics</span>
<h3 class="text-2xl font-bold text-white mb-2">Detailed Analytics</h3>
<p class="text-gray-400">Track your performance with our comprehensive dashboard. Understand your audience and optimize your strategy.</p>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center transform hover:-translate-y-2 transition-transform duration-300">
<span class="material-symbols-outlined text-5xl bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta mb-4">public</span>
<h3 class="text-2xl font-bold text-white mb-2">Global Reach</h3>
<p class="text-gray-400">Earn from a worldwide audience. Our platform supports traffic from all countries with competitive rates.</p>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center transform hover:-translate-y-2 transition-transform duration-300">
<span class="material-symbols-outlined text-5xl bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta mb-4">trending_up</span>
<h3 class="text-2xl font-bold text-white mb-2">High CPMs</h3>
<p class="text-gray-400">We offer some of the most competitive CPM rates in the industry to maximize your earning potential.</p>
</div>
</div>
</div>
</div>
<div class="bg-background-dark py-20 sm:py-24 px-4 sm:px-8">
<div class="max-w-7xl mx-auto">
<div class="text-center mb-16">
<h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-4 text-shadow-lg">How It Works</h2>
<p class="text-lg text-gray-300 max-w-3xl mx-auto">Start earning in just a few simple steps. Our process is designed to be straightforward and efficient.</p>
</div>
<div class="relative">
<div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-gray-800"></div>
<div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-gradient-to-r from-electric-blue to-bright-magenta animate-pulse-slow"></div>
<div class="relative grid grid-cols-1 md:grid-cols-3 gap-12">
<div class="flex flex-col items-center text-center">
<div class="flex items-center justify-center w-24 h-24 rounded-full bg-gray-900/50 border-2 border-electric-blue mb-6 relative z-10 shadow-[0_0_20px_rgba(0,191,255,0.5)]">
<span class="material-symbols-outlined text-5xl text-electric-blue">create</span>
</div>
<h3 class="text-2xl font-bold text-white mb-2">1. Create an Account</h3>
<p class="text-gray-400">Sign up for a free account in seconds. All you need is an email to get started.</p>
</div>
<div class="flex flex-col items-center text-center">
<div class="flex items-center justify-center w-24 h-24 rounded-full bg-gray-900/50 border-2 border-electric-blue mb-6 relative z-10 shadow-[0_0_20px_rgba(0,191,255,0.5)]">
<span class="material-symbols-outlined text-5xl text-electric-blue">link</span>
</div>
<h3 class="text-2xl font-bold text-white mb-2">2. Shorten Your Link</h3>
<p class="text-gray-400">Paste your long URL into our tool to create a shortened, monetized link instantly.</p>
</div>
<div class="flex flex-col items-center text-center">
<div class="flex items-center justify-center w-24 h-24 rounded-full bg-gray-900/50 border-2 border-bright-magenta mb-6 relative z-10 shadow-[0_0_20px_rgba(255,0,255,0.5)]">
<span class="material-symbols-outlined text-5xl text-bright-magenta">paid</span>
</div>
<h3 class="text-2xl font-bold text-white mb-2">3. Earn Money</h3>
<p class="text-gray-400">Share your new link and get paid for every click based on our high CPM rates.</p>
</div>
</div>
</div>
<div class="mt-20 text-center">
<a class="inline-block bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold py-4 px-10 rounded-full hover:scale-105 transition-transform duration-300 text-lg shadow-[0_0_20px_rgba(0,191,255,0.5),_0_0_20px_rgba(255,0,255,0.5)] group" href="#">
                    Start Monetizing Now
                    <span class="inline-block transition-transform group-hover:translate-x-1 motion-reduce:transform-none">→</span>
</a>
</div>
</div>
</div>
<div class="bg-dark-gray/50 py-20 sm:py-24 px-4 sm:px-8">
<div class="max-w-7xl mx-auto">
<div class="text-center mb-16">
<h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-4 text-shadow-lg">What Our Users Say</h2>
<p class="text-lg text-gray-300 max-w-3xl mx-auto">Trusted by creators worldwide. Here's how our platform has helped them succeed.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-8 flex flex-col transform hover:-translate-y-2 transition-transform duration-300">
<span class="material-symbols-outlined text-5xl text-electric-blue mb-6">format_quote</span>
<p class="text-gray-300 flex-grow">"This is hands-down the best link monetization service I've ever used. The CPM rates are fantastic, and the dashboard is incredibly intuitive. I saw my earnings double in the first month!"</p>
<div class="flex items-center mt-6">
<img alt="User avatar" class="w-12 h-12 rounded-full mr-4 border-2 border-electric-blue" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCUA-JqtWZLz752Xs5rAxzxIPHcGqikmXdoUr2DdCIL6cZRZ067lEMc7HP5vjx_41MyK29dOzneCCOPJF_htoEHsEKOLkkWZqv6NaZigLi-BmU3G5t1TD-AbzgUnYL3aEvOx8Y2mZEY_pJ4E7obYCWLmtPYynTR0_nlKEExjq7odZu7XtEkM3lSRSrT0BfaIE_GG9jxdYq5yl_umK5lQQVQEZM63p3nSdG0VhvMdM5dS6_yF99E3UBTLMDJt1dS1zyPddPLLbgY"/>
<div>
<p class="font-bold text-white">Sarah J.</p>
<p class="text-sm text-gray-400">Content Creator</p>
</div>
</div>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-8 flex flex-col transform hover:-translate-y-2 transition-transform duration-300">
<span class="material-symbols-outlined text-5xl text-bright-magenta mb-6">format_quote</span>
<p class="text-gray-300 flex-grow">"The API integration was a breeze. We've automated our entire link shortening process and it's been a game-changer for our business. Reliable, fast, and profitable."</p>
<div class="flex items-center mt-6">
<img alt="User avatar" class="w-12 h-12 rounded-full mr-4 border-2 border-bright-magenta" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC1xjircuAW9GDYcKX8HKmJv2_qFhZoDPQcIy5quRBbtaDQDCnWzhLdJV1vb7b0aRS5LEQXwEhQ1YvfXhbRn4fXXdSpVQFvlEkDvEKW4vw7K3X7tBN5nEli0lSMK0PXQE47GxwlvW90njRyD2wvhWJesfrHHF025GlrzNs_yuWdrXxScWgedCLai3n45-XMrwZT73wP7wVkb9fSya_MyYsmokITzB_Pd2gYwG_m0LniDN_KHaxAUETbbVtBEECChpMC5r3b877h"/>
<div>
<p class="font-bold text-white">Mike R.</p>
<p class="text-sm text-gray-400">App Developer</p>
</div>
</div>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-8 flex flex-col transform hover:-translate-y-2 transition-transform duration-300">
<span class="material-symbols-outlined text-5xl text-electric-blue mb-6">format_quote</span>
<p class="text-gray-300 flex-grow">"I love the global reach and competitive rates for my country. Payouts are always on time, and the support team is genuinely helpful. Highly recommended for anyone looking to monetize their traffic."</p>
<div class="flex items-center mt-6">
<img alt="User avatar" class="w-12 h-12 rounded-full mr-4 border-2 border-electric-blue" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfNnaC4Spa9QmjB_AYXhcX-LhOgKO33Up1x47Wk-PP097jgAVzQ3FEi9OhNaOyWPUAcgGQV-LrqgpA0FVGIl70bPA-E1rL5OUVVhwzt1Ax5ufO5lTkJxec-JRbsiDN5Lii-L62c7KEIqJxSBiOLSehLGAiYwJQkYloKgIwRNcU7EdvrHPZBytw0a8FZ8W9ueYg9By-bYeECd_BpI02YxwYa67Z2ACsZTpx4cgyJ4leZbHenlRs0vzbdrjHaKAWdvKFortt3d2D"/>
<div>
<p class="font-bold text-white">Aisha K.</p>
<p class="text-sm text-gray-400">Blogger</p>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="bg-background-dark py-20 sm:py-24 px-4 sm:px-8 relative overflow-hidden">
<div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-background-dark to-transparent z-10"></div>
<div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-background-dark to-transparent z-10"></div>
<div class="max-w-4xl mx-auto relative">
<div class="text-center mb-16">
<h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-4 text-shadow-lg">Frequently Asked Questions</h2>
<p class="text-lg text-gray-300 max-w-3xl mx-auto">Find quick answers to common questions about our service. If you can't find your answer here, feel free to contact us.</p>
</div>
<div class="space-y-6" x-data="{ open: 0 }">
<div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
<button @click="open = open === 1 ? 0 : 1" class="w-full text-left p-6 flex justify-between items-center hover:bg-gray-800/50 transition-colors duration-300">
<h3 class="text-xl font-semibold text-white">What is a monetized link shortener?</h3>
<span :class="{'rotate-45': open === 1}" class="material-symbols-outlined text-electric-blue transform transition-transform duration-300">add</span>
</button>
<div class="px-6 pb-6 pt-2 text-gray-400" x-collapse="" x-show="open === 1">
<p>A monetized link shortener is a service that transforms your long URLs into shorter, more manageable links. When a user clicks on this new link, they are shown a brief advertisement before being redirected to the original destination. You, as the link creator, earn a portion of the advertising revenue for each valid click.</p>
</div>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
<button @click="open = open === 2 ? 0 : 2" class="w-full text-left p-6 flex justify-between items-center hover:bg-gray-800/50 transition-colors duration-300">
<h3 class="text-xl font-semibold text-white">How much can I earn?</h3>
<span :class="{'rotate-45': open === 2}" class="material-symbols-outlined text-bright-magenta transform transition-transform duration-300">add</span>
</button>
<div class="px-6 pb-6 pt-2 text-gray-400" x-collapse="" x-show="open === 2">
<p>Your earnings depend on several factors, primarily the CPM (Cost Per Mille, or per 1000 views) rate for the country where the click originates. We offer competitive rates for traffic from all over the world. Higher traffic from countries like the USA, UK, and Canada generally yields higher earnings. You can use our Earning Potential Calculator to get an estimate.</p>
</div>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
<button @click="open = open === 3 ? 0 : 3" class="w-full text-left p-6 flex justify-between items-center hover:bg-gray-800/50 transition-colors duration-300">
<h3 class="text-xl font-semibold text-white">What are the payment methods and schedule?</h3>
<span :class="{'rotate-45': open === 3}" class="material-symbols-outlined text-electric-blue transform transition-transform duration-300">add</span>
</button>
<div class="px-6 pb-6 pt-2 text-gray-400" x-collapse="" x-show="open === 3">
<p>We offer a variety of payment methods including PayPal, Payoneer, and Bank Transfer. Payments are processed on a monthly basis, typically within the first week of the month, as long as you have reached the minimum payout threshold of $5.00.</p>
</div>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
<button @click="open = open === 4 ? 0 : 4" class="w-full text-left p-6 flex justify-between items-center hover:bg-gray-800/50 transition-colors duration-300">
<h3 class="text-xl font-semibold text-white">Are there any restrictions on the type of links I can share?</h3>
<span :class="{'rotate-45': open === 4}" class="material-symbols-outlined text-bright-magenta transform transition-transform duration-300">add</span>
</button>
<div class="px-6 pb-6 pt-2 text-gray-400" x-collapse="" x-show="open === 4">
<p>Yes. We strictly prohibit the shortening of links that point to adult content, malware, hate speech, illegal activities, or any other content that violates our Terms of Service. Violating these rules will result in an immediate account ban and forfeiture of all earnings.</p>
</div>
</div>
</div>
</div>
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[200%] h-[30rem] z-0 overflow-hidden pointer-events-none">
<div class="w-full h-full flex flex-col justify-around animate-scroll-up">
<div class="flex flex-col gap-4">
<div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-4 py-2 self-start ml-[10%]">
<span class="material-symbols-outlined text-electric-blue">link</span>
<p class="text-sm text-gray-300"><span class="font-bold text-white">User from Germany</span> just shortened a link</p>
</div>
<div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-4 py-2 self-end mr-[5%]">
<span class="material-symbols-outlined text-bright-magenta">payments</span>
<p class="text-sm text-gray-300"><span class="font-bold text-white">User from USA</span> just earned <span class="font-semibold text-bright-magenta">$0.05</span></p>
</div>
<div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-4 py-2 self-start ml-[20%]">
<span class="material-symbols-outlined text-electric-blue">link</span>
<p class="text-sm text-gray-300"><span class="font-bold text-white">User from Australia</span> just shortened a link</p>
</div>
<div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-4 py-2 self-end mr-[15%]">
<span class="material-symbols-outlined text-green-400">account_balance_wallet</span>
<p class="text-sm text-gray-300">Payout of <span class="font-bold text-green-400">$52.10</span> processed for <span class="font-bold text-white">User from India</span></p>
</div>
<div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-4 py-2 self-start ml-[12%]">
<span class="material-symbols-outlined text-bright-magenta">payments</span>
<p class="text-sm text-gray-300"><span class="font-bold text-white">User from Nigeria</span> just earned <span class="font-semibold text-bright-magenta">$0.08</span></p>
</div>
</div>
<div aria-hidden="true" class="flex flex-col gap-4">
<div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-4 py-2 self-start ml-[10%]">
<span class="material-symbols-outlined text-electric-blue">link</span>
<p class="text-sm text-gray-300"><span class="font-bold text-white">User from Germany</span> just shortened a link</p>
</div>
<div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-4 py-2 self-end mr-[5%]">
<span class="material-symbols-outlined text-bright-magenta">payments</span>
<p class="text-sm text-gray-300"><span class="font-bold text-white">User from USA</span> just earned <span class="font-semibold text-bright-magenta">$0.05</span></p>
</div>
<div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-4 py-2 self-start ml-[20%]">
<span class="material-symbols-outlined text-electric-blue">link</span>
<p class="text-sm text-gray-300"><span class="font-bold text-white">User from Australia</span> just shortened a link</p>
</div>
<div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-4 py-2 self-end mr-[15%]">
<span class="material-symbols-outlined text-green-400">account_balance_wallet</span>
<p class="text-sm text-gray-300">Payout of <span class="font-bold text-green-400">$52.10</span> processed for <span class="font-bold text-white">User from India</span></p>
</div>
<div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-4 py-2 self-start ml-[12%]">
<span class="material-symbols-outlined text-bright-magenta">payments</span>
<p class="text-sm text-gray-300"><span class="font-bold text-white">User from Nigeria</span> just earned <span class="font-semibold text-bright-magenta">$0.08</span></p>
</div>
</div>
</div>
</div>
</div>
<div class="bg-background-dark py-24 sm:py-32 px-4 sm:px-8 relative overflow-hidden">
<div class="absolute top-0 left-0 -translate-x-1/3 -translate-y-1/3 w-[800px] h-[800px] bg-[radial-gradient(ellipse_at_center,_rgba(0,191,255,0.2)_0%,rgba(0,191,255,0)_60%)]"></div>
<div class="absolute bottom-0 right-0 translate-x-1/3 translate-y-1/3 w-[800px] h-[800px] bg-[radial-gradient(ellipse_at_center,_rgba(255,0,255,0.2)_0%,rgba(255,0,255,0)_60%)]"></div>
<div class="max-w-7xl mx-auto relative z-10">
<div class="text-center mb-16">
<h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-4 text-shadow-lg">Global CPM Rates</h2>
<p class="text-lg text-gray-300 max-w-3xl mx-auto">Explore your earning potential across the globe. We offer competitive rates in every country, ensuring you maximize your income.</p>
</div>
<div class="flex justify-center items-center mb-12">
<div class="bg-dark-gray/50 backdrop-blur-sm border border-medium-gray rounded-full p-1 flex space-x-1">
<button class="px-6 py-2 rounded-full text-white bg-electric-blue font-semibold transition">Highest Paying</button>
<button class="px-6 py-2 rounded-full text-gray-400 hover:bg-medium-gray/50 transition">Europe</button>
<button class="px-6 py-2 rounded-full text-gray-400 hover:bg-medium-gray/50 transition">Asia</button>
<button class="px-6 py-2 rounded-full text-gray-400 hover:bg-medium-gray/50 transition">Americas</button>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
<div class="bg-dark-gray/70 backdrop-blur-md border border-medium-gray rounded-xl p-6 flex flex-col items-center text-center transform hover:scale-105 hover:border-electric-blue transition-all duration-300 shadow-2xl shadow-black/30">
<img alt="USA Flag" class="w-16 h-16 rounded-full mb-4 border-2 border-medium-gray" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB36LEZG5NJPwq3JeMdX-dRnXO0orRW1ppC3wPdN364UdS2ZZCnZspD7QF_lvFm35oWfaiUbGpvvKN9QVLN5UkIA1WZWG9ooVaifE8aN-DiAhxU9nBBMy_YIG24PcpXdVGm8MlZQim_OJ384B2Y3PzfwCFVKOTk4G-Yfh8YUMrkZ6BkbZl_maa7cTcyFE7FSJxqVPZGrsxEr2HtBWxkq2pw36joQ0hznZwwMjgrCByIisqe4iG8H5DnY73oO_agQ5tySh1rZcic"/>
<h3 class="text-2xl font-bold text-white mb-1">United States</h3>
<p class="text-gray-400 text-sm mb-4">North America</p>
<p class="text-4xl font-bold text-electric-blue mb-1">$22.00</p>
<p class="text-sm text-gray-400">per 1000 views</p>
</div>
<div class="bg-dark-gray/70 backdrop-blur-md border border-medium-gray rounded-xl p-6 flex flex-col items-center text-center transform hover:scale-105 hover:border-electric-blue transition-all duration-300 shadow-2xl shadow-black/30" style="animation-delay: 100ms;">
<img alt="UK Flag" class="w-16 h-16 rounded-full mb-4 border-2 border-medium-gray" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDjYfKSA8fyYbWtqxmI_FOaUJas6LxI6oOKbRi4JoNkuRhm0q-GAYmuIg3PeBe_aeeOPZLuQKMKecaVwuMESAZkHgOiWKrXPOdiEDw1Wg0-8xBg1FS1lIg9ffeZZf33wevLeRWxkscdmeqdTpUsf8Bt7FihZ3dSUO_aWnQX1McfNls2GpgpE4gTYL3KNmeloMDSRA39kOTbr7tF9ufm8iP70xNgBZ7OscSwXIzMvslzUIp9RWxCC1f-LwqFmy6xyQk3DL0NvIVE"/>
<h3 class="text-2xl font-bold text-white mb-1">United Kingdom</h3>
<p class="text-gray-400 text-sm mb-4">Europe</p>
<p class="text-4xl font-bold text-electric-blue mb-1">$20.50</p>
<p class="text-sm text-gray-400">per 1000 views</p>
</div>
<div class="bg-dark-gray/70 backdrop-blur-md border border-medium-gray rounded-xl p-6 flex flex-col items-center text-center transform hover:scale-105 hover:border-electric-blue transition-all duration-300 shadow-2xl shadow-black/30" style="animation-delay: 200ms;">
<img alt="Canada Flag" class="w-16 h-16 rounded-full mb-4 border-2 border-medium-gray" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBARHsCvKGP9ufOUTJ-TB7NvjNHbAw6_qA8uJPmujKzBLBYfFHsYSd17Ka-UEP3tpO0dfEeEpEMvAyqwZc-_OLUvy70mfImLZFL0cf_Z9zXjnZM1XpOSDcUSTNoaJMO9QaYVXChCqGqTrAsJbi60ol0ZEN1VQRZR5UKSm3NHQj5jokPS8YqL4KiHl3b8BoWG2RcBwSQzlufprT1fIzUGqD_BG5FN0qaTuIGMdxpfkh8szFEaF32D54fXnWXnPwqYS7m4Unzy3nLMr"/>
<h3 class="text-2xl font-bold text-white mb-1">Canada</h3>
<p class="text-gray-400 text-sm mb-4">North America</p>
<p class="text-4xl font-bold text-electric-blue mb-1">$19.80</p>
<p class="text-sm text-gray-400">per 1000 views</p>
</div>
<div class="bg-dark-gray/70 backdrop-blur-md border border-medium-gray rounded-xl p-6 flex flex-col items-center text-center transform hover:scale-105 hover:border-bright-magenta transition-all duration-300 shadow-2xl shadow-black/30" style="animation-delay: 300ms;">
<img alt="Australia Flag" class="w-16 h-16 rounded-full mb-4 border-2 border-medium-gray" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_djSQTStACYqcTPZyzR0ipIYvwfybzF3Tf7z8RueAWiRq9jsKC1eEq4TBEWAfQXm70CpIfPgQkkxVKq291izhVj1BDRKUWM6NNKMXGUTir8Q1Si2sDhaU5aG5mUujSBRxH7ptMjaSeULfRZdQkjlx8zw7ly8X3LKPN2FivFosaOI7AhCq2N9z0_FcVq6dVDFdfnhx3HnkBS2FFI2Ox8jp6o0QnZISUgfehZsR6mfKuxsQcz0FrPgdjO6qW0CNmaeGJW8rn9mk"/>
<h3 class="text-2xl font-bold text-white mb-1">Australia</h3>
<p class="text-gray-400 text-sm mb-4">Oceania</p>
<p class="text-4xl font-bold text-bright-magenta mb-1">$18.75</p>
<p class="text-sm text-gray-400">per 1000 views</p>
</div>
<div class="bg-dark-gray/70 backdrop-blur-md border border-medium-gray rounded-xl p-6 flex flex-col items-center text-center transform hover:scale-105 hover:border-bright-magenta transition-all duration-300 shadow-2xl shadow-black/30" style="animation-delay: 400ms;">
<img alt="Germany Flag" class="w-16 h-16 rounded-full mb-4 border-2 border-medium-gray" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBULFscPSsvHPVohwvMNpun6qcd-Z9uzi4Q-xlF3A4lgli2cqspvh6z5U8noLwxVm84BvHEU9GZhoELLLdeaomuLY7TWZv16fs8YvzdFZHlrwMRnbaXX8fM4fwjYAZfSHb8vFXCfpwQhfyy7a3Iw-ZlkVCUXw0RVYqoOL_dVGPzM2c_0WYcP2QcKsBv5AvkYnfJaR4BWGbFH_D89_FyfQeYJaKyubdcK-d3LVJMZ84Pah2TtzAnlmn-qPhqX4kCiYKMVv1cJOht"/>
<h3 class="text-2xl font-bold text-white mb-1">Germany</h3>
<p class="text-gray-400 text-sm mb-4">Europe</p>
<p class="text-4xl font-bold text-bright-magenta mb-1">$16.40</p>
<p class="text-sm text-gray-400">per 1000 views</p>
</div>
<div class="bg-dark-gray/70 backdrop-blur-md border border-medium-gray rounded-xl p-6 flex flex-col items-center text-center transform hover:scale-105 hover:border-bright-magenta transition-all duration-300 shadow-2xl shadow-black/30" style="animation-delay: 500ms;">
<img alt="France Flag" class="w-16 h-16 rounded-full mb-4 border-2 border-medium-gray" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAEklEQVR42mNkYAAAAAYAAjCB0C8y7joAAAAASUVORK5CYII=" style="background: url('image-c052ac5c5930438681534015f8c6b653') center/cover;"/>
<h3 class="text-2xl font-bold text-white mb-1">France</h3>
<p class="text-gray-400 text-sm mb-4">Europe</p>
<p class="text-4xl font-bold text-bright-magenta mb-1">$15.90</p>
<p class="text-sm text-gray-400">per 1000 views</p>
</div>
<div class="bg-dark-gray/70 backdrop-blur-md border border-medium-gray rounded-xl p-6 flex flex-col items-center text-center transform hover:scale-105 hover:border-gray-500 transition-all duration-300 shadow-2xl shadow-black/30" style="animation-delay: 600ms;">
<img alt="Japan Flag" class="w-16 h-16 rounded-full mb-4 border-2 border-medium-gray" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAEklEQVR42mNkYAAAAAYAAjCB0C8y7joAAAAASUVORK5CYII=" style="background: url('image-23c2a38c20554c20845353594892795f') center/cover;"/>
<h3 class="text-2xl font-bold text-white mb-1">Japan</h3>
<p class="text-gray-400 text-sm mb-4">Asia</p>
<p class="text-4xl font-bold text-gray-300 mb-1">$12.30</p>
<p class="text-sm text-gray-400">per 1000 views</p>
</div>
<div class="bg-dark-gray/70 backdrop-blur-md border border-medium-gray rounded-xl p-6 flex flex-col items-center text-center transform hover:scale-105 hover:border-gray-500 transition-all duration-300 shadow-2xl shadow-black/30" style="animation-delay: 700ms;">
<img alt="Brazil Flag" class="w-16 h-16 rounded-full mb-4 border-2 border-medium-gray" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBFk_arbyXvcPaGsxEoSgwJndRaNJ2jMoDkQApG-QvYgJaFWV141CO5N8CebREhNYCvRuPa54NqIY2WL6blu6E8vKxGPFsvsfV1ti1VZhf1jhAMdI_hy9w7GrjH8u8ckAuyq37FtEFHVrUXUS8UE1XekAp94Tm02-nUHBy06xz5l6ubDFv1ATw7EizSEbCcvzx4AOQ5jE_Jvkme-Pa4AfRItBCVue4m7Nse3W8NQfhmKA9_MDUR2sNk8kq3uLi-SYsLztNyweav"/>
<h3 class="text-2xl font-bold text-white mb-1">Brazil</h3>
<p class="text-gray-400 text-sm mb-4">South America</p>
<p class="text-4xl font-bold text-gray-300 mb-1">$8.50</p>
<p class="text-sm text-gray-400">per 1000 views</p>
</div>
</div>
<div class="mt-16 text-center">
<a class="inline-block bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold py-3 px-8 rounded-full hover:scale-105 transition-transform duration-300 group" href="#">
Explore All Countries <span class="inline-block transition-transform group-hover:translate-x-1 motion-reduce:transform-none">→</span>
</a>
</div>
</div>
</div>
<div class="bg-background-dark py-20 px-8">
<div class="max-w-5xl mx-auto">
<div class="text-center mb-16">
<h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-4">Calculate Your Earning Potential</h2>
<p class="text-lg text-gray-400 max-w-3xl mx-auto">Use our interactive calculator to estimate how much you could earn with our platform. Just slide to adjust the number of clicks your links receive.</p>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-8 md:p-12">
<div class="grid md:grid-cols-2 gap-12 items-center">
<div>
<label class="block text-lg text-gray-300 mb-4" for="clicks-slider">Your Monthly Clicks:</label>
<input class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer range-lg" id="clicks-slider" max="50000" min="1000" step="1000" type="range" value="10000"/>
<div class="flex justify-between text-sm text-gray-400 mt-2">
<span>1,000</span>
<span class="font-bold text-electric-blue text-2xl">10,000</span>
<span>50,000</span>
</div>
</div>
<div class="text-center">
<p class="text-gray-400 text-lg mb-2">Estimated Monthly Earnings</p>
<p class="text-5xl md:text-6xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta">$50.00</p>
</div>
</div>
<div class="mt-12 text-center">
<a class="inline-block bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold py-4 px-10 rounded-full hover:scale-105 transition-transform duration-300 text-lg" href="#">Start Earning Now</a>
</div>
</div>
</div>
</div>
<div class="bg-background-light dark:bg-background-dark py-20 px-8">
<div class="max-w-5xl mx-auto">
<div class="text-center mb-12">
<h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-4">Powerful Features, Seamlessly Integrated</h2>
<p class="text-lg text-gray-400 max-w-3xl mx-auto">Discover our powerful features that help you get the most out of your links, all wrapped in a sleek, intuitive interface.</p>
</div>
<div class="grid md:grid-cols-3 gap-8">
<div class="flex flex-col gap-3 rounded-xl border border-gray-800 bg-gray-900/50 p-6 text-center items-center hover:border-electric-blue transition-colors duration-300">
<span class="material-symbols-outlined text-5xl text-electric-blue mb-4">monetization_on</span>
<h3 class="text-2xl font-bold">Monetization</h3>
<p class="text-gray-400">Turn your links into a passive revenue stream. Effortlessly.</p>
</div>
<div class="flex flex-col gap-3 rounded-xl border border-gray-800 bg-gray-900/50 p-6 text-center items-center hover:border-electric-blue transition-colors duration-300">
<span class="material-symbols-outlined text-5xl text-electric-blue mb-4">monitoring</span>
<h3 class="text-2xl font-bold">Real-time Analytics</h3>
<p class="text-gray-400">Track every click and analyze your performance with a stunning visual dashboard.</p>
</div>
<div class="flex flex-col gap-3 rounded-xl border border-gray-800 bg-gray-900/50 p-6 text-center items-center hover:border-electric-blue transition-colors duration-300">
<span class="material-symbols-outlined text-5xl text-electric-blue mb-4">security</span>
<h3 class="text-2xl font-bold">Ironclad Security</h3>
<p class="text-gray-400">Your links are protected with our robust, cutting-edge security measures.</p>
</div>
</div>
</div>
</div>
<div class="bg-background-dark py-20 px-8">
<div class="max-w-5xl mx-auto">
<div class="text-center mb-16">
<h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-4">Partnership Opportunities</h2>
<p class="text-lg text-gray-400 max-w-3xl mx-auto">Collaborate with us to unlock new possibilities. We're actively seeking partners to grow with.</p>
</div>
<div class="grid md:grid-cols-2 gap-8">
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-8 hover:border-electric-blue transition-colors duration-300 flex flex-col items-start">
<span class="material-symbols-outlined text-5xl text-electric-blue mb-4">campaign</span>
<h3 class="text-2xl font-bold mb-3">Affiliate Program</h3>
<p class="text-gray-400 mb-6 flex-grow">Join our affiliate program and earn commissions by referring new users to our platform. Get access to marketing materials and a dedicated support team.</p>
<a class="font-bold text-electric-blue hover:text-bright-magenta transition-colors duration-300 flex items-center group mt-auto" href="#">
                    Become an Affiliate <span class="material-symbols-outlined ml-2 transition-transform group-hover:translate-x-1">arrow_forward</span>
</a>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-8 hover:border-bright-magenta transition-colors duration-300 flex flex-col items-start">
<span class="material-symbols-outlined text-5xl text-bright-magenta mb-4">business_center</span>
<h3 class="text-2xl font-bold mb-3">API Integration</h3>
<p class="text-gray-400 mb-6 flex-grow">Integrate our powerful link monetization engine into your own application or service with our flexible and robust API. Perfect for developers and businesses.</p>
<a class="font-bold text-electric-blue hover:text-bright-magenta transition-colors duration-300 flex items-center group mt-auto" href="#">
                    Explore API Docs <span class="material-symbols-outlined ml-2 transition-transform group-hover:translate-x-1">arrow_forward</span>
</a>
</div>
</div>
</div>
</div>
<div class="bg-gray-900/50 py-20 px-8">
<div class="max-w-5xl mx-auto text-center">
<h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-4">Your Security is Our Priority</h2>
<p class="text-lg text-gray-400 max-w-3xl mx-auto mb-12">We employ state-of-the-art security and privacy measures to protect your links, your earnings, and your data. Trust and safety are at the core of our service.</p>
<div class="grid md:grid-cols-3 gap-8 text-left">
<div class="bg-dark-gray p-6 rounded-xl border border-medium-gray hover:border-electric-blue transition-all duration-300 transform hover:-translate-y-2">
<span class="material-symbols-outlined text-4xl text-electric-blue mb-4">lock</span>
<h3 class="text-xl font-bold mb-2">End-to-End Encryption</h3>
<p class="text-gray-400">All data transmitted through our service is secured with industry-standard encryption protocols.</p>
</div>
<div class="bg-dark-gray p-6 rounded-xl border border-medium-gray hover:border-bright-magenta transition-all duration-300 transform hover:-translate-y-2">
<span class="material-symbols-outlined text-4xl text-bright-magenta mb-4">shield</span>
<h3 class="text-xl font-bold mb-2">Fraud Detection</h3>
<p class="text-gray-400">Our advanced systems actively monitor for and prevent fraudulent activity to protect your earnings.</p>
</div>
<div class="bg-dark-gray p-6 rounded-xl border border-medium-gray hover:border-electric-blue transition-all duration-300 transform hover:-translate-y-2">
<span class="material-symbols-outlined text-4xl text-electric-blue mb-4">privacy_tip</span>
<h3 class="text-xl font-bold mb-2">Privacy by Design</h3>
<p class="text-gray-400">We are committed to GDPR and CCPA compliance, ensuring your privacy is respected at every step.</p>
</div>
</div>
</div>
</div>
<div class="bg-background-dark py-20 px-8">
<div class="max-w-4xl mx-auto">
<div class="text-center mb-12">
<h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-4">Contact Us</h2>
<p class="text-lg text-gray-400 max-w-2xl mx-auto">Have questions? Our team is here to help. Reach out to us and we'll get back to you as soon as possible.</p>
</div>
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-8 md:p-12">
<form action="#" class="space-y-6">
<div class="grid md:grid-cols-2 gap-6">
<div>
<label class="block text-sm font-medium text-gray-300 mb-2" for="name">Name</label>
<input class="w-full bg-dark-gray border-2 border-medium-gray rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-electric-blue transition-all duration-300" id="name" name="name" placeholder="Your Name" type="text"/>
</div>
<div>
<label class="block text-sm font-medium text-gray-300 mb-2" for="email">Email</label>
<input class="w-full bg-dark-gray border-2 border-medium-gray rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-electric-blue transition-all duration-300" id="email" name="email" placeholder="your@email.com" type="email"/>
</div>
</div>
<div>
<label class="block text-sm font-medium text-gray-300 mb-2" for="message">Message</label>
<textarea class="w-full bg-dark-gray border-2 border-medium-gray rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-electric-blue transition-all duration-300" id="message" name="message" placeholder="How can we help you?" rows="4"></textarea>
</div>
<div class="text-center">
<button class="inline-block bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold py-3 px-8 rounded-full hover:scale-105 transition-transform duration-300 group" type="submit">
                        Send Message <span class="inline-block transition-transform group-hover:translate-x-1 motion-reduce:transform-none">→</span>
</button>
</div>
</form>
</div>
</div>
</div>
<footer class="bg-dark-gray/50 border-t border-gray-800 py-12 px-4 sm:px-6 lg:px-8">
<div class="max-w-7xl mx-auto">
<div class="flex flex-col md:flex-row justify-between items-center space-y-8 md:space-y-0">
<div class="flex flex-col items-center md:items-start">
<a class="flex-shrink-0 mb-4" href="{{ url('/') }}">
<span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta">{{ config('app.name', 'Link Monetizer') }}</span>
</a>
<p class="text-gray-400 text-sm max-w-xs text-center md:text-left">
                    Monetize your links and maximize your earnings with our powerful and intuitive platform.
                </p>
</div>
<div class="flex flex-col items-center md:items-end">
<div class="flex space-x-6 mb-6">
<a class="text-gray-400 hover:text-white transition-colors duration-300" href="#">
<svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
<path clip-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" fill-rule="evenodd"></path>
</svg>
</a>
<a class="text-gray-400 hover:text-white transition-colors duration-300" href="#">
<svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
<path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
</svg>
</a>
<a class="text-gray-400 hover:text-white transition-colors duration-300" href="#">
<svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
<path clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12.011c0 4.237 2.636 7.828 6.258 9.243.457.084.624-.198.624-.44v-1.55c-2.558.556-3.1-1.234-3.1-1.234-.415-1.054-1.012-1.334-1.012-1.334-.828-.567.062-.556.062-.556.915.064 1.396.938 1.396.938.813 1.394 2.132.992 2.652.758.083-.59.318-1.003.58-1.234-2.023-.23-4.148-1.013-4.148-4.503 0-.996.356-1.81.938-2.448-.094-.23-.406-1.158.09-2.415 0 0 .765-.245 2.5 1.013a8.672 8.672 0 012.274-.306c.767 0 1.54.103 2.274.306 1.735-1.258 2.5-1.013 2.5-1.013.496 1.257.184 2.185.09 2.415.582.638.938 1.452.938 2.448 0 3.5-2.129 4.27-4.158 4.496.327.28.62.836.62 1.684v2.483c0 .244.167.527.625.44C19.364 19.839 22 16.248 22 12.011 22 6.477 17.523 2 12 2z" fill-rule="evenodd"></path>
</svg>
</a>
</div>
<div class="flex space-x-6 text-sm">
<a class="text-gray-400 hover:text-white transition-colors duration-300" href="#">Terms of Service</a>
<a class="text-gray-400 hover:text-white transition-colors duration-300" href="#">Privacy Policy</a>
<a class="text-gray-400 hover:text-white transition-colors duration-300" href="#">Disclaimer</a>
</div>
</div>
</div>
<div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
<p>© 2024 Link Monetizer. All rights reserved.</p>
</div>
</div>
</footer>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        gsap.registerPlugin(ScrollTrigger);
        const counters = {
            links: 0,
            earnings: 0
        };
        const linksCounter = document.getElementById('links-counter');
        const earningsCounter = document.getElementById('earnings-counter');
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: "#stats-section",
                start: "top 80%", 
                toggleActions: "play none none none"
            }
        });
        tl.to(counters, {
            duration: 2.5,
            links: 28453987,
            onUpdate: () => linksCounter.innerHTML = Math.round(counters.links).toLocaleString(),
            ease: "power2.out"
        }, 0);
        tl.to(counters, {
            duration: 2.5,
            earnings: 142269.93,
            onUpdate: () => earningsCounter.innerHTML = counters.earnings.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
            ease: "power2.out"
        }, 0.2); 

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
<script defer="" src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 24px;
            height: 24px;
            background: #00BFFF;
            cursor: pointer;
            border-radius: 9999px;
            box-shadow: 0 0 10px #00BFFF, 0 0 20px #00BFFF;
        }
        input[type="range"]::-moz-range-thumb {
            width: 24px;
            height: 24px;
            background: #00BFFF;
            cursor: pointer;
            border-radius: 9999px;
            box-shadow: 0 0 10px #00BFFF, 0 0 20px #00BFFF;
        }
        @keyframes scroll-left {
            from { transform: translateX(0); }
            to { transform: translateX(-100%); }
        }
        .animate-scroll-left {
            animation: scroll-left 40s linear infinite;
        }
        @keyframes pulse-slow {
            0%, 100% { transform: scale(1); opacity: 0.2; }
            50% { transform: scale(1.05); opacity: 0.3; }
        }
       .animate-pulse-slow {
            animation: pulse-slow 8s ease-in-out infinite;
       }
       .text-shadow-lg {
        text-shadow: 0px 0px 15px rgba(0, 191, 255, 0.5), 0px 0px 30px rgba(255, 0, 255, 0.3);
       }
       [x-cloak] { display: none !important; }
       .transition-transform {
            transition: transform 0.3s ease;
       }
       .rotate-45 {
            transform: rotate(45deg);
       }
       .x-collapse {
           overflow: hidden;
           transition: height 0.3s ease-in-out;
       }
    </style>
</body></html>
