<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\Page;

class ViewUserReports extends Page
{
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.view-user-reports';

    public $record;

    public function mount($record): void
    {
        $this->record = $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}