<?php

namespace App\Filament\Resources\LinkClickResource\Widgets;

use App\Models\LinkClick;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClickStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalClicks = LinkClick::count();

        $paidClicks = LinkClick::where('cpm_rate', '>', 0)
            ->where('is_skipped', false)
            ->where(function ($q) {
                $q->where('is_bot', false)->orWhereNull('is_bot');
            })
            ->count();

        $sampledOut = LinkClick::where('is_skipped', true)->count();

        $totalPublisherEarnings = LinkClick::where('cpm_rate', '>', 0)
            ->where('is_skipped', false)
            ->sum('cpm_rate') / 1000;

        // Lost to sampling: what those sampled-out clicks WOULD have earned
        // We stored the original cpm_rate as 0 for skipped clicks, so we estimate
        // using the average CPM of paid clicks
        $avgCpm = $paidClicks > 0 
            ? LinkClick::where('cpm_rate', '>', 0)->where('is_skipped', false)->avg('cpm_rate') 
            : 0;
        $lostToSampling = ($sampledOut * $avgCpm) / 1000;

        return [
            Stat::make('Total Views', number_format($totalClicks))
                ->description('All recorded clicks')
                ->icon('heroicon-o-eye')
                ->color('primary'),

            Stat::make('Paid Views', number_format($paidClicks))
                ->description(($totalClicks > 0 ? round(($paidClicks / $totalClicks) * 100, 1) : 0) . '% of total')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Sampled Out', number_format($sampledOut))
                ->description('Eligible but excluded by click sampling')
                ->icon('heroicon-o-funnel')
                ->color('warning'),

            Stat::make('Publisher Earnings', '$' . number_format($totalPublisherEarnings, 4))
                ->description('Total paid out to publishers')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Lost to Sampling', '$' . number_format($lostToSampling, 4))
                ->description('Estimated potential earnings lost')
                ->icon('heroicon-o-arrow-trending-down')
                ->color('warning'),
        ];
    }
}
