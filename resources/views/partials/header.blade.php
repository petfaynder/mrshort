<header x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 right-0 z-50 bg-hero-bg/80 backdrop-blur-md border-b border-gray-800 transition-all duration-300" id="main-header">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <div class="flex items-center">
                <a class="flex-shrink-0" href="{{ url('/') }}">
                    <span class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta tracking-tight" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">{{ config('app.name', 'MrShort') }}</span>
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-6">
                    <a class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-base font-medium transition-colors" style="font-family: 'Inter', 'Space Grotesk', sans-serif;" href="{{ route('payout.rates') }}">Payout Rates</a>
                    <a class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-base font-medium transition-colors" style="font-family: 'Inter', 'Space Grotesk', sans-serif;" href="{{ route('api.documentation') }}">API</a>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                @auth
                    <a class="hidden md:block text-gray-300 hover:text-white px-3 py-2 rounded-md text-base font-medium transition-colors" style="font-family: 'Inter', 'Space Grotesk', sans-serif;" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a class="hidden md:block text-gray-300 hover:text-white px-3 py-2 rounded-md text-base font-medium transition-colors" style="font-family: 'Inter', 'Space Grotesk', sans-serif;" href="{{ route('login') }}">Login</a>
                    <a class="hidden md:inline-block bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold py-2.5 px-6 rounded-full hover:scale-105 hover:shadow-lg hover:shadow-electric-blue/30 transition-all duration-300 text-base" style="font-family: 'Inter', 'Space Grotesk', sans-serif;" href="{{ route('register') }}">Sign Up</a>
                @endauth
                
                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-electric-blue transition-colors" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <span x-show="!mobileMenuOpen" class="material-symbols-outlined text-2xl">menu</span>
                    <span x-show="mobileMenuOpen" x-cloak class="material-symbols-outlined text-2xl">close</span>
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         x-cloak
         class="md:hidden bg-gray-900/95 backdrop-blur-lg border-b border-gray-800">
        <div class="px-4 py-6 space-y-4">
            <a @click="mobileMenuOpen = false" href="{{ route('payout.rates') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white hover:bg-gray-800/50 transition-all duration-200" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                <span class="material-symbols-outlined text-electric-blue">payments</span>
                <span class="font-medium text-base">Payout Rates</span>
            </a>
            <a @click="mobileMenuOpen = false" href="{{ route('api.documentation') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white hover:bg-gray-800/50 transition-all duration-200" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                <span class="material-symbols-outlined text-bright-magenta">api</span>
                <span class="font-medium text-base">API Documentation</span>
            </a>
            
            <div class="border-t border-gray-800 pt-4 mt-4">
                @auth
                    <a @click="mobileMenuOpen = false" href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white hover:bg-gray-800/50 transition-all duration-200" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                        <span class="material-symbols-outlined text-electric-blue">dashboard</span>
                        <span class="font-medium text-base">Dashboard</span>
                    </a>
                @else
                    <a @click="mobileMenuOpen = false" href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white hover:bg-gray-800/50 transition-all duration-200" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                        <span class="material-symbols-outlined text-gray-400">login</span>
                        <span class="font-medium text-base">Login</span>
                    </a>
                    <a @click="mobileMenuOpen = false" href="{{ route('register') }}" class="mt-3 flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-electric-blue to-bright-magenta text-white font-bold hover:shadow-lg hover:shadow-electric-blue/30 transition-all duration-200 text-base" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                        <span class="material-symbols-outlined">person_add</span>
                        <span>Sign Up Free</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

<style>
    [x-cloak] { display: none !important; }
</style>
