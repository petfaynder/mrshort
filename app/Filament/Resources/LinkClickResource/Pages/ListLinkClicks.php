<?php

namespace App\Filament\Resources\LinkClickResource\Pages;

use App\Filament\Resources\LinkClickResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLinkClicks extends ListRecords
{
    protected static string $resource = LinkClickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
