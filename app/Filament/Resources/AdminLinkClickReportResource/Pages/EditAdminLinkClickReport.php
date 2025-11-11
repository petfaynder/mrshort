<?php

namespace App\Filament\Resources\AdminLinkClickReportResource\Pages;

use App\Filament\Resources\AdminLinkClickReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminLinkClickReport extends EditRecord
{
    protected static string $resource = AdminLinkClickReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
