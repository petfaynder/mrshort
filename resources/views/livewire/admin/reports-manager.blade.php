<div wire:ignore>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Raporlar</h2>

    {{ $this->form }}

    {{-- Chart Visualization (Pasta Grafik) - Countries --}}
    <x-filament::card class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-700">Ülkelere Göre Tıklama Dağılımı</h4>
            <x-filament::button wire:click="exportCsv('countries')" color="primary" size="sm">CSV Export</x-filament::button>
        </div>
        @if ($clicksByCountryChartData['data'] && count($clicksByCountryChartData['data']) > 0)
            <canvas id="clicksByCountryChart"></canvas>
        @else
            <p class="text-gray-600">Seçilen tarih aralığında ülkelere göre tıklama verisi bulunmuyor.</p>
        @endif
    </x-filament::card>

    {{-- Time Trends Chart --}}
    <x-filament::card class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-700">Zamana Göre Tıklama Trendleri</h4>
            <x-filament::button wire:click="exportCsv('time_trends')" color="primary" size="sm">CSV Export</x-filament::button>
        </div>
        @if ($clicksOverTime->count() > 0)
            <canvas id="clicksOverTimeChart"></canvas>
        @else
            <p class="text-gray-600">Seçilen tarih aralığında zamana göre tıklama verisi bulunmuyor.</p>
        @endif
    </x-filament::card>

    {{-- Tables --}}
    <div class="space-y-6">
        <x-filament::card>
            <h4 class="text-lg font-semibold text-gray-700 mb-4">Tıklama Detayları</h4>
            {{ $this->table }}
        </x-filament::card>
    </div>

    @script
    <script>
        (function () { // Wrap in an IIFE
            // Chart for Clicks by Country
            const clicksByCountryChartData = @json($clicksByCountryChartData);
            if (clicksByCountryChartData.data && clicksByCountryChartData.data.length > 0) {
                const ctxCountry = document.getElementById('clicksByCountryChart').getContext('2d');
                new Chart(ctxCountry, {
                    type: 'pie',
                    data: {
                        labels: clicksByCountryChartData.labels,
                        datasets: [{
                            label: 'Tıklama Sayısı',
                            data: clicksByCountryChartData.data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)',
                                'rgba(100, 100, 100, 0.6)', // More colors if needed
                                'rgba(200, 150, 120, 0.6)',
                                'rgba(120, 200, 150, 0.6)',
                                'rgba(150, 120, 200, 0.6)',
                            ],
                            borderColor: [
                                 'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                 'rgba(100, 100, 100, 1)',
                                'rgba(200, 150, 120, 1)',
                                'rgba(120, 200, 150, 1)',
                                'rgba(150, 120, 200, 1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Ülkelere Göre Tıklama Dağılımı'
                            }
                        }
                    },
                });
            }

            // Chart for Clicks Over Time
            const clicksOverTimeData = @json($clicksOverTime);
             if (clicksOverTimeData.length > 0) {
                const ctxTime = document.getElementById('clicksOverTimeChart').getContext('2d');
                 const dates = clicksOverTimeData.map(item => item.click_date);
                 const totals = clicksOverTimeData.map(item => item.total);

                new Chart(ctxTime, {
                    type: 'line', // Line chart for trends
                    data: {
                        labels: dates,
                        datasets: [{
                            label: 'Günlük Tıklama Sayısı',
                            data: totals,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.1,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Zamana Göre Tıklama Trendleri'
                            }
                        },
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'day' // Adjust unit based on date range
                                },
                                title: {
                                    display: true,
                                    text: 'Tarih'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Tıklama Sayısı'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        })(); // End of IIFE
    </script>
    @endscript
</div>