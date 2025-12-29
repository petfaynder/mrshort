<?php

namespace App\Filament\Resources\DomainResource\Pages;

use App\Filament\Resources\DomainResource;
use App\Models\Domain;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDomain extends EditRecord
{
    protected static string $resource = DomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->hidden(fn (): bool => $this->record->is_active),
        ];
    }

    protected function afterSave(): void
    {
        // If this is set as active, deactivate others
        if ($this->record->is_active) {
            Domain::where('id', '!=', $this->record->id)->update(['is_active' => false]);
        }
    }
}

