<?php

namespace App\Filament\Resources\CpmTierResource\Pages;

use App\Filament\Resources\CpmTierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCpmTier extends EditRecord
{
    protected static string $resource = CpmTierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
