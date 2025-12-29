<?php

namespace App\Filament\Resources\DomainResource\Pages;

use App\Filament\Resources\DomainResource;
use App\Models\Domain;
use Filament\Resources\Pages\CreateRecord;

class CreateDomain extends CreateRecord
{
    protected static string $resource = DomainResource::class;

    protected function afterCreate(): void
    {
        // If this is set as active, deactivate others
        if ($this->record->is_active) {
            Domain::where('id', '!=', $this->record->id)->update(['is_active' => false]);
        }
        
        // If this is the first domain, make it active
        if (Domain::count() === 1) {
            $this->record->update(['is_active' => true]);
        }
    }
}

