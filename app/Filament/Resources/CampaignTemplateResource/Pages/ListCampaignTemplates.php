<?php

namespace App\Filament\Resources\CampaignTemplateResource\Pages;

use App\Filament\Resources\CampaignTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCampaignTemplates extends ListRecords
{
    protected static string $resource = CampaignTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}