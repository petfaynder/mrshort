<?php

namespace App\Filament\Resources\LinkSuggestionResource\Pages;

use App\Filament\Resources\LinkSuggestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLinkSuggestion extends EditRecord
{
    protected static string $resource = LinkSuggestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
