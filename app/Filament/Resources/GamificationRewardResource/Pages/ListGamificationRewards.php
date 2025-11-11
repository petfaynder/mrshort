<?php

namespace App\Filament\Resources\GamificationRewardResource\Pages;

use App\Filament\Resources\GamificationRewardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGamificationRewards extends ListRecords
{
    protected static string $resource = GamificationRewardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
