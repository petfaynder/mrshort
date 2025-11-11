<?php

namespace App\Filament\Resources\AdminLinkClickReportResource\Pages;

use App\Filament\Resources\AdminLinkClickReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminLinkClickReports extends ListRecords
{
    protected static string $resource = AdminLinkClickReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
