<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    
    {{-- Preconnect to critical third-party origins --}}
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://lh3.googleusercontent.com">
    
    {{-- SEO Meta Tags from Site Settings --}}
    <title>{{ setting('seo_meta_title', setting('site_name', 'MrShort') . ' - Monetize Your Links | Highest CPM Rates') }}</title>
    <meta name="description" content="{{ setting('seo_description', 'Turn your links into revenue. Get the highest CPM rates in the industry, instant payouts, and powerful analytics.') }}"/>
    <meta name="keywords" content="{{ setting('seo_keywords', 'link shortener, url shortener, earn money, monetize links') }}"/>
    
    {{-- Favicon --}}
    @if(setting('favicon_url'))
    <link rel="icon" href="{{ setting('favicon_url') }}" type="image/x-icon">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Async load fonts to prevent render blocking --}}
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"></noscript>
    
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"></noscript>
    
    {{-- Defer GSAP to prevent render blocking --}}
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/ScrollTrigger.min.js"></script>
    
    {{-- Custom Front Head Code from Settings --}}
    {!! setting('front_head_code', '') !!}
</head>
<body class="bg-[#050505] text-white font-display overflow-x-hidden">
    @include('partials.header')

    <!-- Hero Section (Minimalist & Typography) -->
    <div class="relative min-h-screen w-full flex flex-col justify-center items-center hero-section bg-[#050505] overflow-hidden py-20 lg:py-24">
        
        <!-- Subtle Background Grid -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        
        <!-- Center Spotlight -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-electric-blue/10 rounded-full blur-[150px]"></div>

        <div class="relative z-10 w-full max-w-5xl px-4 flex flex-col items-center text-center">
            
            <!-- Badge -->
            <div class="inline-flex items-center px-5 py-2 rounded-full border border-gray-800 bg-gray-900/50 backdrop-blur-sm mb-8 hero-animate-in">
                <span class="text-sm font-mono text-gray-400 tracking-wider">THE #1 URL SHORTENER</span>
            </div>

            <!-- Massive Typography -->
            <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl xl:text-9xl font-bold tracking-tighter leading-none mb-6 md:mb-8 text-white hero-animate-in-delay-1">
                SHRINK.<br/>
                <span class="text-gray-600 transition-colors duration-500 hover:text-white cursor-default">SHARE.</span><br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-electric-blue to-bright-magenta animate-gradient-x">EARN.</span>
            </h1>

            <p class="text-lg sm:text-xl md:text-2xl text-gray-400 mb-8 md:mb-12 max-w-2xl font-light hero-animate-in-delay-2 px-4">
                Monetize your traffic with the highest paying rates in the market. Simple, fast, and secure.
            </p>

            <!-- Minimalist Form (Functional) -->
            <div x-data="{ 
                url: '', 
                shortened: '', 
                loading: false, 
                error: '',
                copied: false,
                submit() {
                    if(!this.url) return;
                    this.loading = true;
                    this.error = '';
                    this.shortened = '';
                    this.copied = false;
                    fetch('{{ route('guest.shorten') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ url: this.url })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.loading = false;
                        if (data.success) {
                            this.shortened = data.short_link;
                        } else {
                            this.error = data.message || 'Something went wrong';
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        this.error = 'Network error. Please try again.';
                        console.error(err);
                    });
                },
                copyToClipboard() {
                    navigator.clipboard.writeText(this.shortened);
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2000);
                }
            }" class="w-full max-w-2xl mx-auto lg:mx-0 hero-animate-in-delay-3 relative z-30">
                
                <div class="bg-white/5 border border-white/10 p-2 rounded-full backdrop-blur-md shadow-2xl hover:border-white/20 transition-colors duration-300">
                    <form @submit.prevent="submit" class="flex w-full relative">
                        <input x-model="url" type="url" placeholder="Paste your long link here..." required 
                            class="flex-grow bg-transparent border-none text-white placeholder-gray-500 text-lg px-8 py-4 focus:outline-none focus:ring-0" />
                        <button type="submit" :disabled="loading" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-white text-black hover:bg-gray-200 font-bold text-lg py-3 px-8 rounded-full transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[120px]">
                            <span x-show="!loading">Shorten</span>
                            <span x-show="loading" class="inline-block animate-spin rounded-full h-5 w-5 border-2 border-gray-600 border-t-black"></span>
                        </button>
                    </form>
                </div>

                <!-- Result Display -->
                <div x-cloak x-show="shortened" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="mt-4 p-4 bg-green-500/10 border border-green-500/20 rounded-2xl flex items-center justify-between backdrop-blur-md">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <span class="material-symbols-outlined text-green-400">check_circle</span>
                        <a :href="shortened" target="_blank" class="text-green-400 font-mono font-bold text-lg truncate hover:underline" x-text="shortened"></a>
                    </div>
                    <button @click="copyToClipboard()" class="p-2 hover:bg-green-500/20 rounded-lg transition-colors text-green-400 flex items-center gap-2" title="Copy to Clipboard">
                        <span x-show="!copied" class="material-symbols-outlined">content_copy</span>
                        <span x-show="copied" class="material-symbols-outlined">check</span>
                        <span x-show="copied" class="text-sm font-medium">Copied!</span>
                    </button>
                </div>
                
                <!-- Error Display -->
                <div x-cloak x-show="error" x-transition class="mt-4 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 text-sm flex items-center gap-3 backdrop-blur-md">
                    <span class="material-symbols-outlined">error</span>
                    <span x-text="error"></span>
                </div>
            </div>

        </div>
    </div>

    <!-- Stats Section (Minimalist Ghost Numbers) -->
    <div class="bg-[#050505] py-24 sm:py-32 px-4 sm:px-8 relative border-t border-white/5" id="stats-section">
        <!-- Background Grid -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
        
        <div class="max-w-7xl mx-auto px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 text-center md:text-left">
                <div class="gsap-reveal">
                    <p class="text-sm font-mono text-gray-500 mb-4 tracking-[0.2em] uppercase">Total Links Shortened</p>
                    <p class="text-6xl md:text-9xl font-bold text-white tracking-tighter" id="links-counter">28M+</p>
                </div>
                <div class="gsap-reveal" style="transition-delay: 100ms;">
                    <p class="text-sm font-mono text-gray-500 mb-4 tracking-[0.2em] uppercase">Paid to Creators</p>
                    <p class="text-6xl md:text-9xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-electric-blue to-bright-magenta tracking-tighter" id="earnings-counter">$1.2M+</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Everything You Need (Bento Grid) -->
    <div class="bg-[#050505] relative py-24 sm:py-32 px-4 sm:px-8 overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="mb-24 text-left gsap-reveal">
                 <h2 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    EVERYTHING<br/>
                    <span class="text-gray-700">YOU NEED.</span>
                </h2>
                <p class="text-xl text-gray-400 max-w-2xl font-light">
                    A suite of powerful tools designed to maximize your revenue and simplify your workflow.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Item 1 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal flex flex-col justify-between h-full">
                    <div>
                        <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">monetization_on</span>
                        <h3 class="text-3xl font-bold text-white mb-4">Maximum Monetization</h3>
                        <p class="text-gray-400 text-lg leading-relaxed">We offer the highest CPM rates in the industry, optimized for every country and device type. Smart algorithms ensure you get the best paying ads.</p>
                    </div>
                </div>
                
                <!-- Item 2 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group relative overflow-hidden gsap-reveal flex flex-col justify-between h-full" style="transition-delay: 100ms;">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-bright-magenta/20 blur-[100px]"></div>
                    <div>
                        <span class="material-symbols-outlined text-5xl text-bright-magenta mb-6 group-hover:scale-110 transition-transform relative z-10">analytics</span>
                        <h3 class="text-3xl font-bold text-white mb-4 relative z-10">Real-Time Analytics</h3>
                        <p class="text-gray-400 relative z-10 text-lg leading-relaxed">Detailed insights into your traffic sources, device types, and earnings in real-time. Make data-driven decisions.</p>
                    </div>
                </div>
                
                <!-- Item 3 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal" style="transition-delay: 200ms;">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-6 group-hover:text-white transition-colors">bolt</span>
                    <h3 class="text-2xl font-bold text-white mb-3">Fast Payouts</h3>
                    <p class="text-gray-400 leading-relaxed">Daily withdrawals via PayPal, Crypto, and more. No more waiting for your hard-earned money.</p>
                </div>
                
                <!-- Item 4 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal" style="transition-delay: 300ms;">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-6 group-hover:text-white transition-colors">api</span>
                    <h3 class="text-2xl font-bold text-white mb-3">Developer API</h3>
                    <p class="text-gray-400 leading-relaxed">Robust API for seamless integration into your applications. Automate your workflow effortlessly.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works (Steps) -->
    <div class="bg-[#050505] py-24 sm:py-32 px-4 sm:px-8 relative border-t border-white/5">
        <div class="max-w-7xl mx-auto">
            <div class="mb-20 text-left gsap-reveal">
                 <h2 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    SIMPLE<br/>
                    <span class="text-gray-700">STEPS.</span>
                </h2>
            </div>
            
            <div class="relative">
                <!-- Connecting Line for Desktop -->
                <div class="hidden md:block absolute top-12 left-[16%] right-[16%] h-0.5 bg-gray-800 z-0"></div>
                <div class="hidden md:block absolute top-12 left-[16%] right-[16%] h-0.5 bg-gradient-to-r from-electric-blue to-bright-magenta z-0 opacity-50"></div>
                
                <div class="relative grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center text-center group gsap-reveal">
                        <div class="relative mb-8">
                            <div class="absolute inset-0 bg-electric-blue blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-300 rounded-full"></div>
                            <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gray-900 border-2 border-electric-blue relative z-10 shadow-[0_0_15px_rgba(0,191,255,0.3)] group-hover:scale-110 transition-transform duration-300">
                                <span class="material-symbols-outlined text-5xl text-electric-blue">create</span>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-electric-blue rounded-full flex items-center justify-center text-black font-bold border-2 border-gray-900 z-20">1</div>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Create an Account</h3>
                        <p class="text-gray-400 leading-relaxed px-4">Sign up for a free account in seconds. All you need is an email to get started.</p>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center text-center group gsap-reveal" style="transition-delay: 200ms;">
                        <div class="relative mb-8">
                            <div class="absolute inset-0 bg-white blur-xl opacity-10 group-hover:opacity-30 transition-opacity duration-300 rounded-full"></div>
                            <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gray-900 border-2 border-white/50 relative z-10 shadow-[0_0_15px_rgba(255,255,255,0.1)] group-hover:scale-110 transition-transform duration-300">
                                <span class="material-symbols-outlined text-5xl text-white">link</span>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-white text-black rounded-full flex items-center justify-center font-bold border-2 border-gray-900 z-20">2</div>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Shorten Your Link</h3>
                        <p class="text-gray-400 leading-relaxed px-4">Paste your long URL into our tool to create a shortened, monetized link instantly.</p>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center text-center group gsap-reveal" style="transition-delay: 400ms;">
                        <div class="relative mb-8">
                            <div class="absolute inset-0 bg-bright-magenta blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-300 rounded-full"></div>
                            <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gray-900 border-2 border-bright-magenta relative z-10 shadow-[0_0_15px_rgba(255,0,255,0.3)] group-hover:scale-110 transition-transform duration-300">
                                <span class="material-symbols-outlined text-5xl text-bright-magenta">paid</span>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-bright-magenta rounded-full flex items-center justify-center text-white font-bold border-2 border-gray-900 z-20">3</div>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Earn Money</h3>
                        <p class="text-gray-400 leading-relaxed px-4">Share your new link and get paid for every click based on our high CPM rates.</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-20 text-center gsap-reveal">
                <a class="inline-flex items-center gap-2 bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold py-4 px-10 rounded-full hover:scale-105 hover:shadow-[0_0_20px_rgba(0,191,255,0.5)] transition-all duration-300 text-lg group" href="{{ route('register') }}">
                    <span>Start Monetizing Now</span>
                    <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Ad Formats -->
    <div class="bg-[#050505] py-24 sm:py-32 px-8 relative border-t border-white/5">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="mb-16 text-left gsap-reveal">
                 <h2 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    AD<br/>
                    <span class="text-gray-700">FORMATS.</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Interstitial -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">aspect_ratio</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Interstitial</h3>
                    <p class="text-gray-400 leading-relaxed">Full-page ads that appear between page transitions. High CPM, high engagement.</p>
                </div>
                <!-- Banner -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal" style="transition-delay: 100ms;">
                    <span class="material-symbols-outlined text-5xl text-bright-magenta mb-6 group-hover:scale-110 transition-transform">ad_units</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Banner Ads</h3>
                    <p class="text-gray-400 leading-relaxed">Traditional banner ads in various sizes. Non-intrusive and user-friendly.</p>
                </div>
                <!-- Pop-under -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal" style="transition-delay: 200ms;">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">open_in_new</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Pop-under</h3>
                    <p class="text-gray-400 leading-relaxed">Ads that open in a new window behind the current browser window.</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Testimonials Section -->
    <div class="bg-[#050505] relative py-24 sm:py-32 px-4 sm:px-8 border-t border-white/5">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="mb-16 text-left gsap-reveal">
                 <h2 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    USER<br/>
                    <span class="text-gray-700">STORIES.</span>
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 opacity-80 group-hover:scale-110 transition-transform">format_quote</span>
                    <p class="text-gray-400 flex-grow italic leading-relaxed text-lg mb-6">"This is hands-down the best link monetization service I've ever used. The CPM rates are fantastic, and the dashboard is incredibly intuitive. I saw my earnings double in the first month!"</p>
                    <div class="flex items-center pt-6 border-t border-white/5">
                        <img alt="User avatar" class="w-12 h-12 rounded-full mr-4 border-2 border-electric-blue" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCUA-JqtWZLz752Xs5rAxzxIPHcGqikmXdoUr2DdCIL6cZRZ067lEMc7HP5vjx_41MyK29dOzneCCOPJF_htoEHsEKOLkkWZqv6NaZigLi-BmU3G5t1TD-AbzgUnYL3aEvOx8Y2mZEY_pJ4E7obYCWLmtPYynTR0_nlKEExjq7odZu7XtEkM3lSRSrT0BfaIE_GG9jxdYq5yl_umK5lQQVQEZM63p3nSdG0VhvMdM5dS6_yF99E3UBTLMDJt1dS1zyPddPLLbgY=s88" width="48" height="48" loading="lazy" decoding="async"/>
                        <div>
                            <p class="font-bold text-white">Sarah J.</p>
                            <p class="text-sm text-gray-500">Content Creator</p>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal" style="transition-delay: 100ms;">
                    <span class="material-symbols-outlined text-5xl text-bright-magenta mb-6 opacity-80 group-hover:scale-110 transition-transform">format_quote</span>
                    <p class="text-gray-400 flex-grow italic leading-relaxed text-lg mb-6">"The API integration was a breeze. We've automated our entire link shortening process and it's been a game-changer for our business. Reliable, fast, and profitable."</p>
                    <div class="flex items-center pt-6 border-t border-white/5">
                        <img alt="User avatar" class="w-12 h-12 rounded-full mr-4 border-2 border-bright-magenta" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC1xjircuAW9GDYcKX8HKmJv2_qFhZoDPQcIy5quRBbtaDQDCnWzhLdJV1vb7b0aRS5LEQXwEhQ1YvfXhbRn4fXXdSpVQFvlEkDvEKW4vw7K3X7tBN5nEli0lSMK0PXQE47GxwlvW90njRyD2wvhWJesfrHHF025GlrzNs_yuWdrXxScWgedCLai3n45-XMrwZT73wP7wVkb9fSya_MyYsmokITzB_Pd2gYwG_m0LniDN_KHaxAUETbbVtBEECChpMC5r3b877h=s88" width="48" height="48" loading="lazy" decoding="async"/>
                        <div>
                            <p class="font-bold text-white">Mike R.</p>
                            <p class="text-sm text-gray-500">App Developer</p>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal" style="transition-delay: 200ms;">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 opacity-80 group-hover:scale-110 transition-transform">format_quote</span>
                    <p class="text-gray-400 flex-grow italic leading-relaxed text-lg mb-6">"I love the global reach and competitive rates for my country. Payouts are always on time, and the support team is genuinely helpful. Highly recommended for anyone looking to monetize their traffic."</p>
                    <div class="flex items-center pt-6 border-t border-white/5">
                        <img alt="User avatar" class="w-12 h-12 rounded-full mr-4 border-2 border-electric-blue" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfNnaC4Spa9QmjB_AYXhcX-LhOgKO33Up1x47Wk-PP097jgAVzQ3FEi9OhNaOyWPUAcgGQV-LrqgpA0FVGIl70bPA-E1rL5OUVVhwzt1Ax5ufO5lTkJxec-JRbsiDN5Lii-L62c7KEIqJxSBiOLSehLGAiYwJQkYloKgIwRNcU7EdvrHPZBytw0a8FZ8W9ueYg9By-bYeECd_BpI02YxwYa67Z2ACsZTpx4cgyJ4leZbHenlRs0vzbdrjHaKAWdvKFortt3d2D=s88" width="48" height="48" loading="lazy" decoding="async"/>
                        <div>
                            <p class="font-bold text-white">Aisha K.</p>
                            <p class="text-sm text-gray-500">Blogger</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Slant Divider -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-20">
            <svg class="relative block w-[calc(100%+1.3px)] h-[50px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M1200 120L0 16.48 0 0 1200 0 1200 120z" class="fill-background-dark"></path>
            </svg>
        </div>
    </div>

    <!-- Live Activity Feed (Marquee) -->
    <div class="bg-background-dark py-12 relative overflow-hidden border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4">
             <div class="flex items-center gap-4 mb-8 justify-center opacity-70">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-sm font-mono text-green-400 uppercase tracking-widest">Live Activity</span>
            </div>
        </div>
        
        <div class="relative w-full overflow-hidden">
            <div class="flex gap-8 animate-scroll-left w-max hover:pause">
                <!-- Duplicated items for infinite scroll effect -->
                @for ($i = 0; $i < 4; $i++)
                    <div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-6 py-3 whitespace-nowrap">
                        <span class="material-symbols-outlined text-electric-blue">link</span>
                        <p class="text-sm text-gray-300"><span class="font-bold text-white">User from Germany</span> just shortened a link</p>
                    </div>
                    <div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-6 py-3 whitespace-nowrap">
                        <span class="material-symbols-outlined text-bright-magenta">payments</span>
                        <p class="text-sm text-gray-300"><span class="font-bold text-white">User from USA</span> just earned <span class="font-semibold text-bright-magenta">$0.05</span></p>
                    </div>
                    <div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-6 py-3 whitespace-nowrap">
                        <span class="material-symbols-outlined text-electric-blue">link</span>
                        <p class="text-sm text-gray-300"><span class="font-bold text-white">User from Australia</span> just shortened a link</p>
                    </div>
                    <div class="flex items-center gap-4 bg-gray-900/50 border border-gray-800 rounded-full px-6 py-3 whitespace-nowrap">
                        <span class="material-symbols-outlined text-green-400">account_balance_wallet</span>
                        <p class="text-sm text-gray-300">Payout of <span class="font-bold text-green-400">$52.10</span> processed</p>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Global CPM Rates (Data Terminal) -->
    <div class="bg-[#050505] py-24 sm:py-32 px-4 sm:px-8 relative border-t border-white/5">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="mb-16 text-left gsap-reveal">
                 <h2 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    GLOBAL<br/>
                    <span class="text-gray-700">RATES.</span>
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gsap-reveal">
                <!-- Country Card 1 -->
                <div class="flex items-center justify-between p-6 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-electric-blue/30 transition-all duration-300 group">
                    <div class="flex items-center gap-4">
                        <span class="fi fi-us fis rounded-lg shadow-lg grayscale group-hover:grayscale-0 transition-all duration-300" style="width: 40px; height: 30px;"></span>
                        <div>
                            <h4 class="text-white font-bold text-lg">United States</h4>
                            <p class="text-gray-500 text-sm">North America</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-electric-blue">$22.00</p>
                        <p class="text-xs text-gray-500 tracking-wider">PER 1000</p>
                    </div>
                </div>

                <!-- Country Card 2 -->
                <div class="flex items-center justify-between p-6 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-electric-blue/30 transition-all duration-300 group">
                    <div class="flex items-center gap-4">
                        <span class="fi fi-gb fis rounded-lg shadow-lg grayscale group-hover:grayscale-0 transition-all duration-300" style="width: 40px; height: 30px;"></span>
                        <div>
                            <h4 class="text-white font-bold text-lg">United Kingdom</h4>
                            <p class="text-gray-500 text-sm">Europe</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-electric-blue">$20.50</p>
                        <p class="text-xs text-gray-500 tracking-wider">PER 1000</p>
                    </div>
                </div>

                <!-- Country Card 3 -->
                <div class="flex items-center justify-between p-6 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-electric-blue/30 transition-all duration-300 group">
                    <div class="flex items-center gap-4">
                        <span class="fi fi-ca fis rounded-lg shadow-lg grayscale group-hover:grayscale-0 transition-all duration-300" style="width: 40px; height: 30px;"></span>
                        <div>
                            <h4 class="text-white font-bold text-lg">Canada</h4>
                            <p class="text-gray-500 text-sm">North America</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-electric-blue">$19.80</p>
                        <p class="text-xs text-gray-500 tracking-wider">PER 1000</p>
                    </div>
                </div>

                <!-- Country Card 4 -->
                <div class="flex items-center justify-between p-6 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-bright-magenta/30 transition-all duration-300 group">
                    <div class="flex items-center gap-4">
                        <span class="fi fi-au fis rounded-lg shadow-lg grayscale group-hover:grayscale-0 transition-all duration-300" style="width: 40px; height: 30px;"></span>
                        <div>
                            <h4 class="text-white font-bold text-lg">Australia</h4>
                            <p class="text-gray-500 text-sm">Oceania</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-bright-magenta">$18.75</p>
                        <p class="text-xs text-gray-500 tracking-wider">PER 1000</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-center gsap-reveal">
                <a class="inline-block border border-white/20 hover:bg-white/10 text-white font-bold py-4 px-10 rounded-full transition-all duration-300 tracking-wider text-sm" href="{{ route('payout.rates') }}">
                    VIEW ALL COUNTRIES
                </a>
            </div>
        </div>
    </div>

    <!-- Earning Potential Calculator -->
    <div class="bg-[#050505] py-24 sm:py-32 px-8 relative overflow-hidden border-t border-white/5">
        <div class="max-w-5xl mx-auto relative z-10">
            <div class="mb-16 text-left gsap-reveal">
                 <h2 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    ESTIMATE<br/>
                    <span class="text-gray-700">EARNINGS.</span>
                </h2>
            </div>
            
            <div class="bg-gradient-to-br from-gray-800/80 to-gray-900/80 backdrop-blur-md border border-gray-700/50 rounded-3xl p-8 md:p-12 shadow-2xl gsap-reveal">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <label class="block text-lg text-gray-300 mb-6 font-medium" for="clicks-slider">Monthly Clicks</label>
                        <div class="relative">
                            <input class="w-full h-3 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-electric-blue" id="clicks-slider" max="50000" min="1000" step="1000" type="range" value="10000"/>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500 mt-4 font-mono">
                            <span>1,000</span>
                            <span class="font-bold text-electric-blue text-xl" id="clicks-display">10,000</span>
                            <span>50,000+</span>
                        </div>
                    </div>
                    <div class="text-center bg-gray-900/50 rounded-2xl p-8 border border-gray-700/30">
                        <p class="text-gray-400 text-lg mb-2 uppercase tracking-widest text-xs">Estimated Monthly Earnings</p>
                        <p class="text-5xl md:text-7xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta tracking-tight" id="calculator-earnings">$50.00</p>
                    </div>
                </div>
                <div class="mt-12 text-center border-t border-gray-700/50 pt-8">
                    <a class="inline-block bg-white text-gray-900 font-bold py-4 px-10 rounded-full hover:bg-gray-200 hover:scale-105 transition-all duration-300 text-lg shadow-lg" href="{{ route('register') }}">Start Earning Now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Powerful Features -->
    <div class="bg-[#050505] py-24 sm:py-32 px-8 relative border-t border-white/5">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="mb-16 text-left gsap-reveal">
                 <h2 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    POWERFUL<br/>
                    <span class="text-gray-700">FEATURES.</span>
                </h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">monetization_on</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Monetization</h3>
                    <p class="text-gray-400 leading-relaxed">Turn your links into a passive revenue stream. Effortlessly monetize every click with our smart ad technology.</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal" style="transition-delay: 100ms;">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">monitoring</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Real-time Analytics</h3>
                    <p class="text-gray-400 leading-relaxed">Track every click and analyze your performance with a stunning visual dashboard. Data at your fingertips.</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal" style="transition-delay: 200ms;">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">security</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Ironclad Security</h3>
                    <p class="text-gray-400 leading-relaxed">Your links are protected with our robust, cutting-edge security measures. Safety first, always.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Partnership Opportunities -->
    <div class="bg-[#050505] py-24 sm:py-32 px-8 relative border-t border-white/5">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
        <div class="max-w-5xl mx-auto relative z-10">
            <div class="mb-16 text-left gsap-reveal">
                 <h2 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    PARTNER<br/>
                    <span class="text-gray-700">WITH US.</span>
                </h2>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Affiliate Card -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 flex flex-col items-start group gsap-reveal">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">campaign</span>
                    <h3 class="text-2xl font-bold mb-3 text-white">Affiliate Program</h3>
                    <p class="text-gray-400 mb-6 flex-grow leading-relaxed">Join our affiliate program and earn commissions by referring new users to our platform. Get access to marketing materials and a dedicated support team.</p>
                    <a class="font-bold text-electric-blue hover:text-white transition-colors duration-300 flex items-center group mt-auto" href="{{ route('register') }}">
                        Become an Affiliate <span class="material-symbols-outlined ml-2 transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
                <!-- API Card -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 flex flex-col items-start group gsap-reveal" style="transition-delay: 100ms;">
                    <span class="material-symbols-outlined text-5xl text-bright-magenta mb-6 group-hover:scale-110 transition-transform">business_center</span>
                    <h3 class="text-2xl font-bold mb-3 text-white">API Integration</h3>
                    <p class="text-gray-400 mb-6 flex-grow leading-relaxed">Integrate our powerful link monetization engine into your own application or service with our flexible and robust API. Perfect for developers and businesses.</p>
                    <a class="font-bold text-bright-magenta hover:text-white transition-colors duration-300 flex items-center group mt-auto" href="{{ route('api.documentation') }}">
                        Explore API Docs <span class="material-symbols-outlined ml-2 transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-[#050505] py-24 sm:py-32 px-4 sm:px-8 relative overflow-hidden border-t border-white/5">
        <div class="max-w-4xl mx-auto relative z-10">
            <div class="mb-16 text-left gsap-reveal">
                 <h2 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    COMMON<br/>
                    <span class="text-gray-700">QUESTIONS.</span>
                </h2>
            </div>
            <div class="space-y-6 gsap-reveal" x-data="{ open: 0 }">
                <div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden transition-all duration-300 hover:border-gray-700">
                    <button @click="open = open === 1 ? 0 : 1" class="w-full text-left p-6 flex justify-between items-center hover:bg-gray-800/50 transition-colors duration-300">
                        <h3 class="text-xl font-semibold text-white">What is a monetized link shortener?</h3>
                        <span :class="{'rotate-45': open === 1}" class="material-symbols-outlined text-electric-blue transform transition-transform duration-300">add</span>
                    </button>
                    <div class="px-6 pb-6 pt-2 text-gray-400" x-show="open === 1" x-collapse>
                        <p>A monetized link shortener is a service that transforms your long URLs into shorter, more manageable links. When a user clicks on this new link, they are shown a brief advertisement before being redirected to the original destination. You, as the link creator, earn a portion of the advertising revenue for each valid click.</p>
                    </div>
                </div>
                <div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden transition-all duration-300 hover:border-gray-700">
                    <button @click="open = open === 2 ? 0 : 2" class="w-full text-left p-6 flex justify-between items-center hover:bg-gray-800/50 transition-colors duration-300">
                        <h3 class="text-xl font-semibold text-white">How much can I earn?</h3>
                        <span :class="{'rotate-45': open === 2}" class="material-symbols-outlined text-bright-magenta transform transition-transform duration-300">add</span>
                    </button>
                    <div class="px-6 pb-6 pt-2 text-gray-400" x-show="open === 2" x-collapse>
                        <p>Your earnings depend on several factors, primarily the CPM (Cost Per Mille, or per 1000 views) rate for the country where the click originates. We offer competitive rates for traffic from all over the world.</p>
                    </div>
                </div>
                <div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden transition-all duration-300 hover:border-gray-700">
                    <button @click="open = open === 3 ? 0 : 3" class="w-full text-left p-6 flex justify-between items-center hover:bg-gray-800/50 transition-colors duration-300">
                        <h3 class="text-xl font-semibold text-white">What are the payment methods?</h3>
                        <span :class="{'rotate-45': open === 3}" class="material-symbols-outlined text-electric-blue transform transition-transform duration-300">add</span>
                    </button>
                    <div class="px-6 pb-6 pt-2 text-gray-400" x-show="open === 3" x-collapse>
                        <p>We offer a variety of payment methods including PayPal, Payoneer, and Bank Transfer. Payments are processed on a monthly basis.</p>
                    </div>
                </div>
                 <div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden transition-all duration-300 hover:border-gray-700">
                    <button @click="open = open === 4 ? 0 : 4" class="w-full text-left p-6 flex justify-between items-center hover:bg-gray-800/50 transition-colors duration-300">
                        <h3 class="text-xl font-semibold text-white">Are there any restrictions on links?</h3>
                        <span :class="{'rotate-45': open === 4}" class="material-symbols-outlined text-bright-magenta transform transition-transform duration-300">add</span>
                    </button>
                    <div class="px-6 pb-6 pt-2 text-gray-400" x-show="open === 4" x-collapse>
                        <p>Yes. We strictly prohibit the shortening of links that point to adult content, malware, hate speech, illegal activities, or any other content that violates our Terms of Service.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Section -->
    <div class="bg-[#050505] py-24 px-8 border-t border-white/5 relative">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
        <div class="max-w-5xl mx-auto text-left gsap-reveal relative z-10">
            <div class="mb-16">
                 <h2 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-6 leading-none">
                    SECURE<br/>
                    <span class="text-gray-700">PLATFORM.</span>
                </h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6 text-left">
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">lock</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Encryption</h3>
                    <p class="text-gray-400 leading-relaxed">All data is secured with industry-standard TLS encryption protocols.</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal" style="transition-delay: 100ms;">
                    <span class="material-symbols-outlined text-5xl text-bright-magenta mb-6 group-hover:scale-110 transition-transform">shield</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Fraud Protection</h3>
                    <p class="text-gray-400 leading-relaxed">Advanced AI systems actively monitor and prevent fraudulent activities.</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10 hover:border-white/20 transition-all duration-300 group gsap-reveal" style="transition-delay: 200ms;">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">privacy_tip</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Privacy First</h3>
                    <p class="text-gray-400 leading-relaxed">Fully GDPR and CCPA compliant. Your privacy is our top priority.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-[#050505] py-12 border-t border-white/5 overflow-hidden">
        <div class="max-w-7xl mx-auto px-8">
            <p class="text-center text-sm font-mono text-gray-600 mb-8 uppercase tracking-widest">Withdraw Your Money Via</p>
            <div class="flex flex-wrap justify-center gap-8 md:gap-16 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="flex items-center gap-2 text-xl md:text-2xl font-bold text-white hover:text-electric-blue transition-colors cursor-default"><span class="material-symbols-outlined">payments</span> PayPal</div>
                <div class="flex items-center gap-2 text-xl md:text-2xl font-bold text-white hover:text-electric-blue transition-colors cursor-default"><span class="material-symbols-outlined">currency_bitcoin</span> Bitcoin</div>
                <div class="flex items-center gap-2 text-xl md:text-2xl font-bold text-white hover:text-electric-blue transition-colors cursor-default"><span class="material-symbols-outlined">attach_money</span> USDT</div>
                <div class="flex items-center gap-2 text-xl md:text-2xl font-bold text-white hover:text-electric-blue transition-colors cursor-default"><span class="material-symbols-outlined">account_balance</span> Payoneer</div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Wait for GSAP to be available (since it's deferred)
        function initAnimations() {
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                // GSAP not loaded yet, try again shortly
                setTimeout(initAnimations, 50);
                return;
            }
            
            gsap.registerPlugin(ScrollTrigger);

            // Section Scroll Animations
            gsap.utils.toArray('.gsap-reveal').forEach(section => {
                gsap.from(section, {
                    scrollTrigger: {
                        trigger: section,
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    y: 50,
                    opacity: 0,
                    duration: 1,
                    ease: "power3.out"
                });
            });

            // Counters
            const counters = { links: 0, earnings: 0 };
            const linksCounter = document.getElementById('links-counter');
            const earningsCounter = document.getElementById('earnings-counter');
            
            if(linksCounter && earningsCounter) {
                ScrollTrigger.create({
                    trigger: "#stats-section",
                    start: "top 80%",
                    once: true,
                    onEnter: () => {
                         gsap.to(counters, {
                            duration: 2.5,
                            links: 28453987,
                            onUpdate: () => linksCounter.innerHTML = Math.round(counters.links).toLocaleString(),
                            ease: "power2.out"
                        });
                        gsap.to(counters, {
                            duration: 2.5,
                            earnings: 142269.93,
                            onUpdate: () => earningsCounter.innerHTML = counters.earnings.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                            ease: "power2.out"
                        });
                    }
                });
            }

            // Slider Logic
            const slider = document.getElementById('clicks-slider');
            const clicksDisplay = document.getElementById('clicks-display');
            const earningsDisplay = document.getElementById('calculator-earnings');
            
            if(slider) {
                slider.addEventListener('input', (e) => {
                    const clicks = parseInt(e.target.value);
                    const earnings = (clicks / 1000) * 5; // Assuming $5 average CPM
                    
                    clicksDisplay.innerText = clicks.toLocaleString();
                    earningsDisplay.innerText = '$' + earnings.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                });
            }
            
            // Generic form loading
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                // Skip Alpine.js handled forms to avoid conflicts
                if (form.hasAttribute('x-data') || form.closest('[x-data]')) return;

                form.addEventListener('submit', function() {
                    const btn = form.querySelector('button[type="submit"]');
                    if(btn) {
                        btn.disabled = true;
                        btn.innerHTML = 'Processing...';
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                    }
                });
            });
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAnimations);
        } else {
            initAnimations();
        }
    </script>
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        @keyframes float-1 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(10deg); }
        }
        @keyframes float-2 {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(20px) translateX(10px); }
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        .animate-float-1 {
            animation: float-1 5s ease-in-out infinite;
        }
        .animate-float-2 {
            animation: float-2 7s ease-in-out infinite;
        }
        .perspective-1000 {
            perspective: 1000px;
        }

        @keyframes gradient-x {
            0%, 100% {
                background-size: 200% 200%;
                background-position: left center;
            }
            50% {
                background-size: 200% 200%;
                background-position: right center;
            }
        }
        .animate-gradient-x {
            animation: gradient-x 15s ease infinite;
        }
        
        @keyframes scroll-left {
            from { transform: translateX(0); }
            to { transform: translateX(-100%); }
        }
        .animate-scroll-left {
            animation: scroll-left 30s linear infinite;
        }
        .pause:hover {
            animation-play-state: paused;
        }
        
        .animate-pulse-slow {
             animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #111827; 
        }
        ::-webkit-scrollbar-thumb {
            background: #374151; 
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4B5563; 
        }

        /* Gradient Text Helper */
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to right, #00BFFF, #FF00FF);
        }
    </style>
    
{{-- Cookie Consent Banner - Pure JS (no Livewire) --}}
@if(setting('display_cookie_notification', true))
<div x-data="{ 
    show: false,
    init() {
        this.show = !this.getCookie('cookie_consent');
    },
    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    },
    accept() {
        const d = new Date();
        d.setTime(d.getTime() + (365*24*60*60*1000));
        document.cookie = 'cookie_consent=accepted;expires=' + d.toUTCString() + ';path=/';
        this.show = false;
    }
}" x-cloak>
    <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="fixed bottom-0 left-0 right-0 z-50 p-4 bg-gray-900/95 backdrop-blur-sm border-t border-gray-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-300">
                <p>We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.</p>
            </div>
            <div class="flex gap-3">
                <button @click="accept()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Accept
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Custom Footer Code from Settings --}}
{!! setting('footer_code', '') !!}
</body>
</html>
