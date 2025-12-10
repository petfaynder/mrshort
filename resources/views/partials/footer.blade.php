<footer class="bg-black border-t border-gray-800 py-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <!-- Brand Column -->
            <div class="col-span-1">
                <a class="flex-shrink-0 mb-6 block" href="{{ url('/') }}">
                    <span class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta tracking-tight" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">{{ config('app.name', 'MrShort') }}</span>
                </a>
                <p class="text-gray-400 text-base leading-relaxed mb-6" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                    The smartest way to monetize your links. High CPMs, reliable payments, and premium support.
                </p>
                <div class="flex gap-4">
                    <a href="https://mrshort.io" target="_blank" class="w-11 h-11 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-electric-blue hover:border-electric-blue/50 transition-all duration-300">
                        <span class="material-symbols-outlined text-xl">public</span>
                    </a>
                    <a href="mailto:support@mrshort.io" class="w-11 h-11 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-electric-blue hover:border-electric-blue/50 transition-all duration-300">
                        <span class="material-symbols-outlined text-xl">mail</span>
                    </a>
                </div>
            </div>
            
            <!-- Platform Column -->
            <div>
                <h4 class="text-white font-bold text-lg mb-6" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">Platform</h4>
                <ul class="space-y-4 text-base text-gray-400" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                    <li><a href="{{ route('payout.rates') }}" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">payments</span> Payout Rates
                    </a></li>
                    <li><a href="{{ route('api.documentation') }}" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">api</span> API Documentation
                    </a></li>
                    @auth
                    <li><a href="{{ route('dashboard') }}" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">dashboard</span> Dashboard
                    </a></li>
                    @else
                    <li><a href="{{ route('register') }}" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">person_add</span> Get Started
                    </a></li>
                    @endauth
                </ul>
            </div>
            
            <!-- Legal Column -->
            <div>
                <h4 class="text-white font-bold text-lg mb-6" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">Legal</h4>
                <ul class="space-y-4 text-base text-gray-400" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                    <li><a href="{{ route('privacy.policy') }}" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">privacy_tip</span> Privacy Policy
                    </a></li>
                    <li><a href="{{ route('terms.of.service') }}" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">gavel</span> Terms of Service
                    </a></li>
                    <li><a href="{{ route('cookie.policy') }}" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">cookie</span> Cookie Policy
                    </a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-900 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-500 text-base" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">© {{ date('Y') }} MrShort.io. All rights reserved.</p>
            <div class="flex items-center gap-6 text-base text-gray-500" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                <a href="{{ route('privacy.policy') }}" class="hover:text-gray-300 transition-colors">Privacy</a>
                <span class="text-gray-800">•</span>
                <a href="{{ route('terms.of.service') }}" class="hover:text-gray-300 transition-colors">Terms</a>
                <span class="text-gray-800">•</span>
                <a href="{{ route('cookie.policy') }}" class="hover:text-gray-300 transition-colors">Cookies</a>
            </div>
        </div>
    </div>
</footer>
