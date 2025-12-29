<?php

namespace App\Filament\Resources\StreakMilestoneResource\Pages;

use App\Filament\Resources\StreakMilestoneResource;
use Filament\Resources\Pages\EditRecord;

class EditStreakMilestone extends EditRecord
{
    protected static string $resource = StreakMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}

