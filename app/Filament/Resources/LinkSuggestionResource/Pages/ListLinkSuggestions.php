<?php

namespace App\Filament\Resources\LinkSuggestionResource\Pages;

use App\Filament\Resources\LinkSuggestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLinkSuggestions extends ListRecords
{
    protected static string $resource = LinkSuggestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
