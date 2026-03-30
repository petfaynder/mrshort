<x-user-dashboard-layout>
    @if (session('status'))
        <div class="flex items-center gap-3 rounded-lg border border-primary/50 bg-primary/10 p-3 text-sm text-primary dark:text-blue-400 mb-6">
            <span class="material-symbols-outlined">check_circle</span>
            <p>{{ session('status') }}</p>
        </div>
    @endif

    @php
        $activeCampaign = app(\App\Services\CpmCampaignService::class)->getActiveCampaign();
    @endphp

    @if ($activeCampaign)
        <div class="relative w-full mb-8" x-data="{ 
            hovered: false,
            endTime: new Date('{{ $activeCampaign->end_date->format('Y-m-d\TH:i:sP') }}').getTime(),
            d: '00', h: '00', m: '00', s: '00',
            init() {
                this.updateTime();
                setInterval(() => this.updateTime(), 1000);
            },
            updateTime() {
                const distance = this.endTime - new Date().getTime();
                if (distance < 0) {
                    this.d = '00'; this.h = '00'; this.m = '00'; this.s = '00';
                    return;
                }
                this.d = String(Math.floor(distance / 86400000)).padStart(2, '0');
                this.h = String(Math.floor((distance % 86400000) / 3600000)).padStart(2, '0');
                this.m = String(Math.floor((distance % 3600000) / 60000)).padStart(2, '0');
                this.s = String(Math.floor((distance % 60000) / 1000)).padStart(2, '0');
            }
        }">
            <!-- Neo-brutalist container -->
            <div @mouseenter="hovered = true" @mouseleave="hovered = false" class="bg-[#FFF4E0] border-[3px] border-zinc-900 rounded-2xl p-6 sm:p-8 shadow-[8px_8px_0px_#18181b] overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-8 relative z-10 transition-transform duration-300 ease-out"
                 :class="hovered ? 'translate-x-1 translate-y-1 shadow-[4px_4px_0px_#18181b]' : ''">
                
                <!-- Background decorative shapes -->
                <div class="absolute -right-16 -top-16 w-56 h-56 bg-[#FF90E8] rounded-full border-[3px] border-zinc-900 opacity-60 z-0 mix-blend-multiply transition-transform duration-700" :class="hovered ? 'scale-110' : ''"></div>
                <div class="absolute right-32 -bottom-16 w-40 h-40 bg-[#3B82F6] rounded-full border-[3px] border-zinc-900 opacity-60 z-0 mix-blend-multiply transition-transform duration-500 delay-100" :class="hovered ? 'scale-125' : ''"></div>
                <div class="absolute -left-12 top-1/2 w-32 h-32 bg-[#FFB800] border-[3px] border-zinc-900 opacity-60 z-0 mix-blend-multiply rounded-xl transition-transform duration-700 rotate-12" :class="hovered ? 'rotate-45 scale-110' : ''"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 w-full lg:w-auto">
                    <!-- Icon block -->
                    <div class="flex-shrink-0 bg-[#FFB800] border-[3px] border-zinc-900 rounded-2xl p-4 shadow-[4px_4px_0px_#18181b] transform -rotate-6 transition-transform duration-300" :class="hovered ? 'rotate-6 scale-110' : ''">
                        <span class="material-symbols-outlined text-6xl text-zinc-900">campaign</span>
                    </div>
                    
                    <!-- Text content -->
                    <div class="text-center md:text-left">
                        <div class="inline-block bg-white border-[2px] border-zinc-900 rounded-full px-4 py-1 mb-4 shadow-[3px_3px_0px_#18181b] transform transition-transform duration-300 -rotate-1" :class="hovered ? '-rotate-3' : ''">
                            <span class="text-zinc-900 font-black tracking-widest text-xs uppercase px-1">🔥 Limited Time Event</span>
                        </div>
                        <h2 class="text-4xl sm:text-5xl font-black text-zinc-900 uppercase mb-3 leading-none transition-transform duration-300" style="font-family: 'Arial Black', Impact, sans-serif; -webkit-text-stroke: 1px #18181b;" :class="hovered ? 'translate-x-2' : ''">
                            {{ $activeCampaign->name }}
                        </h2>
                        <p class="text-zinc-800 font-bold text-lg sm:text-xl">
                            All payouts are multiplied by <span class="inline-block bg-[#FF90E8] border-[2px] border-zinc-900 px-3 py-1 rounded-lg shadow-[3px_3px_0px_#18181b] transform -rotate-3 text-2xl ml-1 transition-transform duration-300" :class="hovered ? 'rotate-3 scale-110 bg-[#3B82F6] text-white' : ''">{{ $activeCampaign->multiplier }}X</span>
                        </p>
                    </div>
                </div>

                <!-- Circular Countdown -->
                <div class="relative z-10 flex-shrink-0 bg-white border-[3px] border-zinc-900 rounded-2xl p-5 shadow-[6px_6px_0px_#18181b] w-full lg:w-auto max-w-sm mx-auto lg:mx-0 transform rotate-2 transition-transform duration-300" :class="hovered ? '-rotate-1' : ''">
                    <p class="text-center text-zinc-900 font-black uppercase tracking-widest text-xs border-b-[3px] border-dashed border-zinc-300 pb-3 mb-4">Time Remaining</p>
                    
                    <div class="flex justify-center items-center gap-2 sm:gap-3 font-black text-2xl sm:text-3xl text-zinc-900" style="font-family: Monaco, Consolas, monospace;">
                        <!-- Days -->
                        <div class="flex flex-col items-center group">
                            <span class="bg-[#E0E7FF] border-[3px] border-zinc-900 rounded-xl w-14 sm:w-16 h-14 sm:h-16 flex items-center justify-center shadow-[3px_3px_0px_#18181b] group-hover:-translate-y-1 transition-transform" x-text="d"></span>
                            <span class="text-[10px] mt-3 font-black uppercase tracking-widest text-zinc-500">Days</span>
                        </div>
                        <span class="text-3xl pb-6 animate-pulse">:</span>
                        <!-- Hours -->
                        <div class="flex flex-col items-center group">
                            <span class="bg-[#E0E7FF] border-[3px] border-zinc-900 rounded-xl w-14 sm:w-16 h-14 sm:h-16 flex items-center justify-center shadow-[3px_3px_0px_#18181b] group-hover:-translate-y-1 transition-transform" x-text="h"></span>
                            <span class="text-[10px] mt-3 font-black uppercase tracking-widest text-zinc-500">Hrs</span>
                        </div>
                        <span class="text-3xl pb-6 animate-pulse text-zinc-300">:</span>
                        <!-- Minutes -->
                        <div class="flex flex-col items-center group">
                            <span class="bg-[#E0E7FF] border-[3px] border-zinc-900 rounded-xl w-14 sm:w-16 h-14 sm:h-16 flex items-center justify-center shadow-[3px_3px_0px_#18181b] group-hover:-translate-y-1 transition-transform" x-text="m"></span>
                            <span class="text-[10px] mt-3 font-black uppercase tracking-widest text-zinc-500">Min</span>
                        </div>
                        <!-- Seconds (Only visible when days == 0) -->
                        <span x-show="d == '00'" class="text-3xl pb-6 animate-pulse text-red-500" style="display: none;">:</span>
                        <div x-show="d == '00'" class="flex flex-col items-center group" style="display: none;">
                            <span class="bg-[#FECACA] text-red-600 border-[3px] border-zinc-900 rounded-xl w-14 sm:w-16 h-14 sm:h-16 flex items-center justify-center shadow-[3px_3px_0px_#18181b] group-hover:-translate-y-1 transition-transform" x-text="s"></span>
                            <span class="text-[10px] mt-3 font-black uppercase tracking-widest text-red-500">Sec</span>
                        </div>
                    </div>
                </div>
            </div>
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
    <div data-tutorial="gamification-widgets" class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
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
