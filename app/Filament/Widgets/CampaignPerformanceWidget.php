<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Models\AdCampaign;
use App\Models\LinkClick;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CampaignPerformanceWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Kampanya Performans Analitiği';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function getDescription(): ?string
    {
        return 'Son 30 gündeki kampanya performans trendleri';
    }

    protected function getData(): array
    {
        // Tarih filtresi uygula
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();

        // Günlük kampanya performans verilerini al
        $dailyData = $this->getDailyPerformanceData($startDate, $endDate);

        // Haftalık özet verilerini al
        $weeklyData = $this->getWeeklyPerformanceData($startDate, $endDate);

        return [
            'datasets' => [
                [
                    'label' => 'Günlük Gösterimler',
                    'data' => array_values($dailyData['impressions']),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Günlük Tıklanmalar',
                    'data' => array_values($dailyData['clicks']),
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'CTR (%)',
                    'data' => array_values($dailyData['ctr']),
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => false,
                    'tension' => 0.4,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => array_keys($dailyData['impressions']),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Gösterim / Tıklanma',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'CTR (%)',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];
    }

    protected function getDailyPerformanceData(string $startDate, string $endDate): array
    {
        $dateRange = collect();
        $currentDate = Carbon::parse($startDate);

        while ($currentDate <= Carbon::parse($endDate)) {
            $dateRange->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Günlük verileri doldur
        $dailyData = [
            'impressions' => [],
            'clicks' => [],
            'ctr' => [],
        ];

        foreach ($dateRange as $date) {
            $dayStart = Carbon::parse($date)->startOfDay();
            $dayEnd = Carbon::parse($date)->endOfDay();

            // Gösterimleri hesapla (LinkClick tablosundaki link_id ile ilişkilendir)
            // LinkClick'in doğrudan bir CampaignTemplateAd'e bağlı olmadığını varsayarak,
            // Link üzerinden CampaignTemplate'e ve dolayısıyla AdCampaign'e ulaşmaya çalışıyoruz.
            $impressions = LinkClick::whereBetween('created_at', [$dayStart, $dayEnd])
                ->whereHas('link', function ($query) {
                    $query->whereNotNull('campaign_template_id');
                })
                ->count();

            // Tıklanmaları hesapla (Şimdilik AdCampaign'in toplam tıklamalarını kullanıyoruz)
            // Daha sonra CampaignTemplateAd'ler için ayrı bir tıklama takibi eklenebilir.
            $clicks = AdCampaign::whereBetween('updated_at', [$dayStart, $dayEnd])
                ->sum('total_clicks');

            $ctr = $impressions > 0 ? ($clicks / $impressions) * 100 : 0;

            $dailyData['impressions'][$date] = $impressions;
            $dailyData['clicks'][$date] = $clicks;
            $dailyData['ctr'][$date] = round($ctr, 2);
        }

        return $dailyData;
    }

    protected function getWeeklyPerformanceData(string $startDate, string $endDate): array
    {
        // LinkClick'in doğrudan bir CampaignTemplateAd'e bağlı olmadığını varsayarak,
        // Link üzerinden CampaignTemplate'e ve dolayısıyla AdCampaign'e ulaşmaya çalışıyoruz.
        $totalImpressions = LinkClick::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('link', function ($query) {
                $query->whereNotNull('campaign_template_id');
            })
            ->count();

        // Şimdilik AdCampaign'in toplam tıklamalarını kullanıyoruz
        $totalClicks = AdCampaign::whereBetween('updated_at', [$startDate, $endDate])
            ->sum('total_clicks');

        $averageCtr = $totalImpressions > 0 ? ($totalClicks / $totalImpressions) * 100 : 0;

        return [
            'total_impressions' => $totalImpressions,
            'total_clicks' => $totalClicks,
            'average_ctr' => round($averageCtr, 2),
            'top_performing_campaign' => $this->getTopPerformingCampaign($startDate, $endDate), // Bu metodun da güncellenmesi gerekecek
        ];
    }

    protected function getTopPerformingCampaign(string $startDate, string $endDate): ?string
    {
        // AdStep ve StepAd kaldırıldığı için bu metodun mantığı tamamen değişmeli.
        // Şimdilik en çok tıklanan kampanyayı AdCampaign'in kendi total_clicks sütunundan bulabiliriz.
        return AdCampaign::whereBetween('updated_at', [$startDate, $endDate])
            ->orderByDesc('total_clicks')
            ->first()?->name;
    }

    protected function getStartDate(): string
    {
        $filter = $this->getPageFilters()['date_range'] ?? null;

        if ($filter && isset($filter['start'])) {
            return $filter['start'];
        }

        return now()->subDays(30)->format('Y-m-d');
    }

    protected function getEndDate(): string
    {
        $filter = $this->getPageFilters()['date_range'] ?? null;

        if ($filter && isset($filter['end'])) {
            return $filter['end'];
        }

        return now()->format('Y-m-d');
    }

    protected function getPageFilters(): array
    {
        // Filament sayfa filtrelerini al
        return request()->query('filters', []);
    }
}
