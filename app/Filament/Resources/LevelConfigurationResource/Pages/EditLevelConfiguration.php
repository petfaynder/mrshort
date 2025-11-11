<?php

namespace App\Filament\Resources\LevelConfigurationResource\Pages;

use App\Filament\Resources\LevelConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLevelConfiguration extends EditRecord
{
    protected static string $resource = LevelConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
