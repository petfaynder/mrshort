<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon; // Add this line

class ListInactiveLinks extends ListRecords
{
    protected static string $resource = LinkResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('expires_at', '<', Carbon::now())->whereNotNull('expires_at');
    }

    public function getTitle(): string
    {
        return 'Inactive Links';
    }
}