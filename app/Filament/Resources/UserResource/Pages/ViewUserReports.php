<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\Page;

class ViewUserReports extends Page
{
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.view-user-reports';

    public $record;
    public ?User $user = null;

    public function mount($record): void
    {
        $this->record = $record;
        $this->user = User::findOrFail($record);
    }

    public function getHeading(): string
    {
        return $this->user ? "Reports: {$this->user->name} (#{$this->user->id})" : 'User Reports';
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
