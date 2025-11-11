<?php

namespace App\Filament\Resources\CpmTierResource\Pages;

use App\Filament\Resources\CpmTierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCpmTiers extends ListRecords
{
    protected static string $resource = CpmTierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
