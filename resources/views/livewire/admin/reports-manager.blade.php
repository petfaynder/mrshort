<div>
    {{-- Filtre Bölümü --}}
    <div class="mb-6">
        {{ $this->form }}
    </div>

    {{-- İstatistik Kartları --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $totalClicks = collect($clicksByDeviceType)->sum('total');
            $humanClicks = collect($clicksByBotStatus)->where('is_bot', false)->first()->total ?? 0;
            $botClicks = collect($clicksByBotStatus)->where('is_bot', true)->first()->total ?? 0;
            $topCountry = collect($clicksByCountryChartData['labels'])->zip($clicksByCountryChartData['data'])->sortByDesc(1)->first();
        @endphp
        <x-filament::card>
            <div class="text-center">
                <p class="text-3xl font-bold text-primary">{{ number_format($totalClicks) }}</p>
                <p class="text-sm text-gray-400">Toplam Tıklama</p>
            </div>
        </x-filament::card>
        <x-filament::card>
            <div class="text-center">
                <p class="text-3xl font-bold text-green-500">{{ number_format($humanClicks) }}</p>
                <p class="text-sm text-gray-400">İnsan Tıklaması</p>
            </div>
        </x-filament::card>
        <x-filament::card>
            <div class="text-center">
                <p class="text-3xl font-bold text-red-500">{{ number_format($botClicks) }}</p>
                <p class="text-sm text-gray-400">Bot Tıklaması</p>
            </div>
        </x-filament::card>
        <x-filament::card>
            <div class="text-center">
                <p class="text-3xl font-bold text-yellow-500">{{ $topCountry[0] ?? '-' }}</p>
                <p class="text-sm text-gray-400">En Çok Tıklanan Ülke</p>
            </div>
        </x-filament::card>
    </div>

    {{-- Grafikler --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Ülkelere Göre Tıklamalar --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Ülkelere Göre Tıklamalar</h3>
                <x-filament::button wire:click="exportCsv('countries')" size="sm" color="gray">
                    CSV Export
                </x-filament::button>
            </div>
            @if(count($clicksByCountryChartData['data']) > 0)
                <div style="height: 300px;">
                    <canvas id="countriesChart"></canvas>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Veri bulunamadı</p>
            @endif
        </x-filament::card>

        {{-- Zaman Trendleri --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Günlük Tıklama Trendleri</h3>
                <x-filament::button wire:click="exportCsv('time_trends')" size="sm" color="gray">
                    CSV Export
                </x-filament::button>
            </div>
            @if($clicksOverTime->count() > 0)
                <div style="height: 300px;">
                    <canvas id="timeChart"></canvas>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Veri bulunamadı</p>
            @endif
        </x-filament::card>
    </div>

    {{-- 3 Sütunlu Kartlar --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        {{-- Cihaz Türleri --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Cihaz Türleri</h3>
                <x-filament::button wire:click="exportCsv('device_types')" size="sm" color="gray">
                    Export
                </x-filament::button>
            </div>
            <ul class="divide-y divide-gray-700">
                @forelse($clicksByDeviceType as $device)
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm">{{ $device->device_type ?? 'Diğer' }}</span>
                    <span class="text-sm font-semibold">{{ number_format($device->total) }}</span>
                </li>
                @empty
                <li class="py-3 text-center text-gray-500">Veri yok</li>
                @endforelse
            </ul>
        </x-filament::card>

        {{-- İşletim Sistemleri --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">İşletim Sistemleri</h3>
                <x-filament::button wire:click="exportCsv('operating_systems')" size="sm" color="gray">
                    Export
                </x-filament::button>
            </div>
            <ul class="divide-y divide-gray-700 max-h-64 overflow-y-auto">
                @forelse($clicksByOs as $os)
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm">{{ $os->os ?? 'Bilinmiyor' }}</span>
                    <span class="text-sm font-semibold">{{ number_format($os->total) }}</span>
                </li>
                @empty
                <li class="py-3 text-center text-gray-500">Veri yok</li>
                @endforelse
            </ul>
        </x-filament::card>

        {{-- Tarayıcılar --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Tarayıcılar</h3>
                <x-filament::button wire:click="exportCsv('browsers')" size="sm" color="gray">
                    Export
                </x-filament::button>
            </div>
            <ul class="divide-y divide-gray-700 max-h-64 overflow-y-auto">
                @forelse($clicksByBrowser as $browser)
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm">{{ $browser->browser ?? 'Bilinmiyor' }}</span>
                    <span class="text-sm font-semibold">{{ number_format($browser->total) }}</span>
                </li>
                @empty
                <li class="py-3 text-center text-gray-500">Veri yok</li>
                @endforelse
            </ul>
        </x-filament::card>
    </div>

    {{-- 2 Sütunlu Kartlar --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Yönlendiren Domainler --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Yönlendiren Domainler</h3>
                <x-filament::button wire:click="exportCsv('referrers')" size="sm" color="gray">
                    Export
                </x-filament::button>
            </div>
            <ul class="divide-y divide-gray-700 max-h-64 overflow-y-auto">
                @forelse($clicksByReferrer as $referrer)
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm truncate max-w-xs">{{ $referrer->referrer ?? 'Direkt' }}</span>
                    <span class="text-sm font-semibold">{{ number_format($referrer->total) }}</span>
                </li>
                @empty
                <li class="py-3 text-center text-gray-500">Veri yok</li>
                @endforelse
            </ul>
        </x-filament::card>

        {{-- En Çok Tıklanan Ülkeler --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">En Çok Tıklanan Ülkeler</h3>
                <x-filament::button wire:click="exportCsv('countries_table')" size="sm" color="gray">
                    Export
                </x-filament::button>
            </div>
            <ul class="divide-y divide-gray-700 max-h-64 overflow-y-auto">
                @forelse(collect($clicksByCountryChartData['labels'])->zip($clicksByCountryChartData['data'])->sortByDesc(1)->take(10) as $country)
                <li class="py-3 flex justify-between items-center">
                    <span class="text-sm">{{ $country[0] ?? 'Bilinmiyor' }}</span>
                    <span class="text-sm font-semibold">{{ number_format($country[1]) }}</span>
                </li>
                @empty
                <li class="py-3 text-center text-gray-500">Veri yok</li>
                @endforelse
            </ul>
        </x-filament::card>
    </div>

    {{-- Linklere Göre Tıklamalar --}}
    <x-filament::card class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Linklere Göre Tıklamalar</h3>
            <x-filament::button wire:click="exportCsv('links')" size="sm" color="gray">
                CSV Export
            </x-filament::button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Kısa Link</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Orijinal URL</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase">Tıklama</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($clicksByLink as $link)
                    <tr>
                        <td class="px-4 py-3 text-sm text-primary">{{ $link['short_link'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-300 truncate max-w-xs">{{ Str::limit($link['original_url'], 50) }}</td>
                        <td class="px-4 py-3 text-sm text-right font-semibold">{{ number_format($link['total_clicks']) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-center text-gray-500">Veri yok</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($totalLinkPages > 1)
        <div class="mt-4 flex items-center justify-between border-t border-gray-700 pt-4">
            <div class="text-sm text-gray-400">
                Toplam {{ number_format($totalLinks) }} link, Sayfa {{ $linksPage }} / {{ $totalLinkPages }}
            </div>
            <div class="flex items-center gap-1">
                {{-- Previous Button --}}
                <button 
                    wire:click="previousLinksPage" 
                    @if($linksPage <= 1) disabled @endif
                    class="px-3 py-1 text-sm rounded border border-gray-600 hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    ← Önceki
                </button>
                
                {{-- Page Numbers --}}
                @php
                    $startPage = max(1, $linksPage - 2);
                    $endPage = min($totalLinkPages, $linksPage + 2);
                @endphp
                
                @if($startPage > 1)
                    <button wire:click="goToLinksPage(1)" class="px-3 py-1 text-sm rounded border border-gray-600 hover:bg-gray-700">1</button>
                    @if($startPage > 2)
                        <span class="px-2 text-gray-500">...</span>
                    @endif
                @endif
                
                @for($i = $startPage; $i <= $endPage; $i++)
                    <button 
                        wire:click="goToLinksPage({{ $i }})" 
                        class="px-3 py-1 text-sm rounded border {{ $i === $linksPage ? 'bg-primary text-white border-primary' : 'border-gray-600 hover:bg-gray-700' }}"
                    >
                        {{ $i }}
                    </button>
                @endfor
                
                @if($endPage < $totalLinkPages)
                    @if($endPage < $totalLinkPages - 1)
                        <span class="px-2 text-gray-500">...</span>
                    @endif
                    <button wire:click="goToLinksPage({{ $totalLinkPages }})" class="px-3 py-1 text-sm rounded border border-gray-600 hover:bg-gray-700">{{ $totalLinkPages }}</button>
                @endif
                
                {{-- Next Button --}}
                <button 
                    wire:click="nextLinksPage" 
                    @if($linksPage >= $totalLinkPages) disabled @endif
                    class="px-3 py-1 text-sm rounded border border-gray-600 hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Sonraki →
                </button>
            </div>
        </div>
        @endif
    </x-filament::card>
</div>

@script
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ülkelere göre pasta grafik
    const countriesData = @json($clicksByCountryChartData);
    if (countriesData.data && countriesData.data.length > 0) {
        const ctx1 = document.getElementById('countriesChart');
        if (ctx1) {
            new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: countriesData.labels.slice(0, 10),
                    datasets: [{
                        data: countriesData.data.slice(0, 10),
                        backgroundColor: [
                            '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
                            '#ec4899', '#06b6d4', '#84cc16', '#f97316', '#6366f1'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { color: '#9ca3af' }
                        }
                    }
                }
            });
        }
    }

    // Zaman trendleri çizgi grafik
    const timeData = @json($clicksOverTime);
    if (timeData && timeData.length > 0) {
        const ctx2 = document.getElementById('timeChart');
        if (ctx2) {
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: timeData.map(item => item.click_date),
                    datasets: [{
                        label: 'Tıklama',
                        data: timeData.map(item => item.total),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#9ca3af' },
                            grid: { color: 'rgba(156, 163, 175, 0.1)' }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#9ca3af' },
                            grid: { color: 'rgba(156, 163, 175, 0.1)' }
                        }
                    }
                }
            });
        }
    }
</script>
@endscript