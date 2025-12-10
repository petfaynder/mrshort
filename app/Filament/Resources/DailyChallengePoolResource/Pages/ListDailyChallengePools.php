<?php

namespace App\Filament\Resources\DailyChallengePoolResource\Pages;

use App\Filament\Resources\DailyChallengePoolResource;
use Filament\Resources\Pages\ListRecords;

class ListDailyChallengePools extends ListRecords
{
    protected static string $resource = DailyChallengePoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
