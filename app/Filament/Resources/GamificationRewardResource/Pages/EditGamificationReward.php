<?php

namespace App\Filament\Resources\GamificationRewardResource\Pages;

use App\Filament\Resources\GamificationRewardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGamificationReward extends EditRecord
{
    protected static string $resource = GamificationRewardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
