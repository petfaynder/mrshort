<?php

namespace App\Filament\Resources\DailySpinPrizeResource\Pages;

use App\Filament\Resources\DailySpinPrizeResource;
use Filament\Resources\Pages\ListRecords;

class ListDailySpinPrizes extends ListRecords
{
    protected static string $resource = DailySpinPrizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}

