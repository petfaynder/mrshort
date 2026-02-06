<?php

namespace App\Filament\Resources\DmcaComplaintResource\Pages;

use App\Filament\Resources\DmcaComplaintResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDmcaComplaint extends EditRecord
{
    protected static string $resource = DmcaComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
