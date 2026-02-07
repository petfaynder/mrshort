<?php

namespace App\Filament\Resources\UserAdCampaignResource\Pages;

use App\Filament\Resources\UserAdCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserAdCampaign extends CreateRecord
{
    protected static string $resource = UserAdCampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['campaign_type'] = 'user'; // Ensure campaign type is set
        $data['is_active'] = true; // Auto-activate on create (or set based on approval)
        return $data;
    }
}
