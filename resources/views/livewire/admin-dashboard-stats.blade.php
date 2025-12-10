<div class="space-y-6">
    {{-- Date Filter --}}
    <div class="mb-4">
        <livewire:user.stats-date-filter />
    </div>

    {{-- Temel Kazanç ve Görüntülenme Metrik Kartları --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- Toplam Yayıncı Kazancı --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Toplam Yayıncı Kazancı</p>
                    <p class="text-2xl font-bold mt-1">${{ number_format($totalPublisherEarnings ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Link Kazançları --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-5 text-white shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-xs font-medium uppercase tracking-wide">Link Kazançları</p>
                    <p class="text-2xl font-bold mt-1">${{ number_format($totalLinkEarnings ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Referans Kazançları --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl p-5 text-white shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-xs font-medium uppercase tracking-wide">Referans Kazançları</p>
                    <p class="text-2xl font-bold mt-1">${{ number_format($totalReferralEarnings ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Toplam Görüntülenme --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl p-5 text-white shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-violet-100 text-xs font-medium uppercase tracking-wide">Toplam Görüntülenme</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($totalViews ?? 0) }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Kullanıcı ve Link Aktivite Metrik Kartları --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-sky-100 dark:bg-sky-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Yeni Kullanıcılar (24s)</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($this->newUsersLast24Hours) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Yeni Kullanıcılar (7g)</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($newUsersLast7Days) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-teal-100 dark:bg-teal-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Yeni Linkler (24s)</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($newLinksLast24Hours) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Yeni Linkler (7g)</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($newLinksLast7Days) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Operasyonel Kartlar --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Bekleyen Çekim Talepleri</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingWithdrawalRequestsCount) }}</p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">${{ number_format($pendingWithdrawalRequestsAmount, 2) }} toplam</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-xl">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Açık Destek Talepleri</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($openSupportTicketsCount) }}</p>
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium">Yanıt bekliyor</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Toplam Aktif Link</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalActiveLinks) }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 font-medium">Çalışıyor</p>
                </div>
            </div>
        </div>
    </div>
    
    {{-- İstatistik Grafiği --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Günlük Tıklanma İstatistikleri</h3>
        <div wire:ignore class="h-64">
            <canvas id="dailyClicksChart"></canvas>
        </div>
    </div>

    {{-- İki Sütunlu Alan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- En Çok Tıklama Alan Ülkeler --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">En Çok Tıklama Alan Ülkeler</h3>
            @if(!empty($topCountries))
                <div class="space-y-3">
                    @foreach($topCountries as $index => $country)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 flex items-center justify-center text-xs font-bold rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400' : ($index === 1 ? 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300' : ($index === 2 ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400')) }}">{{ $index + 1 }}</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $country['name'] }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($country['clicks']) }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">({{ $country['percentage'] }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Veri bulunamadı.</p>
            @endif
        </div>

        {{-- Son Duyurular --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Son Duyurular</h3>
            @if($recentAnnouncements->count() > 0)
                <div class="space-y-4">
                    @foreach($recentAnnouncements as $announcement)
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border-l-4 border-blue-500">
                            <div class="flex items-start justify-between gap-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $announcement->title }}</h4>
                                <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $announcement->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 line-clamp-2">{!! Str::limit($announcement->content, 150) !!}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Gösterilecek duyuru bulunmuyor.</p>
            @endif
        </div>
    </div>

    {{-- Detaylı Veri Tablosu --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Günlük İstatistikler Tablosu</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tarih</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Görüntülenme</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Link Kazancı</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Referans Kazancı</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Toplam Kazanç</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Günlük CPM</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($dailyStatsTableData as $index => $stat)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-800/50' }} hover:bg-blue-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $stat['date'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ number_format($stat['views']) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $stat['link_earnings'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $stat['referral_earnings'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $stat['total_publisher_earnings'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $stat['daily_cpm'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2">Seçili tarih aralığı için veri bulunamadı.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        let dailyClicksChart = null;

        function renderDailyClicksChart(labels, data) {
            const ctx = document.getElementById('dailyClicksChart');
            if (!ctx) return;
            
            const context = ctx.getContext('2d');
            if (dailyClicksChart) {
                dailyClicksChart.destroy();
            }
            
            const gradient = context.createLinearGradient(0, 0, 0, 250);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.01)');
            
            dailyClicksChart = new Chart(context, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Günlük Tıklanmalar',
                        data: data,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initial chart render
        @if(isset($chartLabels) && isset($chartData))
            renderDailyClicksChart(@json($chartLabels), @json($chartData));
        @endif
        
        Livewire.on('statsRefreshed', (eventData) => {
            if (eventData && eventData.dailyClicksData) {
                renderDailyClicksChart(eventData.dailyClicksData.labels, eventData.dailyClicksData.data);
            }
        });

        window.addEventListener('stats-updated-by-filter', event => {
            if (event.detail && event.detail.dailyClicksData) {
                renderDailyClicksChart(event.detail.dailyClicksData.labels, event.detail.dailyClicksData.data);
            }
        });
    });
</script>
@endpush
