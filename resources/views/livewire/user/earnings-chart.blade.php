<div x-data="chartComponentData()">
    <div class="grid grid-cols-1 gap-8 mb-8">
        <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark">Statistics Visualization</h3>
            </div>
            <div class="h-[400px]" id="chart" wire:ignore></div>
        </div>
        <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Daily Statistics</h3>
            @if (count($statsData) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                    @foreach (array_slice(array_reverse($statsData), 0, 5) as $dayStats)
                        <div class="p-4 bg-background-light dark:bg-background-dark rounded-lg">
                            <p class="font-semibold text-heading-light dark:text-heading-dark">{{ \Carbon\Carbon::parse($dayStats['date'])->format('l, d M') }}</p>
                            <p class="text-sm text-text-light dark:text-text-dark">{{ $dayStats['views'] }} Clicks</p>
                            <div class="mt-2 text-right">
                                <p class="font-bold text-green-500">${{ number_format($dayStats['publisher_earnings'], 2) }}</p>
                                <p class="text-sm text-text-light dark:text-text-dark">${{ number_format($dayStats['cpm'], 2) }} CPM</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400">No daily statistics available.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function chartComponentData() {
        return {
            chart: null,
            init() {
                const initialData = @json($statsData);
                this.renderChart(initialData);

                Livewire.on('chartDataUpdated', event => {
                    this.updateChart(event.data);
                });
            },
            updateChart(data) {
                if (!this.chart) return;
                
                this.chart.updateOptions({
                    series: [
                        { name: 'Views', data: data.map(item => item.views) },
                        { name: 'Earnings', data: data.map(item => parseFloat(item.publisher_earnings).toFixed(2)) },
                        { name: 'CPM', data: data.map(item => parseFloat(item.cpm).toFixed(2)) }
                    ],
                    xaxis: {
                        categories: data.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }))
                    }
                });
            },
            renderChart(chartData) {
                const chartElement = document.querySelector("#chart");
                if (!chartElement) return;

                const isDarkMode = document.documentElement.classList.contains('dark');
                const options = {
                    chart: {
                        type: 'area',
                        height: '100%',
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    series: [
                        { name: 'Views', data: chartData.map(item => item.views) },
                        { name: 'Earnings', data: chartData.map(item => parseFloat(item.publisher_earnings).toFixed(2)) },
                        { name: 'CPM', data: chartData.map(item => parseFloat(item.cpm).toFixed(2)) }
                    ],
                    xaxis: {
                        categories: chartData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                        labels: { style: { colors: isDarkMode ? '#94a3b8' : '#64748b' } },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: [
                        {
                            seriesName: 'Views',
                            title: { text: 'Views', style: { color: isDarkMode ? '#94a3b8' : '#64748b' } },
                            labels: { style: { colors: isDarkMode ? '#94a3b8' : '#64748b' } }
                        },
                        {
                            seriesName: 'Earnings',
                            opposite: true,
                            title: { text: 'Earnings ($)', style: { color: isDarkMode ? '#94a3b8' : '#64748b' } },
                            labels: { style: { colors: isDarkMode ? '#94a3b8' : '#64748b' } }
                        },
                        {
                            seriesName: 'CPM',
                            show: false,
                            opposite: true,
                            title: { text: 'CPM ($)', style: { color: isDarkMode ? '#94a3b8' : '#64748b' } },
                        }
                    ],
                    colors: ['#3b82f6', '#10b981', '#f59e0b'],
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    grid: { borderColor: isDarkMode ? '#334155' : '#e2e8f0', strokeDashArray: 5 },
                    fill: {
                        type: "gradient",
                        gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] }
                    },
                    tooltip: {
                        theme: isDarkMode ? 'dark' : 'light',
                        shared: true,
                        intersect: false,
                    },
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center',
                        labels: { colors: isDarkMode ? '#ffffff' : '#0f172a' }
                    }
                };

                this.chart = new ApexCharts(chartElement, options);
                this.chart.render();
            }
        }
    }
</script>
@endpush
