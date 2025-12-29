<?php

namespace App\Filament\Resources\UserAdCampaignResource\Pages;

use App\Filament\Resources\UserAdCampaignResource;
use Filament\Resources\Pages\ListRecords;

class ListUserAdCampaigns extends ListRecords
{
    protected static string $resource = UserAdCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
