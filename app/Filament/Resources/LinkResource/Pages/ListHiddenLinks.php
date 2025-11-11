<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListHiddenLinks extends ListRecords
{
    protected static string $resource = LinkResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('is_hidden', true);
    }

    public function getTitle(): string
    {
        return 'Hidden Links';
    }
}