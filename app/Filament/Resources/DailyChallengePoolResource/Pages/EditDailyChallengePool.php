<?php

namespace App\Filament\Resources\DailyChallengePoolResource\Pages;

use App\Filament\Resources\DailyChallengePoolResource;
use Filament\Resources\Pages\EditRecord;

class EditDailyChallengePool extends EditRecord
{
    protected static string $resource = DailyChallengePoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
