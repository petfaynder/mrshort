<?php

namespace App\Filament\Resources\AdSettingResource\Pages;

use App\Filament\Resources\AdSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdSettings extends ListRecords
{
    protected static string $resource = AdSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
