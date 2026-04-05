<?php

namespace App\Filament\Resources\LinkSuggestionResource\Pages;

use App\Filament\Resources\LinkSuggestionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLinkSuggestion extends CreateRecord
{
    protected static string $resource = LinkSuggestionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
