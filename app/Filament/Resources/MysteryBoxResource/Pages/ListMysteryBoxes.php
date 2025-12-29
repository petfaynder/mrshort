<?php

namespace App\Filament\Resources\MysteryBoxResource\Pages;

use App\Filament\Resources\MysteryBoxResource;
use Filament\Resources\Pages\ListRecords;

class ListMysteryBoxes extends ListRecords
{
    protected static string $resource = MysteryBoxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}

