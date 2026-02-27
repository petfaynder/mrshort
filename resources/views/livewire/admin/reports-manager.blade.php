<div>
    {{-- Filtre Bölümü --}}
    <div class="mb-6">
        {{ $this->form }}
    </div>

    {{-- İstatistik Kartları --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $totalClicks = $this->clicksByDeviceType->sum('total');
            $humanClicks = $this->clicksByBotStatus->where('is_bot', false)->first()->total ?? 0;
            $botClicks = $this->clicksByBotStatus->where('is_bot', true)->first()->total ?? 0;
            $topCountry = collect($this->clicksByCountryChartData['labels'])->zip($this->clicksByCountryChartData['data'])->sortByDesc(1)->first();
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
        {{-- Coğrafi Yoğunluk Haritası (Geographic Density Map) --}}
        <x-filament::card class="lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Coğrafi Yoğunluk Haritası</h3>
                
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span>Düşük</span>
                    <div class="h-3 w-32 rounded-full" style="background: linear-gradient(to right, #2c3e50, #00f2ff);"></div>
                    <span>Yüksek</span>
                    
                    <x-filament::button wire:click="exportCsv('countries')" size="sm" color="gray" class="ml-4">
                        CSV İndir
                    </x-filament::button>
                </div>
            </div>
            <div 
                wire:ignore 
                x-data="worldMapChart({ clicksData: @js($this->clicksByCountryChartData) })" 
                class="p-4 relative min-h-[400px] rounded-lg" 
                style="background-color: #101922;"
            >
                <div x-ref="mapdiv" style="width: 100%; height: 500px;"></div>
            </div>
        </x-filament::card>
        <x-filament::card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Günlük Tıklama Trendleri</h3>
                <x-filament::button wire:click="exportCsv('time_trends')" size="sm" color="gray">
                    CSV Export
                </x-filament::button>
            </div>
            @if($this->clicksOverTime->count() > 0)
                <div 
                wire:ignore 
                x-data="timeTrendsChart({ trendData: @js($this->clicksOverTime) })" 
                class="p-4 relative min-h-[350px]"
            >
                <div x-ref="chartdiv" style="width: 100%; height: 350px;"></div>
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
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">devices</span>
                    <h3 class="text-lg font-bold text-white">Cihazlar</h3>
                </div>
                <button wire:click="exportCsv('device_types')" class="text-gray-400 hover:text-white transition">
                    <span class="material-symbols-outlined !text-xl">download</span>
                </button>
            </div>
            <div class="space-y-4">
                @php $maxDevice = $this->clicksByDeviceType->max('total') ?? 1; @endphp
                @forelse($this->clicksByDeviceType as $device)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-200">{{ $device->device_type ?? 'Diğer' }}</span>
                        <span class="text-gray-400">{{ number_format($device->total) }}</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-1.5">
                        <div class="bg-primary h-1.5 rounded-full" style="width: {{ ($device->total / $maxDevice) * 100 }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">Veri yok</p>
                @endforelse
            </div>
        </x-filament::card>

        {{-- İşletim Sistemleri --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500">computer</span>
                    <h3 class="text-lg font-bold text-white">İşletim Sistemleri</h3>
                </div>
                <button wire:click="exportCsv('operating_systems')" class="text-gray-400 hover:text-white transition">
                    <span class="material-symbols-outlined !text-xl">download</span>
                </button>
            </div>
            <div class="space-y-4 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                @php $maxOs = $this->clicksByOs->max('total') ?? 1; @endphp
                @forelse($this->clicksByOs as $os)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-200">{{ $os->os ?? 'Bilinmiyor' }}</span>
                        <span class="text-gray-400">{{ number_format($os->total) }}</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-1.5">
                        <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ ($os->total / $maxOs) * 100 }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">Veri yok</p>
                @endforelse
            </div>
        </x-filament::card>

        {{-- Tarayıcılar --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-500">public</span>
                    <h3 class="text-lg font-bold text-white">Tarayıcılar</h3>
                </div>
                <button wire:click="exportCsv('browsers')" class="text-gray-400 hover:text-white transition">
                    <span class="material-symbols-outlined !text-xl">download</span>
                </button>
            </div>
            <div class="space-y-4 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                @php $maxBrowser = $this->clicksByBrowser->max('total') ?? 1; @endphp
                @forelse($this->clicksByBrowser as $browser)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-200">{{ $browser->browser ?? 'Bilinmiyor' }}</span>
                        <span class="text-gray-400">{{ number_format($browser->total) }}</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-1.5">
                        <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ ($browser->total / $maxBrowser) * 100 }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">Veri yok</p>
                @endforelse
            </div>
        </x-filament::card>
    </div>

    {{-- 2 Sütunlu Kartlar --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Yönlendiren Domainler --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-yellow-500">link</span>
                    <h3 class="text-lg font-bold text-white">Yönlendiren Domainler (Referrers)</h3>
                </div>
                <button wire:click="exportCsv('referrers')" class="text-gray-400 hover:text-white transition">
                    <span class="material-symbols-outlined !text-xl">download</span>
                </button>
            </div>
            <div class="space-y-4 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                @php $maxRef = $this->clicksByReferrer->max('total') ?? 1; @endphp
                @forelse($this->clicksByReferrer as $referrer)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-200 truncate max-w-[200px]">{{ $referrer->referrer ?? 'Direkt (Katalog Dışı)' }}</span>
                        <span class="text-gray-400">{{ number_format($referrer->total) }}</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-1.5">
                        <div class="bg-yellow-500 h-1.5 rounded-full" style="width: {{ ($referrer->total / $maxRef) * 100 }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">Veri yok</p>
                @endforelse
            </div>
        </x-filament::card>

        {{-- En Çok Tıklanan Ülkeler (Side Panel) --}}
        <x-filament::card>
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-400">flag</span>
                    <h3 class="text-lg font-bold text-white">Top 10 Ülke</h3>
                </div>
                <button wire:click="exportCsv('countries_table')" class="text-gray-400 hover:text-white transition">
                    <span class="material-symbols-outlined !text-xl">download</span>
                </button>
            </div>
            <div class="space-y-4 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                @php
                    $countriesList = collect($this->clicksByCountryChartData['labels'])->zip($this->clicksByCountryChartData['data'])->sortByDesc(1)->take(10);
                    $maxCountry = $countriesList->max(1) ?? 1;
                @endphp
                @forelse($countriesList as $country)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <div class="flex items-center gap-2">
                            @if($country[0] && $country[0] !== 'Bilinmiyor')
                                <span class="fi fi-{{ strtolower(substr($country[0], 0, 2)) }}"></span>
                            @endif
                            <span class="font-medium text-gray-200">{{ $country[0] ?? 'Bilinmiyor' }}</span>
                        </div>
                        <span class="text-gray-400">{{ number_format($country[1]) }}</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-1.5">
                        <div class="bg-blue-400 h-1.5 rounded-full" style="width: {{ ($country[1] / $maxCountry) * 100 }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">Veri yok</p>
                @endforelse
            </div>
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
                    @forelse($this->clicksByLink as $link)
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
        @if($this->clicksByLink->total() > $this->clicksByLink->perPage())
        <div class="mt-4">
            {{ $this->clicksByLink->links() }}
        </div>
        @endif
    </x-filament::card>
</div>

@assets
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
@endassets

@script
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('worldMapChart', ({ clicksData }) => ({
            root: null,
            polygonSeries: null,
            init() {
                this.loadMap();
                
                Livewire.on('heatmap-data-updated', (event) => {
                    this.updateData(event[0].data);
                });
            },
            destroy() {
                if (this.root) {
                    this.root.dispose();
                }
            },
            loadMap() {
                if (typeof am5 === 'undefined') {
                    // Script belum dimuat sepenuhnya, tunggu 100ms
                    setTimeout(() => this.loadMap(), 100);
                    return;
                }
                
                this.root = am5.Root.new(this.$refs.mapdiv);
                this.root.setThemes([am5themes_Animated.new(this.root)]);

                let chart = this.root.container.children.push(
                    am5map.MapChart.new(this.root, {
                        panX: "rotateX",
                        panY: "translateY",
                        projection: am5map.geoMercator(),
                        homeGeoPoint: { latitude: 2, longitude: 2 }
                    })
                );

                this.polygonSeries = chart.series.push(
                    am5map.MapPolygonSeries.new(this.root, {
                        geoJSON: am5geodata_worldLow,
                        exclude: ["AQ"]
                    })
                );

                this.polygonSeries.mapPolygons.template.setAll({
                    tooltipText: "{name}: {value} Tıklama",
                    toggleKey: "active",
                    interactive: true,
                    fill: am5.color(0xaaaaaa)
                });

                this.polygonSeries.mapPolygons.template.states.create("hover", {
                    fill: am5.color(0x00f2ff)
                });

                this.polygonSeries.heatRules.push({
                    target: this.polygonSeries.mapPolygons.template,
                    dataField: "value",
                    min: am5.color(0x2c3e50),
                    max: am5.color(0x00f2ff),
                    key: "fill"
                });

                this.updateData(clicksData);
            },
            updateData(data) {
                if (!this.polygonSeries) return;
                var heatData = [];
                if (data && data.labels && data.data) {
                    for (var i = 0; i < data.labels.length; i++) {
                        heatData.push({
                            id: data.labels[i],
                            value: Number(data.data[i])
                        });
                    }
                }
                this.polygonSeries.data.setAll(heatData);
            }
        }));

        Alpine.data('timeTrendsChart', ({ trendData }) => ({
            root: null,
            series: null,
            xAxis: null,
            init() {
                this.loadChart();
                
                Livewire.on('timechart-data-updated', (event) => {
                    this.updateData(event[0].data);
                });
            },
            destroy() {
                if (this.root) {
                    this.root.dispose();
                }
            },
            loadChart() {
                if (typeof am5 === 'undefined' || typeof am5xy === 'undefined') {
                    setTimeout(() => this.loadChart(), 100);
                    return;
                }
                
                this.root = am5.Root.new(this.$refs.chartdiv);
                this.root.setThemes([am5themes_Animated.new(this.root)]);

                let chart = this.root.container.children.push(
                    am5xy.XYChart.new(this.root, {
                        panX: true,
                        panY: true,
                        wheelX: "panX",
                        wheelY: "zoomX",
                        pinchZoomX: true
                    })
                );

                let cursor = chart.set("cursor", am5xy.XYCursor.new(this.root, {
                    behavior: "none"
                }));
                cursor.lineY.set("visible", false);

                // Create axes
                let xAxis = chart.xAxes.push(
                    am5xy.DateAxis.new(this.root, {
                        maxDeviation: 0.2,
                        baseInterval: { timeUnit: "day", count: 1 },
                        renderer: am5xy.AxisRendererX.new(this.root, {
                            minGridDistance: 80,
                            minorGridEnabled: true
                        }),
                        tooltip: am5.Tooltip.new(this.root, {})
                    })
                );

                // Styling X Axis labels for dark theme
                xAxis.get("renderer").labels.template.setAll({
                    fill: am5.color(0x9ca3af),
                    fontSize: 12
                });

                let yAxis = chart.yAxes.push(
                    am5xy.ValueAxis.new(this.root, {
                        renderer: am5xy.AxisRendererY.new(this.root, {})
                    })
                );

                // Styling Y Axis labels for dark theme
                yAxis.get("renderer").labels.template.setAll({
                    fill: am5.color(0x9ca3af),
                    fontSize: 12
                });

                // Style Grid Lines
                xAxis.get("renderer").grid.template.setAll({
                    stroke: am5.color(0x374151),
                    strokeOpacity: 0.5
                });
                
                yAxis.get("renderer").grid.template.setAll({
                    stroke: am5.color(0x374151),
                    strokeOpacity: 0.5
                });

                // Add series
                let series = chart.series.push(
                    am5xy.SmoothedXLineSeries.new(this.root, {
                        name: "Clicks",
                        xAxis: xAxis,
                        yAxis: yAxis,
                        valueYField: "value",
                        valueXField: "date",
                        tooltip: am5.Tooltip.new(this.root, {
                            labelText: "{valueY} clicks"
                        }),
                        tension: 0.4
                    })
                );

                // Neon primary color glow
                series.strokes.template.setAll({
                    strokeWidth: 3,
                    stroke: am5.color(0x00f2ff),
                    shadowColor: am5.color(0x00f2ff),
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowOffsetY: 0,
                    shadowOpacity: 0.8
                });
                
                // Gradient Fill Under Line
                series.fills.template.setAll({
                    fillOpacity: 0.5,
                    visible: true,
                    fillGradient: am5.LinearGradient.new(this.root, {
                        stops: [
                            { color: am5.color(0x00f2ff), opacity: 0.5 },
                            { color: am5.color(0x00f2ff), opacity: 0 }
                        ],
                        rotation: 90
                    })
                });

                series.bullets.push(() => {
                    return am5.Bullet.new(this.root, {
                        sprite: am5.Circle.new(this.root, {
                            radius: 4,
                            fill: am5.color(0x101922),
                            stroke: am5.color(0x00f2ff),
                            strokeWidth: 2
                        })
                    });
                });

                this.series = series;
                this.updateData(trendData);
                
                // Appear animation
                series.appear(1000);
                chart.appear(1000, 100);
            },
            updateData(data) {
                if (!this.series) return;
                
                let chartData = [];
                if (data && data.length > 0) {
                    chartData = data.map(item => {
                        return {
                            date: new Date(item.click_date).getTime(),
                            value: Number(item.total)
                        };
                    });
                }
                
                this.series.data.setAll(chartData);
            }
        }));
    });
</script>
@endscript