<?php

namespace App\Filament\Resources\DmcaComplaintResource\Pages;

use App\Filament\Resources\DmcaComplaintResource;
use Filament\Resources\Pages\ListRecords;

class ListDmcaComplaints extends ListRecords
{
    protected static string $resource = DmcaComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
