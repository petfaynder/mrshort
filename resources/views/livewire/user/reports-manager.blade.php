<div>
    <main class="flex-1 mt-6">
        <div class="flex flex-wrap items-start justify-between gap-4 p-4">
            <div class="flex min-w-72 flex-col gap-3">
                <p class="text-white text-4xl font-black leading-tight tracking-[-0.033em]">Detaylı Raporlar</p>
                <p class="text-gray-400 text-base font-normal leading-normal">Tüm linklerinizin birleşik istatistiklerini analiz edin.</p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-medium text-gray-400" for="start-date">Başlangıç Tarihi</label>
                    <input wire:model.live="startDate" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-700 bg-gray-800 h-10 placeholder:text-gray-400 px-4 text-sm font-normal" id="start-date" type="date"/>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-medium text-gray-400" for="end-date">Bitiş Tarihi</label>
                    <input wire:model.live="endDate" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-700 bg-gray-800 h-10 placeholder:text-gray-400 px-4 text-sm font-normal" id="end-date" type="date"/>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-medium text-gray-400" for="quick-select">Hızlı Seçim</label>
                    <select wire:model.live="selectedPreset" class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-700 bg-gray-800 h-10 placeholder:text-gray-400 px-3 text-sm font-normal" id="quick-select">
                        <option value="last_7_days">Son 7 Gün</option>
                        <option value="last_30_days">Son 30 Gün</option>
                        <option value="last_90_days">Son 3 Ay</option>
                        <option value="last_365_days">Son 1 Yıl</option>
                        <option value="all_time">Tüm Zamanlar</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mt-6 bg-gray-900 rounded-xl border border-gray-800">
            <div class="p-4 flex flex-wrap justify-between items-center gap-4 border-b border-gray-800">
                <h3 class="text-lg font-bold text-white">Coğrafi Yoğunluk Haritası</h3>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span>Düşük</span>
                    <div class="flex h-3 w-32 rounded-full overflow-hidden">
                        <div class="w-1/4 h-full bg-blue-900"></div>
                        <div class="w-1/4 h-full bg-blue-700"></div>
                        <div class="w-1/4 h-full bg-blue-500"></div>
                        <div class="w-1/4 h-full bg-cyan-300"></div>
                    </div>
                    <span>Yüksek</span>
                    <button wire:click="exportCsv('countries')" class="flex items-center gap-2 font-medium hover:text-white ml-2">
                        <span class="material-symbols-outlined !text-base">download</span>
                    </button>
                </div>
            </div>
            <div wire:ignore class="p-4 relative min-h-[400px]" style="background-color: #101922;">
                @include('partials.world-map-svg')
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <div class="bg-gray-900 rounded-xl border border-gray-800">
                <div class="p-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Cihaz Türleri</h3>
                    <button wire:click="exportCsv('device_types')" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white">
                        <span class="material-symbols-outlined !text-base">download</span>
                    </button>
                </div>
                <ul class="divide-y divide-gray-800">
                    @foreach($clicksByDeviceType as $device)
                    <li class="p-4 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">
                                @if($device['device_type'] == 'Mobile') smartphone
                                @elseif($device['device_type'] == 'Desktop') desktop_windows
                                @elseif($device['device_type'] == 'Tablet') tablet_mac
                                @else tv @endif
                            </span>
                            <span class="text-sm font-medium text-white">{{ $device['device_type'] ?? 'Diğer' }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-300">{{ number_format($device['total']) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gray-900 rounded-xl border border-gray-800">
                <div class="p-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">İşletim Sistemleri</h3>
                    <button wire:click="exportCsv('operating_systems')" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white">
                        <span class="material-symbols-outlined !text-base">download</span>
                    </button>
                </div>
                <ul class="divide-y divide-gray-800">
                    @foreach($clicksByOs as $os)
                    <li class="p-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-white">{{ $os['os'] ?? 'Bilinmiyor' }}</span>
                        <span class="text-sm font-semibold text-gray-300">{{ number_format($os['total']) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gray-900 rounded-xl border border-gray-800">
                <div class="p-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Tarayıcılar</h3>
                    <button wire:click="exportCsv('browsers')" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white">
                        <span class="material-symbols-outlined !text-base">download</span>
                    </button>
                </div>
                <ul class="divide-y divide-gray-800">
                    @foreach($clicksByBrowser as $browser)
                    <li class="p-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-white">{{ $browser['browser'] ?? 'Bilinmiyor' }}</span>
                        <span class="text-sm font-semibold text-gray-300">{{ number_format($browser['total']) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-900 rounded-xl border border-gray-800">
                <div class="p-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Yönlendiren Domainler</h3>
                    <button wire:click="exportCsv('referrers')" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white">
                        <span class="material-symbols-outlined !text-base">download</span>
                    </button>
                </div>
                <ul class="divide-y divide-gray-800">
                    @foreach($clicksByReferrer as $referrer)
                    <li class="p-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-white">{{ $referrer['referrer'] ?? 'Doğrudan' }}</span>
                        <span class="text-sm font-semibold text-gray-300">{{ number_format($referrer['total']) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gray-900 rounded-xl border border-gray-800">
                <div class="p-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">En çok tıklama alınan Ülkeler</h3>
                    <button wire:click="exportCsv('countries_table')" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white">
                        <span class="material-symbols-outlined !text-base">download</span>
                    </button>
                </div>
                <ul class="divide-y divide-gray-800">
                    @foreach(collect($clicksByCountryChartData['labels'])->zip($clicksByCountryChartData['data'])->sortByDesc(1)->take(4) as $country)
                    <li class="p-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-white">{{ $country[0] ?? 'Bilinmiyor' }}</span>
                        <span class="text-sm font-semibold text-gray-300">{{ number_format($country[1]) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-900 rounded-xl border border-gray-800 md:col-span-2 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-bold text-white">İnsan Tıklaması</h3>
                    <button wire:click="exportCsv('bot_status')" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white">
                        <span class="material-symbols-outlined !text-base">download</span>
                    </button>
                </div>
                <div class="mt-4 flex-1 flex flex-col items-center justify-center text-center">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined !text-5xl text-primary">verified_user</span>
                        <div>
                            @php
                                $humanClicks = collect($clicksByBotStatus)->where('is_bot', false)->first()['total'] ?? 0;
                            @endphp
                            <p class="text-5xl font-bold text-white">{{ number_format($humanClicks) }}</p>
                            <p class="text-sm text-gray-400">gerçek kullanıcı tıklaması</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-bold text-white">Bot Tıklamaları</h3>
                        <button wire:click="exportCsv('bot_status')" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white">
                            <span class="material-symbols-outlined !text-base">download</span>
                        </button>
                    </div>
                    @php
                        $botClicks = collect($clicksByBotStatus)->where('is_bot', true)->first()['total'] ?? 0;
                        $totalBotAndHuman = $humanClicks + $botClicks;
                        $botPercentage = $totalBotAndHuman > 0 ? ($botClicks / $totalBotAndHuman) * 100 : 0;
                    @endphp
                    <p class="text-5xl font-bold text-red-accent mt-2">{{ number_format($botClicks) }}</p>
                    <p class="text-sm text-gray-400">engellenen tıklama</p>
                </div>
                <div class="mt-4 flex items-center gap-4">
                    <span class="material-symbols-outlined text-red-accent !text-4xl">smart_toy</span>
                    <div class="w-full">
                        <p class="text-sm text-gray-300">Toplam tıklamaların %{{ number_format($botPercentage, 1) }}'ı</p>
                        <div class="mt-1 h-2 w-full rounded-full bg-gray-700">
                            <div class="h-2 rounded-full bg-red-accent" style="width: {{ $botPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 bg-gray-900 rounded-xl border border-gray-800">
            <div class="p-4 flex justify-between items-center border-b border-gray-800">
                <h3 class="text-lg font-bold text-white">Linklere Göre Tıklamalar</h3>
                <button wire:click="exportCsv('links')" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white">
                    <span class="material-symbols-outlined !text-base">download</span>
                    <span>CSV Export</span>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th wire:click="sortBy('short_link')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider" scope="col">
                                <div class="flex items-center">
                                    <span>Kısa Link</span>
                                    @if($sortBy === 'short_link')
                                        <span class="material-symbols-outlined ml-2 !text-base">
                                            {{ $sortDirection === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
                                        </span>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('total_clicks')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider" scope="col">
                                <div class="flex items-center">
                                    <span>Tıklanma</span>
                                    @if($sortBy === 'total_clicks')
                                        <span class="material-symbols-outlined ml-2 !text-base">
                                            {{ $sortDirection === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
                                        </span>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider" scope="col">Yüzde</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @php
                            $maxClicks = collect($clicksByLink)->max('total_clicks') ?? 1;
                        @endphp
                        @foreach($clicksByLink as $link)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">{{ $link['short_link'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-white">{{ number_format($link['total_clicks']) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                <div class="w-full bg-gray-700 rounded-full h-2.5">
                                    <div class="bg-primary h-2.5 rounded-full" style="width: {{ ($link['total_clicks'] / $maxClicks) * 100 }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@push('scripts')
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    console.log("amCharts script starting...");

    var root = am5.Root.new("world-map");
    root.setThemes([am5themes_Animated.new(root)]);

    var chart = root.container.children.push(
        am5map.MapChart.new(root, {
            panX: "rotateX",
            panY: "translateY",
            projection: am5map.geoMercator(),
            homeGeoPoint: { latitude: 2, longitude: 2 }
        })
    );

    var polygonSeries = chart.series.push(
        am5map.MapPolygonSeries.new(root, {
            geoJSON: am5geodata_worldLow,
            exclude: ["AQ"]
        })
    );

    polygonSeries.mapPolygons.template.setAll({
        tooltipText: "{name}: {value}",
        toggleKey: "active",
        interactive: true,
        fill: am5.color(0xaaaaaa)
    });

    polygonSeries.mapPolygons.template.states.create("hover", {
        fill: am5.color(0x67e8f9)
    });

    

    function updateData(data) {
        console.log("Updating map data:", data);
        var heatData = [];
        if (data && data.labels && data.data) {
            for (var i = 0; i < data.labels.length; i++) {
                heatData.push({
                    id: data.labels[i],
                    value: data.data[i]
                });
            }
        }
        
        polygonSeries.set("heatRules", [{
            target: polygonSeries.mapPolygons.template,
            key: "fill",
            min: am5.color(0x1e3a8a),
            max: am5.color(0x67e8f9),
            dataField: "value",
            logarithmic: true
        }]);

        polygonSeries.data.setAll(heatData);
        console.log("Map data updated successfully.");
    }

    // Initial data load
    var initialData = @json($clicksByCountryChartData);
    updateData(initialData);

    // Livewire event listener
    Livewire.on('heatmap-data-updated', event => {
        console.log("Received heatmap-data-updated event from Livewire.");
        updateData(event[0].data);
    });
});
</script>
@endpush
