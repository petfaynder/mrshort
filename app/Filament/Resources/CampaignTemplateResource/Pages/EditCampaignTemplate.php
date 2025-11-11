<?php

namespace App\Filament\Resources\CampaignTemplateResource\Pages;

use App\Filament\Resources\CampaignTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCampaignTemplate extends EditRecord
{
    protected static string $resource = CampaignTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}