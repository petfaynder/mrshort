<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Payout Rates - MrShort</title>
    <meta name="description" content="MrShort Payout Rates - Check our competitive CPM rates for different countries and start earning today."/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#050505] text-white font-display overflow-x-hidden">
    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <main class="pt-32 pb-24 px-4 sm:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Page Title -->
            <div class="mb-16">
                <h1 class="text-5xl md:text-7xl font-bold tracking-tighter text-white mb-6 leading-none">
                    PAYOUT<br/>
                    <span class="text-gray-700">RATES.</span>
                </h1>
                <p class="text-gray-400 text-lg max-w-2xl">We pay for all legitimate visitors you bring to your links. Check the rates below for different countries and devices.</p>
                <p class="mt-2 text-sm text-gray-500">Rates updated daily</p>
            </div>

            <!-- Feature Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 hover:border-white/20 transition-all duration-300 group">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">account_balance_wallet</span>
                    <h3 class="text-2xl font-bold mb-3 text-white">Instant Payments</h3>
                    <p class="text-gray-400 leading-relaxed">Reach the minimum withdrawal amount and get paid instantly via PayPal, Crypto, or Bank Transfer.</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 hover:border-white/20 transition-all duration-300 group">
                    <span class="material-symbols-outlined text-5xl text-bright-magenta mb-6 group-hover:scale-110 transition-transform">public</span>
                    <h3 class="text-2xl font-bold mb-3 text-white">All Countries Counted</h3>
                    <p class="text-gray-400 leading-relaxed">We count all visitors you send from any country around the world. No traffic goes to waste.</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 hover:border-white/20 transition-all duration-300 group">
                    <span class="material-symbols-outlined text-5xl text-electric-blue mb-6 group-hover:scale-110 transition-transform">group_add</span>
                    <h3 class="text-2xl font-bold mb-3 text-white">Referral Bonus</h3>
                    <p class="text-gray-400 leading-relaxed">Earn 30% of their revenue as a bonus for referring new users to our platform.</p>
                </div>
            </div>

            <!-- Rates Table -->
            <div class="bg-white/5 border border-white/10 rounded-3xl overflow-hidden">
                <!-- Table Header -->
                <div class="hidden md:grid grid-cols-3 gap-4 px-8 py-5 border-b border-white/10 bg-white/5">
                    <div class="font-bold text-white">Country</div>
                    <div class="text-center font-bold text-white">Desktop / 1000 Views</div>
                    <div class="text-center font-bold text-white">Mobile / 1000 Views</div>
                </div>

                <!-- Table Body -->
                <div class="divide-y divide-white/5">
                    <!-- United States -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center px-8 py-5 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="fi fi-us fis rounded shadow-lg" style="width: 40px; height: 30px;"></span>
                            <span class="font-bold text-white text-lg">United States</span>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4 py-2 border-t border-b md:border-none border-white/5">
                            <span class="md:hidden text-gray-500">Desktop Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-electric-blue">
                                <span class="material-symbols-outlined">desktop_windows</span>
                                <span>$22.00</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4">
                            <span class="md:hidden text-gray-500">Mobile Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-bright-magenta">
                                <span class="material-symbols-outlined">phone_iphone</span>
                                <span>$22.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- United Kingdom -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center px-8 py-5 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="fi fi-gb fis rounded shadow-lg" style="width: 40px; height: 30px;"></span>
                            <span class="font-bold text-white text-lg">United Kingdom</span>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4 py-2 border-t border-b md:border-none border-white/5">
                            <span class="md:hidden text-gray-500">Desktop Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-electric-blue">
                                <span class="material-symbols-outlined">desktop_windows</span>
                                <span>$20.50</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4">
                            <span class="md:hidden text-gray-500">Mobile Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-bright-magenta">
                                <span class="material-symbols-outlined">phone_iphone</span>
                                <span>$20.50</span>
                            </div>
                        </div>
                    </div>

                    <!-- Canada -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center px-8 py-5 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="fi fi-ca fis rounded shadow-lg" style="width: 40px; height: 30px;"></span>
                            <span class="font-bold text-white text-lg">Canada</span>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4 py-2 border-t border-b md:border-none border-white/5">
                            <span class="md:hidden text-gray-500">Desktop Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-electric-blue">
                                <span class="material-symbols-outlined">desktop_windows</span>
                                <span>$18.00</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4">
                            <span class="md:hidden text-gray-500">Mobile Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-bright-magenta">
                                <span class="material-symbols-outlined">phone_iphone</span>
                                <span>$18.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Australia -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center px-8 py-5 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="fi fi-au fis rounded shadow-lg" style="width: 40px; height: 30px;"></span>
                            <span class="font-bold text-white text-lg">Australia</span>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4 py-2 border-t border-b md:border-none border-white/5">
                            <span class="md:hidden text-gray-500">Desktop Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-electric-blue">
                                <span class="material-symbols-outlined">desktop_windows</span>
                                <span>$15.75</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4">
                            <span class="md:hidden text-gray-500">Mobile Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-bright-magenta">
                                <span class="material-symbols-outlined">phone_iphone</span>
                                <span>$15.75</span>
                            </div>
                        </div>
                    </div>

                    <!-- Germany -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center px-8 py-5 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="fi fi-de fis rounded shadow-lg" style="width: 40px; height: 30px;"></span>
                            <span class="font-bold text-white text-lg">Germany</span>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4 py-2 border-t border-b md:border-none border-white/5">
                            <span class="md:hidden text-gray-500">Desktop Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-electric-blue">
                                <span class="material-symbols-outlined">desktop_windows</span>
                                <span>$12.50</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4">
                            <span class="md:hidden text-gray-500">Mobile Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-bright-magenta">
                                <span class="material-symbols-outlined">phone_iphone</span>
                                <span>$12.50</span>
                            </div>
                        </div>
                    </div>

                    <!-- France -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center px-8 py-5 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="fi fi-fr fis rounded shadow-lg" style="width: 40px; height: 30px;"></span>
                            <span class="font-bold text-white text-lg">France</span>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4 py-2 border-t border-b md:border-none border-white/5">
                            <span class="md:hidden text-gray-500">Desktop Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-electric-blue">
                                <span class="material-symbols-outlined">desktop_windows</span>
                                <span>$10.90</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4">
                            <span class="md:hidden text-gray-500">Mobile Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-bright-magenta">
                                <span class="material-symbols-outlined">phone_iphone</span>
                                <span>$10.90</span>
                            </div>
                        </div>
                    </div>

                    <!-- All Other Countries -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center px-8 py-5 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 flex items-center justify-center bg-gray-800 rounded shadow-lg">
                                <span class="material-symbols-outlined text-gray-400">language</span>
                            </div>
                            <span class="font-bold text-white text-lg">All Other Countries</span>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4 py-2 border-t border-b md:border-none border-white/5">
                            <span class="md:hidden text-gray-500">Desktop Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-electric-blue">
                                <span class="material-symbols-outlined">desktop_windows</span>
                                <span>$5.00</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between md:justify-center gap-4">
                            <span class="md:hidden text-gray-500">Mobile Rate</span>
                            <div class="flex items-center gap-2 text-xl font-bold text-bright-magenta">
                                <span class="material-symbols-outlined">phone_iphone</span>
                                <span>$5.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="mt-16 text-center">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold py-4 px-10 rounded-full hover:scale-105 hover:shadow-[0_0_20px_rgba(0,191,255,0.5)] transition-all duration-300 text-lg group">
                    <span>Start Earning Now</span>
                    <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')
</body>
</html>
