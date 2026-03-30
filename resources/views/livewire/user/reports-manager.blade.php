<div>
    <main class="flex-1 mt-6">
        <div class="flex flex-wrap items-start justify-between gap-4 p-4">
            <div class="flex min-w-72 flex-col gap-3">
                <p class="text-white text-4xl font-black leading-tight tracking-[-0.033em]">Detailed Reports</p>
                <p class="text-gray-400 text-base font-normal leading-normal">Analyze the combined statistics of all your links.</p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-medium text-gray-400" for="start-date">Start Date</label>
                    <input wire:model.live="startDate" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-700 bg-gray-800 h-10 placeholder:text-gray-400 px-4 text-sm font-normal" id="start-date" type="date"/>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-medium text-gray-400" for="end-date">End Date</label>
                    <input wire:model.live="endDate" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-700 bg-gray-800 h-10 placeholder:text-gray-400 px-4 text-sm font-normal" id="end-date" type="date"/>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-medium text-gray-400" for="quick-select">Quick Select</label>
                    <select wire:model.live="selectedPreset" class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-700 bg-gray-800 h-10 placeholder:text-gray-400 px-3 text-sm font-normal" id="quick-select">
                        <option value="last_7_days">Last 7 Days</option>
                        <option value="last_30_days">Last 30 Days</option>
                        <option value="last_90_days">Last 3 Months</option>
                        <option value="last_365_days">Last 1 Year</option>
                        <option value="all_time">All Time</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mt-6 bg-gray-900 rounded-xl border border-gray-800">
            <div class="p-4 flex flex-wrap justify-between items-center gap-4 border-b border-gray-800">
                <h3 class="text-lg font-bold text-white">Geographic Density Map</h3>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span>Low</span>
                    <div class="h-3 w-32 rounded-full" style="background: linear-gradient(to right, #2c3e50, #00f2ff);"></div>
                    <span>High</span>
                    <button wire:click="exportCsv('countries')" wire:loading.attr="disabled" class="flex items-center gap-2 font-medium hover:text-white ml-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="exportCsv('countries')" class="material-symbols-outlined !text-base">download</span>
                        <span wire:loading wire:target="exportCsv('countries')" class="material-symbols-outlined !text-base animate-spin">progress_activity</span>
                    </button>
                </div>
            </div>
            <div wire:ignore class="p-4 relative min-h-[400px]" style="background-color: #101922;">
                <div id="user-reports-worldmap" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <div class="bg-gray-900 rounded-xl border border-gray-800">
                <div class="p-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Device Types</h3>
                    <button wire:click="exportCsv('device_types')" wire:loading.attr="disabled" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="exportCsv('device_types')" class="material-symbols-outlined !text-base">download</span>
                        <span wire:loading wire:target="exportCsv('device_types')" class="material-symbols-outlined !text-base animate-spin">progress_activity</span>
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
                            <span class="text-sm font-medium text-white">{{ $device['device_type'] ?? 'Other' }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-300">{{ number_format($device['total']) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gray-900 rounded-xl border border-gray-800">
                <div class="p-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Operating Systems</h3>
                    <button wire:click="exportCsv('operating_systems')" wire:loading.attr="disabled" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="exportCsv('operating_systems')" class="material-symbols-outlined !text-base">download</span>
                        <span wire:loading wire:target="exportCsv('operating_systems')" class="material-symbols-outlined !text-base animate-spin">progress_activity</span>
                    </button>
                </div>
                <ul class="divide-y divide-gray-800">
                    @foreach($clicksByOs as $os)
                    <li class="p-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-white">{{ $os['os'] ?? 'Unknown' }}</span>
                        <span class="text-sm font-semibold text-gray-300">{{ number_format($os['total']) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gray-900 rounded-xl border border-gray-800">
                <div class="p-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Browsers</h3>
                    <button wire:click="exportCsv('browsers')" wire:loading.attr="disabled" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="exportCsv('browsers')" class="material-symbols-outlined !text-base">download</span>
                        <span wire:loading wire:target="exportCsv('browsers')" class="material-symbols-outlined !text-base animate-spin">progress_activity</span>
                    </button>
                </div>
                <ul class="divide-y divide-gray-800">
                    @foreach($clicksByBrowser as $browser)
                    <li class="p-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-white">{{ $browser['browser'] ?? 'Unknown' }}</span>
                        <span class="text-sm font-semibold text-gray-300">{{ number_format($browser['total']) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-900 rounded-xl border border-gray-800">
                <div class="p-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Referring Domains</h3>
                    <button wire:click="exportCsv('referrers')" wire:loading.attr="disabled" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="exportCsv('referrers')" class="material-symbols-outlined !text-base">download</span>
                        <span wire:loading wire:target="exportCsv('referrers')" class="material-symbols-outlined !text-base animate-spin">progress_activity</span>
                    </button>
                </div>
                <ul class="divide-y divide-gray-800">
                    @foreach($clicksByReferrer as $referrer)
                    <li class="p-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-white">{{ $referrer['referrer'] ?? 'Direct' }}</span>
                        <span class="text-sm font-semibold text-gray-300">{{ number_format($referrer['total']) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gray-900 rounded-xl border border-gray-800">
                <div class="p-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Top Clicked Countries</h3>
                    <button wire:click="exportCsv('countries_table')" wire:loading.attr="disabled" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="exportCsv('countries_table')" class="material-symbols-outlined !text-base">download</span>
                        <span wire:loading wire:target="exportCsv('countries_table')" class="material-symbols-outlined !text-base animate-spin">progress_activity</span>
                    </button>
                </div>
                <ul class="divide-y divide-gray-800">
                    @foreach(collect($clicksByCountryChartData['labels'])->zip($clicksByCountryChartData['data'])->sortByDesc(1)->take(4) as $country)
                    <li class="p-4 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            @if($country[0] && $country[0] !== 'Unknown')
                                <span class="fi fi-{{ strtolower($country[0]) }}"></span>
                            @endif
                            <span class="text-sm font-medium text-white">{{ $country[0] ?? 'Unknown' }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-300">{{ number_format($country[1]) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-900 rounded-xl border border-gray-800 md:col-span-2 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-bold text-white">Human Clicks</h3>
                    <button wire:click="exportCsv('bot_status')" wire:loading.attr="disabled" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="exportCsv('bot_status')" class="material-symbols-outlined !text-base">download</span>
                        <span wire:loading wire:target="exportCsv('bot_status')" class="material-symbols-outlined !text-base animate-spin">progress_activity</span>
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
                            <p class="text-sm text-gray-400">real user clicks</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-bold text-white">Bot Clicks</h3>
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
                    <p class="text-sm text-gray-400">blocked clicks</p>
                </div>
                <div class="mt-4 flex items-center gap-4">
                    <span class="material-symbols-outlined text-red-accent !text-4xl">smart_toy</span>
                    <div class="w-full">
                        <p class="text-sm text-gray-300">{{ number_format($botPercentage, 1) }}% of total clicks</p>
                        <div class="mt-1 h-2 w-full rounded-full bg-gray-700">
                            <div class="h-2 rounded-full bg-red-accent" style="width: {{ $botPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 bg-gray-900 rounded-xl border border-gray-800">
            <div class="p-4 flex justify-between items-center border-b border-gray-800">
                <h3 class="text-lg font-bold text-white">Clicks by Links</h3>
                <button wire:click="exportCsv('links')" wire:loading.attr="disabled" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="exportCsv('links')" class="material-symbols-outlined !text-base">download</span>
                    <span wire:loading wire:target="exportCsv('links')" class="material-symbols-outlined !text-base animate-spin">progress_activity</span>
                    <span>CSV Export</span>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th wire:click="sortBy('short_link')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider" scope="col">
                                <div class="flex items-center">
                                    <span>Short Link</span>
                                    @if($sortBy === 'short_link')
                                        <span class="material-symbols-outlined ml-2 !text-base">
                                            {{ $sortDirection === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
                                        </span>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('total_clicks')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider" scope="col">
                                <div class="flex items-center">
                                    <span>Clicks</span>
                                    @if($sortBy === 'total_clicks')
                                        <span class="material-symbols-outlined ml-2 !text-base">
                                            {{ $sortDirection === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
                                        </span>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider" scope="col">Percentage</th>
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

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<script>
(function() {
    var clicksData = @json($clicksByCountryChartData);
    var mapRoot = null;
    var mapPolygonSeries = null;

    function initWorldMap() {
        var el = document.getElementById('user-reports-worldmap');
        if (!el) { setTimeout(initWorldMap, 100); return; }
        if (typeof am5 === 'undefined' || typeof am5map === 'undefined' ||
            typeof am5geodata_worldLow === 'undefined' || typeof am5themes_Animated === 'undefined') {
            setTimeout(initWorldMap, 100);
            return;
        }
        if (mapRoot) return;
        if (el._amcharts) return;
        el._amcharts = true;

        try {

        mapRoot = am5.Root.new(el);
        mapRoot.setThemes([am5themes_Animated.new(mapRoot)]);

        var chart = mapRoot.container.children.push(
            am5map.MapChart.new(mapRoot, {
                panX: "rotateX",
                panY: "translateY",
                projection: am5map.geoMercator(),
                homeGeoPoint: { latitude: 2, longitude: 2 }
            })
        );

        mapPolygonSeries = chart.series.push(
            am5map.MapPolygonSeries.new(mapRoot, {
                geoJSON: am5geodata_worldLow,
                exclude: ["AQ"]
            })
        );

        mapPolygonSeries.mapPolygons.template.setAll({
            tooltipText: "{name}: {value} Clicks",
            toggleKey: "active",
            interactive: true,
            fill: am5.color(0xaaaaaa)
        });

        mapPolygonSeries.mapPolygons.template.states.create("hover", {
            fill: am5.color(0x00f2ff)
        });

        mapPolygonSeries.heatRules.push({
            target: mapPolygonSeries.mapPolygons.template,
            dataField: "value",
            min: am5.color(0x2c3e50),
            max: am5.color(0x00f2ff),
            key: "fill"
        });

        updateMapData(clicksData);
        } catch(e) {
            el._amcharts = false;
            setTimeout(initWorldMap, 200);
        }
    }

    function updateMapData(data) {
        if (!mapPolygonSeries) return;
        var heatData = [];
        if (data && data.labels && data.data) {
            for (var i = 0; i < data.labels.length; i++) {
                heatData.push({ id: data.labels[i], value: Number(data.data[i]) });
            }
        }
        mapPolygonSeries.data.setAll(heatData);
    }

    initWorldMap();

    if (typeof Livewire !== 'undefined') {
        Livewire.on('heatmap-data-updated', function(event) {
            updateMapData(event[0].data);
        });
    }
})();
</script>
