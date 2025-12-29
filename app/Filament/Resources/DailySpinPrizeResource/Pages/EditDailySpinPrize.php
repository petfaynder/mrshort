<?php

namespace App\Filament\Resources\DailySpinPrizeResource\Pages;

use App\Filament\Resources\DailySpinPrizeResource;
use Filament\Resources\Pages\EditRecord;

class EditDailySpinPrize extends EditRecord
{
    protected static string $resource = DailySpinPrizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}

