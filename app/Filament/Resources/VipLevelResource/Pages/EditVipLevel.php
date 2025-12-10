<?php

namespace App\Filament\Resources\VipLevelResource\Pages;

use App\Filament\Resources\VipLevelResource;
use Filament\Resources\Pages\EditRecord;

class EditVipLevel extends EditRecord
{
    protected static string $resource = VipLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
