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

    {{-- URL Kısaltma Formu --}}
    <div class="bg-card-light dark:bg-card-dark p-6 rounded-lg mb-8 shadow-md">
        <div class="flex items-center gap-4">
            <input class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent text-heading-light dark:text-heading-dark placeholder:text-text-light dark:placeholder:text-text-dark" placeholder="Paste your long URL here" type="text"/>
            <button class="bg-primary text-white font-semibold px-6 py-3 rounded-lg flex items-center gap-2 whitespace-nowrap hover:bg-blue-600 transition-colors">
                <span class="material-symbols-outlined">content_cut</span>
                Shrink Now
            </button>
        </div>
    </div>

    <div class="flex justify-end mb-4">
        <livewire:user.stats-date-filter />
    </div>

    {{-- İstatistik Kartları (Bu bölüm livewire:user.dashboard-stats bileşeni ile dinamik hale getirilebilir) --}}
    <livewire:user.dashboard-stats />
    
    {{-- İstatistik Grafiği ve Tablosu --}}
    <livewire:user.earnings-chart />

    {{-- Diğer Statik Bölümler --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 my-8">
        <div class="xl:col-span-1 bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Earnings Goal</h3>
            <div class="text-center">
                <div class="relative inline-flex items-center justify-center">
                    <svg class="w-32 h-32" viewBox="0 0 120 120">
                        <circle class="stroke-current text-gray-200 dark:text-gray-700" cx="60" cy="60" fill="transparent" r="54" stroke-width="12"></circle>
                        <circle class="stroke-current text-primary -rotate-90 origin-center" cx="60" cy="60" fill="transparent" r="54" stroke-dasharray="339.292" stroke-dashoffset="84.823" stroke-linecap="round" stroke-width="12" style="stroke-dashoffset: 84.823;"></circle>
                    </svg>
                    <div class="absolute flex flex-col items-center">
                        <span class="text-3xl font-bold text-heading-light dark:text-heading-dark">75%</span>
                    </div>
                </div>
                <p class="text-lg font-medium text-heading-light dark:text-heading-dark mt-4">$75.00 / $100.00</p>
                <p class="text-sm text-text-light dark:text-text-dark">of your monthly goal.</p>
                <button class="mt-4 bg-primary/10 text-primary font-semibold px-4 py-2 rounded-lg hover:bg-primary/20 transition-colors">Set New Goal</button>
            </div>
        </div>
        <div class="xl:col-span-2 bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Top Viewed Countries</h3>
            <div class="space-y-4">
                {{-- Country data will be dynamic later --}}
                <div class="flex items-center gap-4">
                    <div class="flex-grow">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-heading-light dark:text-heading-dark font-medium">United States</span>
                            <span class="font-bold text-sm text-heading-light dark:text-heading-dark">79 Clicks</span>
                        </div>
                        <div class="w-full bg-background-light dark:bg-background-dark rounded-full h-1.5">
                            <div class="bg-primary h-1.5 rounded-full" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex-grow">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-heading-light dark:text-heading-dark font-medium">United Kingdom</span>
                            <span class="font-bold text-sm text-heading-light dark:text-heading-dark">45 Clicks</span>
                        </div>
                        <div class="w-full bg-background-light dark:bg-background-dark rounded-full h-1.5">
                            <div class="bg-primary h-1.5 rounded-full" style="width: 23%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md mb-8">
        <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Link Manager</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                {{-- Link manager table content will be dynamic later --}}
                <thead class="border-b border-border-light dark:border-border-dark">
                    <tr>
                        <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Short Link</th>
                        <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Original Link</th>
                        <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Clicks</th>
                        <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Status</th>
                        <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Date</th>
                        <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="p-3 text-primary font-semibold">linkly.to/xY2zAb</td>
                        <td class="p-3 text-heading-light dark:text-heading-dark truncate max-w-xs">https://example.com/very-long-url-that-needs-to-be-shortened</td>
                        <td class="p-3 text-heading-light dark:text-heading-dark">1,204</td>
                        <td class="p-3"><span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">Active</span></td>
                        <td class="p-3 text-heading-light dark:text-heading-dark">Aug 12, 2023</td>
                        <td class="p-3">
                            <div class="flex justify-end items-center gap-2">
                                <button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm">edit</span></button>
                                <button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm">bar_chart</span></button>
                                <button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm text-red-500">delete</span></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Optimized Link Suggestions --}}
    <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md mb-8">
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2 bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Payment Information Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-text-light dark:text-text-dark mb-1">Current Balance</p>
                    <p class="text-3xl font-bold text-green-500">$36.75</p>
                    <p class="text-sm text-text-light dark:text-text-dark mt-2">Available for withdrawal.</p>
                </div>
                <div>
                    <p class="text-sm text-text-light dark:text-text-dark mb-1">Payment Threshold</p>
                    <p class="text-2xl font-bold text-heading-light dark:text-heading-dark">$50.00</p>
                    <div class="w-full bg-background-light dark:bg-background-dark rounded-full h-2.5 mt-2">
                        <div class="bg-primary h-2.5 rounded-full" style="width: 73.5%"></div>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-text-light dark:text-text-dark mb-1">Last Payment Date</p>
                    <p class="text-lg font-semibold text-heading-light dark:text-heading-dark">July 1, 2023</p>
                </div>
                <div>
                    <p class="text-sm text-text-light dark:text-text-dark mb-1">Next Payment Date</p>
                    <p class="text-lg font-semibold text-heading-light dark:text-heading-dark">September 1, 2023</p>
                </div>
            </div>
        </div>
        <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Recent Activity Feed</h3>
            <ul class="space-y-4">
                <li class="flex items-start gap-3">
                    <div class="mt-1 p-1.5 bg-blue-100 dark:bg-blue-900/50 rounded-full"><span class="material-symbols-outlined text-primary text-base">person_add</span></div>
                    <div>
                        <p class="text-sm text-heading-light dark:text-heading-dark">New referral signup: user_54321</p>
                        <p class="text-xs text-text-light dark:text-text-dark">2 hours ago</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <div class="mt-1 p-1.5 bg-green-100 dark:bg-green-900/50 rounded-full"><span class="material-symbols-outlined text-green-500 text-base">payments</span></div>
                    <div>
                        <p class="text-sm text-heading-light dark:text-heading-dark">Payment of $52.10 sent via PayPal.</p>
                        <p class="text-xs text-text-light dark:text-text-dark">1 day ago</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <div class="mt-1 p-1.5 bg-purple-100 dark:bg-purple-900/50 rounded-full"><span class="material-symbols-outlined text-purple-500 text-base">add_link</span></div>
                    <div>
                        <p class="text-sm text-heading-light dark:text-heading-dark">New link created: linkly.to/fG4hIj</p>
                        <p class="text-xs text-text-light dark:text-text-dark">3 days ago</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</x-user-dashboard-layout>
