<?php

namespace App\Filament\Resources\MysteryBoxResource\Pages;

use App\Filament\Resources\MysteryBoxResource;
use Filament\Resources\Pages\EditRecord;

class EditMysteryBox extends EditRecord
{
    protected static string $resource = MysteryBoxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}

