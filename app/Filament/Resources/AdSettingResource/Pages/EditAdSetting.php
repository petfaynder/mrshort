<?php

namespace App\Filament\Resources\AdSettingResource\Pages;

use App\Filament\Resources\AdSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdSetting extends EditRecord
{
    protected static string $resource = AdSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
