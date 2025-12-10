<?php

namespace App\Filament\Resources\StreakMilestoneResource\Pages;

use App\Filament\Resources\StreakMilestoneResource;
use Filament\Resources\Pages\ListRecords;

class ListStreakMilestones extends ListRecords
{
    protected static string $resource = StreakMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
