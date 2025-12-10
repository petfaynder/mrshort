<x-user-dashboard-layout>
    @if (session('status'))
        <div class="flex items-center gap-3 rounded-lg border border-primary/50 bg-primary/10 p-3 text-sm text-primary dark:text-blue-400 mb-6">
            <span class="material-symbols-outlined">check_circle</span>
            <p>{{ session('status') }}</p>
        </div>
    @endif

    {{-- Güvenlik Önerisi ve Reklam Banner'ları --}}
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
        <p>It is very recommended to enable 2 Factor Authentication on your security settings to ensure the security of your account</p>
    </div>
    <div class="bg-gray-200 p-4 mb-6 text-center dark:bg-gray-800 dark:text-gray-300">
        <p>Reklam Alanı</p>
    </div>

    {{-- Duyurular --}}
    <livewire:user.announcements />

    {{-- Gamification Widgets --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Streak Display --}}
        <livewire:user.streak-display />
        
        {{-- Daily Challenges --}}
        <livewire:user.daily-challenges />

        {{-- Weekly Competition --}}
        <livewire:user.weekly-competition />
    </div>

    {{-- Milestone Modal (Global) --}}
    <livewire:user.milestone-modal />

    {{-- URL Kısaltma Formu --}}
    <div data-tutorial="shortener">
        <livewire:user.quick-shortener />
    </div>

    <div data-tutorial="date-filter" class="flex justify-end mb-4">
        <livewire:user.stats-date-filter />
    </div>

    {{-- İstatistik Kartları --}}
    <div data-tutorial="stats">
        <livewire:user.dashboard-stats />
    </div>
    
    {{-- İstatistik Grafiği ve Tablosu --}}
    <div data-tutorial="chart">
        <livewire:user.earnings-chart />
    </div>

    {{-- Hedef ve Ülkeler --}}
    <div data-tutorial="performance">
        <livewire:user.performance-overview />
    </div>

    {{-- Link Yöneticisi --}}
    <div data-tutorial="recent-links">
        <livewire:user.recent-links />
    </div>

    {{-- Optimized Link Suggestions --}}
    <div data-tutorial="suggestions" class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md mb-8">
        <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Optimized Link Suggestions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-start gap-4 p-4 bg-background-light dark:bg-background-dark rounded-lg">
                <div class="mt-1 p-2 bg-green-100 dark:bg-green-900/50 rounded-full"><span class="material-symbols-outlined text-green-500 text-base">trending_up</span></div>
                <div>
                    <h4 class="font-semibold text-heading-light dark:text-heading-dark">High Traffic Potential</h4>
                    <p class="text-sm text-text-light dark:text-text-dark">Your link for "Tech Gadgets 2024" is performing well. Consider creating more content around this topic.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-4 bg-background-light dark:bg-background-dark rounded-lg">
                <div class="mt-1 p-2 bg-blue-100 dark:bg-blue-900/50 rounded-full"><span class="material-symbols-outlined text-primary text-base">public</span></div>
                <div>
                    <h4 class="font-semibold text-heading-light dark:text-heading-dark">Geo-Targeting Tip</h4>
                    <p class="text-sm text-text-light dark:text-text-dark">High CPM in Germany. Try sharing your links in German-speaking forums for higher earnings.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Info & Recent Activity --}}
    <div data-tutorial="payment-activity" class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <livewire:user.payment-summary />
        <livewire:user.recent-activity />
    </div>
</x-user-dashboard-layout>
