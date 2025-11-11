<div>
    <div class="mb-6">
        <livewire:user.stats-date-filter />
    </div>

    <!-- Temel Kazanç ve Görüntülenme Metrik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10"> {{-- mb-8'den mb-10'a artırıldı --}}
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m-4-4h8m-4 4a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Toplam Yayıncı Kazancı</p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">${{ number_format($totalPublisherEarnings ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Link Kazançları</p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">${{ number_format($totalLinkEarnings ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Referans Kazançları</p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">${{ number_format($totalReferralEarnings ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Toplam Görüntülenme</p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">{{ number_format($totalViews ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kullanıcı ve Link Aktivite Metrik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10"> {{-- mb-8'den mb-10'a artırıldı --}}
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Yeni Kullanıcılar (24s)</p>
            <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">{{ number_format($this->newUsersLast24Hours) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Yeni Kullanıcılar (7g)</p>
            <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">{{ number_format($newUsersLast7Days) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Yeni Linkler (24s)</p>
            <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">{{ number_format($newLinksLast24Hours) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Yeni Linkler (7g)</p>
            <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">{{ number_format($newLinksLast7Days) }}</p>
        </div>
    </div>

    <!-- Operasyonel Metrik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10"> {{-- mb-8'den mb-10'a artırıldı --}}
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Bekleyen Çekim Talepleri</p>
            <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">{{ number_format($pendingWithdrawalRequestsCount) }} (Toplam: ${{ number_format($pendingWithdrawalRequestsAmount, 2) }})</p>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Açık Destek Talepleri</p>
            <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">{{ number_format($openSupportTicketsCount) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase break-words">Toplam Aktif Link</p>
            <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200 break-words">{{ number_format($totalActiveLinks) }}</p>
        </div>
    </div>
    
    <!-- İstatistik Grafiği -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-10"> {{-- mb-8'den mb-10'a artırıldı --}}
        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Günlük Tıklanma İstatistikleri</h3>
        <div wire:ignore>
            <canvas id="dailyClicksChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10"> {{-- mb-6'dan mb-10'a artırıldı --}}
        <!-- En Çok Tıklama Alan Ülkeler -->
        <div class="lg:col-span-1 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">En Çok Tıklama Alan Ülkeler</h3>
            @if(!empty($topCountries))
                <ul>
                    @foreach($topCountries as $country)
                        <li class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-300">{{ $country['name'] }}</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($country['clicks']) }} ({{ $country['percentage'] }}%)</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 dark:text-gray-400">Veri bulunamadı.</p>
            @endif
        </div>

        <!-- Son Duyurular -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Son Duyurular</h3>
            @if($recentAnnouncements->count() > 0)
                <ul>
                    @foreach($recentAnnouncements as $announcement)
                        <li class="py-2 border-b border-gray-200 dark:border-gray-700">
                            <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $announcement->title }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $announcement->created_at->format('d M Y, H:i') }}</p>
                            <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                {!! Str::limit($announcement->content, 150) !!}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 dark:text-gray-400">Gösterilecek duyuru bulunmuyor.</p>
            @endif
        </div>
    </div>

    <!-- Detaylı Veri Tablosu -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Günlük İstatistikler Tablosu</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tarih</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Görüntülenme</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Link Kazancı</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Referans Kazancı</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Toplam Yayıncı Kazancı</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Günlük CPM</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($dailyStatsTableData as $stat)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $stat['date'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($stat['views']) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $stat['link_earnings'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $stat['referral_earnings'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $stat['total_publisher_earnings'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $stat['daily_cpm'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">
                                Seçili tarih aralığı için veri bulunamadı.
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
            const ctx = document.getElementById('dailyClicksChart').getContext('2d');
            if (dailyClicksChart) {
                dailyClicksChart.destroy();
            }
            dailyClicksChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Günlük Tıklanmalar',
                        data: data,
                        borderColor: 'rgba(59, 130, 246, 0.8)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        tension: 0.1,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Initial chart render
        @this.data.dailyClicksData && renderDailyClicksChart(@json($chartLabels), @json($chartData));
        
        Livewire.on('statsRefreshed', (eventData) => {
             if (eventData && eventData.dailyClicksData) {
                renderDailyClicksChart(eventData.dailyClicksData.labels, eventData.dailyClicksData.data);
            } else if (@this.data.dailyClicksData) { // Fallback to initial data if event data is incomplete
                 renderDailyClicksChart(@json($chartLabels), @json($chartData));
            }
        });

        // Re-render chart when date filter changes and stats are reloaded
        // This assumes your date filter component emits an event or this component re-renders
         window.addEventListener('stats-updated-by-filter', event => {
            if (event.detail && event.detail.dailyClicksData) {
                 renderDailyClicksChart(event.detail.dailyClicksData.labels, event.detail.dailyClicksData.data);
            }
        });

    });
</script>
@endpush
