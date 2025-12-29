<?php

namespace App\Filament\Resources\UserAdCampaignResource\Pages;

use App\Filament\Resources\UserAdCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserAdCampaign extends EditRecord
{
    protected static string $resource = UserAdCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
