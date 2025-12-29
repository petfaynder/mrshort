<?php

namespace App\Filament\Resources\VipLevelResource\Pages;

use App\Filament\Resources\VipLevelResource;
use Filament\Resources\Pages\ListRecords;

class ListVipLevels extends ListRecords
{
    protected static string $resource = VipLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}

