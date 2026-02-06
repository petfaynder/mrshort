<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DmcaComplaintResource\Pages;
use App\Models\DmcaComplaint;
use App\Models\Link;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class DmcaComplaintResource extends Resource
{
    protected static ?string $model = DmcaComplaint::class;

    protected static ?string $slug = 'dmca-complaints';

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?string $navigationLabel = 'DMCA Complaints';

    protected static ?string $navigationGroup = 'Support';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'DMCA Complaint';

    protected static ?string $pluralModelLabel = 'DMCA Complaints';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Complaint Information')
                    ->schema([
                        Forms\Components\TextInput::make('link_code')
                            ->label('Link Code')
                            ->disabled(),
                        Forms\Components\Textarea::make('original_url')
                            ->label('Target URL')
                            ->disabled()
                            ->rows(2),
                        Forms\Components\Select::make('complaint_type')
                            ->label('Complaint Type')
                            ->options(DmcaComplaint::complaintTypeLabels())
                            ->disabled(),
                        Forms\Components\TextInput::make('reporter_name')
                            ->label('Reporter Name')
                            ->disabled(),
                        Forms\Components\TextInput::make('reporter_email')
                            ->label('Reporter Email')
                            ->disabled(),
                        Forms\Components\TextInput::make('reporter_ip')
                            ->label('IP Address')
                            ->disabled(),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->disabled()
                            ->rows(4),
                    ])->columns(2),

                Forms\Components\Section::make('Management')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(DmcaComplaint::statusLabels())
                            ->required(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3)
                            ->helperText('This field is only visible to admins.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('link_code')
                    ->label('Link')
                    ->searchable()
                    ->url(fn (DmcaComplaint $record): string => url('/' . $record->link_code), shouldOpenInNewTab: true)
                    ->color('primary'),
                Tables\Columns\TextColumn::make('complaint_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => DmcaComplaint::complaintTypeLabels()[$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'copyright' => 'warning',
                        'malware' => 'danger',
                        'illegal' => 'danger',
                        'phishing' => 'danger',
                        'sexual_content' => 'danger',
                        'other' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('reporter_name')
                    ->label('Reporter')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reporter_email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => DmcaComplaint::statusLabels()[$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'reviewing' => 'info',
                        'resolved' => 'success',
                        'rejected' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(DmcaComplaint::statusLabels()),
                Tables\Filters\SelectFilter::make('complaint_type')
                    ->label('Complaint Type')
                    ->options(DmcaComplaint::complaintTypeLabels()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('block_link')
                    ->label('Block Link')
                    ->icon('heroicon-o-no-symbol')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Block Link')
                    ->modalDescription('This link will be blocked and will no longer redirect. Are you sure?')
                    ->visible(fn (DmcaComplaint $record): bool => $record->link && !$record->link->is_blocked)
                    ->action(function (DmcaComplaint $record) {
                        if ($record->link) {
                            $record->link->update(['is_blocked' => true]);
                            $record->update([
                                'status' => 'resolved',
                                'resolved_at' => now(),
                                'admin_notes' => ($record->admin_notes ? $record->admin_notes . "\n" : '') . '[' . now()->format('d.m.Y H:i') . '] Link blocked.',
                            ]);
                            Notification::make()
                                ->title('Link successfully blocked')
                                ->success()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('delete_link')
                    ->label('Delete Link')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Link')
                    ->modalDescription('This link will be permanently deleted. This action cannot be undone. Are you sure?')
                    ->visible(fn (DmcaComplaint $record): bool => $record->link !== null)
                    ->action(function (DmcaComplaint $record) {
                        if ($record->link) {
                            $record->link->delete();
                            $record->update([
                                'status' => 'resolved',
                                'resolved_at' => now(),
                                'link_id' => null,
                                'admin_notes' => ($record->admin_notes ? $record->admin_notes . "\n" : '') . '[' . now()->format('d.m.Y H:i') . '] Link deleted.',
                            ]);
                            Notification::make()
                                ->title('Link successfully deleted')
                                ->success()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Complaint')
                    ->modalDescription('This complaint will be rejected. Are you sure?')
                    ->visible(fn (DmcaComplaint $record): bool => $record->status !== 'rejected')
                    ->action(function (DmcaComplaint $record) {
                        $record->update([
                            'status' => 'rejected',
                            'resolved_at' => now(),
                            'admin_notes' => ($record->admin_notes ? $record->admin_notes . "\n" : '') . '[' . now()->format('d.m.Y H:i') . '] Complaint rejected.',
                        ]);
                        Notification::make()
                            ->title('Complaint rejected')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_resolved')
                        ->label('Mark as Resolved')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update([
                            'status' => 'resolved',
                            'resolved_at' => now(),
                        ]))),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDmcaComplaints::route('/'),
            'view' => Pages\ViewDmcaComplaint::route('/{record}'),
            'edit' => Pages\EditDmcaComplaint::route('/{record}/edit'),
        ];
    }
}
