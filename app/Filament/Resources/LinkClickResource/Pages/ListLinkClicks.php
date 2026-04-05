<?php

namespace App\Filament\Resources\LinkClickResource\Pages;

use App\Filament\Resources\LinkClickResource;
use App\Models\LinkClick;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLinkClicks extends ListRecords
{
    protected static string $resource = LinkClickResource::class;

    protected function getHeaderActions(): array
    {
        return []; // No create button — clicks are log data
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Clicks')
                ->badge(fn () => LinkClick::count())
                ->badgeColor('gray'),

            'paid' => Tab::make('Paid')
                ->badge(fn () => LinkClick::where('cpm_rate', '>', 0)->where('is_skipped', false)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cpm_rate', '>', 0)->where('is_skipped', false)),

            'sampled_out' => Tab::make('Sampled Out')
                ->badge(fn () => LinkClick::where('is_skipped', true)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_skipped', true)),

            'unpaid' => Tab::make('Unpaid')
                ->badge(fn () => LinkClick::where('cpm_rate', '<=', 0)->where('is_skipped', false)->where(function ($q) {
                    $q->where('is_bot', false)->orWhereNull('is_bot');
                })->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cpm_rate', '<=', 0)->where('is_skipped', false)->where(function ($q) {
                    $q->where('is_bot', false)->orWhereNull('is_bot');
                })),

            'bots' => Tab::make('Bots')
                ->badge(fn () => LinkClick::where('is_bot', true)->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_bot', true)),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\LinkClickResource\Widgets\ClickStatsOverview::class,
        ];
    }
}
