<div class="space-y-6">
    {{-- Date Filter --}}
    <div class="mb-4">
        <livewire:user.stats-date-filter />
    </div>

    {{-- Basic Earnings and Views Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        {{-- Toplam Yayıncı Kazancı --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-sky-500/10">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-sky-500/20 blur-2xl group-hover:bg-sky-500/30 transition-colors"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wide">Total Publisher Earnings</p>
                    <p class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-cyan-400 mt-1">${{ number_format($totalPublisherEarnings ?? 0, 2) }}</p>
                </div>
                <div class="rounded-xl bg-gradient-to-br from-sky-500/30 to-cyan-500/30 p-3">
                    <svg class="w-6 h-6 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Link Earnings --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-emerald-500/10">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/20 blur-2xl group-hover:bg-emerald-500/30 transition-colors"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wide">Link Earnings</p>
                    <p class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400 mt-1">${{ number_format($totalLinkEarnings ?? 0, 2) }}</p>
                </div>
                <div class="rounded-xl bg-gradient-to-br from-emerald-500/30 to-teal-500/30 p-3">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Referral Earnings --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-amber-500/10">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-500/20 blur-2xl group-hover:bg-amber-500/30 transition-colors"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wide">Referral Earnings</p>
                    <p class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-400 mt-1">${{ number_format($totalReferralEarnings ?? 0, 2) }}</p>
                </div>
                <div class="rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 p-3">
                    <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Toplam Görüntülenme --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-violet-500/10">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-violet-500/20 blur-2xl group-hover:bg-violet-500/30 transition-colors"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wide">Total Views</p>
                    <p class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-purple-400 mt-1">{{ number_format($totalViews ?? 0) }}</p>
                </div>
                <div class="rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 p-3">
                    <svg class="w-6 h-6 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Kullanıcı ve Link Aktivite Metrik Kartları --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-5 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-sky-500/20 p-2.5">
                    <svg class="w-5 h-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400">New Users (24h)</p>
                    <p class="text-xl font-bold text-white">{{ number_format($newUsersLast24Hours) }}</p>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-5 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-cyan-500/20 p-2.5">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400">New Users (7d)</p>
                    <p class="text-xl font-bold text-white">{{ number_format($newUsersLast7Days) }}</p>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-5 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-teal-500/20 p-2.5">
                    <svg class="w-5 h-5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400">New Links (24h)</p>
                    <p class="text-xl font-bold text-white">{{ number_format($newLinksLast24Hours) }}</p>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-5 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-emerald-500/20 p-2.5">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400">New Links (7d)</p>
                    <p class="text-xl font-bold text-white">{{ number_format($newLinksLast7Days) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Operasyonel Kartlar --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
            <div class="flex items-center gap-4">
                <div class="rounded-xl bg-amber-500/20 p-3">
                    <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Pending Withdrawal Requests</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($pendingWithdrawalRequestsCount) }}</p>
                    <p class="text-sm text-amber-400 font-medium">${{ number_format($pendingWithdrawalRequestsAmount, 2) }} total</p>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
            <div class="flex items-center gap-4">
                <div class="rounded-xl bg-rose-500/20 p-3">
                    <svg class="w-6 h-6 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Open Support Tickets</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($openSupportTicketsCount) }}</p>
                    <p class="text-sm text-rose-400 font-medium">Waiting for response</p>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
            <div class="flex items-center gap-4">
                <div class="rounded-xl bg-emerald-500/20 p-3">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Active Links</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($totalActiveLinks) }}</p>
                    <p class="text-sm text-emerald-400 font-medium">Working</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row - Daily Clicks & User Growth --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Daily Click Statistics --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
                Daily Click Statistics
            </h3>
            <div wire:ignore class="h-64">
                <canvas id="dailyClicksChart"></canvas>
            </div>
        </div>

        {{-- User Growth Chart --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                User Growth (Last 12 Weeks)
            </h3>
            <div wire:ignore class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Admin vs Publisher Earnings --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                Earnings Distribution
            </h3>
            <div wire:ignore class="h-56 flex items-center justify-center">
                <canvas id="earningsChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4 text-center">
                <div class="rounded-xl bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Admin Profit</p>
                    <p class="text-lg font-bold text-sky-400">${{ number_format($earningsComparisonData['admin'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-xl bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Publisher Payout</p>
                    <p class="text-lg font-bold text-emerald-400">${{ number_format($earningsComparisonData['publisher'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Live Activity Feed --}}
        <div class="lg:col-span-2 rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Live Activity Feed
            </h3>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @forelse($recentActivity as $activity)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors">
                        <div class="p-2 rounded-lg bg-{{ $activity['color'] }}-500/20">
                            @if($activity['icon'] === 'user-plus')
                                <svg class="w-4 h-4 text-{{ $activity['color'] }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            @elseif($activity['icon'] === 'link')
                                <svg class="w-4 h-4 text-{{ $activity['color'] }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            @elseif($activity['icon'] === 'banknotes')
                                <svg class="w-4 h-4 text-{{ $activity['color'] }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-white">{{ $activity['message'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['time_ago'] }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Three Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Top Clicked Countries --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Top Clicked Countries
            </h3>
            @if(!empty($topCountries))
                <div class="space-y-3">
                    @foreach($topCountries as $index => $country)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 flex items-center justify-center text-xs font-bold rounded-full {{ $index === 0 ? 'bg-amber-500/30 text-amber-400' : ($index === 1 ? 'bg-gray-500/30 text-gray-300' : ($index === 2 ? 'bg-orange-500/30 text-orange-400' : 'bg-gray-600/30 text-gray-400')) }}">{{ $index + 1 }}</span>
                                <span class="font-medium text-white">{{ $country['name'] }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold text-white">{{ number_format($country['clicks']) }}</span>
                                <span class="text-xs text-gray-400 ml-1">({{ $country['percentage'] }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-4">No data found.</p>
            @endif
        </div>

        {{-- Recent Announcements --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                Recent Announcements
            </h3>
            @if($recentAnnouncements->count() > 0)
                <div class="space-y-4">
                    @foreach($recentAnnouncements as $announcement)
                        <div class="p-4 rounded-xl bg-white/5 border-l-4 border-sky-500 hover:bg-white/10 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <h4 class="font-semibold text-white">{{ $announcement->title }}</h4>
                                <span class="text-xs text-gray-400 whitespace-nowrap">{{ $announcement->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-sm text-gray-300 mt-2 line-clamp-2">{!! Str::limit($announcement->content, 150) !!}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-4">No announcements to display.</p>
            @endif
        </div>

        {{-- Recent Links --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Recent Shortened Links
            </h3>
            @if(!empty($recentLinks))
                <div class="space-y-3">
                    @foreach(array_slice($recentLinks, 0, 5) as $link)
                        <div class="p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-mono text-sky-400">{{ $link['short_code'] }}</span>
                                <span class="text-xs text-gray-500">{{ $link['created_at'] }}</span>
                            </div>
                            <p class="text-xs text-gray-400 truncate mt-1">{{ $link['original_url'] }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">{{ $link['user'] }}</span>
                                <span class="text-xs text-emerald-400 font-medium">{{ $link['clicks'] }} clicks</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-4">No links found.</p>
            @endif
        </div>
    </div>

    {{-- Daily Statistics Table --}}
    <div class="rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-white/10 flex items-center gap-2">
            <svg class="w-5 h-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-semibold text-white">Daily Statistics Table</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead class="bg-white/5">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center gap-1">
                                Total Views
                                <span class="text-gray-500 cursor-help" title="All views including non-paid">ⓘ</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-emerald-400 uppercase tracking-wider">
                            <div class="flex items-center gap-1">
                                Paid Views
                                <span class="text-emerald-500 cursor-help" title="Views that generated earnings">ⓘ</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Link Earnings</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Referral Earnings</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Total Earnings</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Daily CPM</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($dailyStatsTableData as $index => $stat)
                        <tr class="hover:bg-white/5 transition-colors {{ $index % 2 === 0 ? 'bg-white/[0.02]' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $stat['date'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ number_format($stat['views']) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-emerald-400">{{ number_format($stat['paid_views']) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stat['link_earnings'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stat['referral_earnings'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $stat['total_publisher_earnings'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stat['daily_cpm'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2">No data found for the selected date range.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    const chartTextColor = '#9ca3af';
    const gridColor = 'rgba(255,255,255,0.05)';
    
    let dailyClicksChart = null;
    let userGrowthChart = null;
    let earningsChart = null;

    function createDailyClicksChart() {
        const ctx = document.getElementById('dailyClicksChart');
        if (!ctx) return false;
        
        const context = ctx.getContext('2d');
        if (dailyClicksChart) dailyClicksChart.destroy();
        
        const gradient = context.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(14, 165, 233, 0.4)');
        gradient.addColorStop(1, 'rgba(14, 165, 233, 0.01)');
        
        const labels = @json($chartLabels ?? []);
        const data = @json($chartData ?? []);
        
        dailyClicksChart = new Chart(context, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Clicks',
                    data: data,
                    borderColor: 'rgb(14, 165, 233)',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(14, 165, 233)',
                    pointBorderColor: '#0f1928',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 25, 40, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#d1d5db',
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: chartTextColor }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: chartTextColor }
                    }
                }
            }
        });
        return true;
    }

    function createUserGrowthChart() {
        const ctx = document.getElementById('userGrowthChart');
        if (!ctx) return false;
        
        const context = ctx.getContext('2d');
        if (userGrowthChart) userGrowthChart.destroy();
        
        const labels = @json($userGrowthData['labels'] ?? []);
        const data = @json($userGrowthData['data'] ?? []);
        
        userGrowthChart = new Chart(context, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'New Users',
                    data: data,
                    backgroundColor: 'rgba(6, 182, 212, 0.7)',
                    borderColor: 'rgb(6, 182, 212)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 25, 40, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#d1d5db',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: chartTextColor }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: chartTextColor }
                    }
                }
            }
        });
        return true;
    }

    function createEarningsChart() {
        const ctx = document.getElementById('earningsChart');
        if (!ctx) return false;
        
        const context = ctx.getContext('2d');
        if (earningsChart) earningsChart.destroy();
        
        const adminProfit = {{ $earningsComparisonData['admin'] ?? 0 }};
        const publisherPayout = {{ $earningsComparisonData['publisher'] ?? 0 }};
        
        earningsChart = new Chart(context, {
            type: 'doughnut',
            data: {
                labels: ['Admin Profit', 'Publisher Payout'],
                datasets: [{
                    data: [adminProfit, publisherPayout],
                    backgroundColor: ['rgba(14, 165, 233, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                    borderColor: ['rgb(14, 165, 233)', 'rgb(16, 185, 129)'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 25, 40, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#d1d5db',
                        callbacks: {
                            label: function(context) {
                                return context.label + ': $' + context.parsed.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
        return true;
    }

    function initAllCharts() {
        try {
            createDailyClicksChart();
            createUserGrowthChart();
            createEarningsChart();
            console.log('Charts initialized successfully');
        } catch(e) {
            console.error('Chart init error:', e);
        }
    }

    // Wait for Chart.js to be available and DOM ready
    function waitAndInit() {
        if (typeof Chart !== 'undefined') {
            initAllCharts();
        } else {
            setTimeout(waitAndInit, 100);
        }
    }

    // Initialize on DOM ready
    if (document.readyState === 'complete') {
        waitAndInit();
    } else {
        window.addEventListener('load', waitAndInit);
    }
})();
</script>
