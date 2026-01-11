<?php

namespace App\Filament\Resources\FeedbackPostResource\Pages;

use App\Filament\Resources\FeedbackPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeedbackPosts extends ListRecords
{
    protected static string $resource = FeedbackPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
