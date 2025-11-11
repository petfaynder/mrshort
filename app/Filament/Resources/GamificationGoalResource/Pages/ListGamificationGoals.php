<?php

namespace App\Filament\Resources\GamificationGoalResource\Pages;

use App\Filament\Resources\GamificationGoalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGamificationGoals extends ListRecords
{
    protected static string $resource = GamificationGoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
