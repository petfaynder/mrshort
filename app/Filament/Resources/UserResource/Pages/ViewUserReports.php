<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;

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
            Action::make('back_to_user')
                ->label('Back to User')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => UserResource::getUrl('edit', ['record' => $this->record])),

            Action::make('send_warning')
                ->label('Send Warning')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\Textarea::make('warning_message')
                        ->label('Warning Message')
                        ->required()
                        ->rows(4)
                        ->placeholder('Your account has been flagged for suspicious activity...'),
                ])
                ->action(function (array $data): void {
                    if ($this->user) {
                        \App\Models\Ticket::create([
                            'user_id'  => $this->user->id,
                            'subject'  => '[Admin Warning] Account Activity Review',
                            'message'  => $data['warning_message'],
                            'status'   => 'open',
                            'category' => 'general',
                            'priority' => 'high',
                        ]);
                    }
                    \Filament\Notifications\Notification::make()
                        ->title('Warning sent to user')
                        ->success()
                        ->send();
                })
                ->modalHeading('Send Warning to User')
                ->modalDescription('This will create a high-priority support ticket visible to the user.'),

            Action::make('deactivate_user')
                ->label('Suspend User')
                ->icon('heroicon-o-no-symbol')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Suspend this user?')
                ->modalDescription('This will deactivate the user account. They will not be able to log in.')
                ->action(function (): void {
                    if ($this->user) {
                        $this->user->update(['is_active' => false]);
                        \Filament\Notifications\Notification::make()
                            ->title('User suspended')
                            ->warning()
                            ->send();
                    }
                })
                ->visible(fn () => $this->user?->is_active ?? false),
        ];
    }
}
