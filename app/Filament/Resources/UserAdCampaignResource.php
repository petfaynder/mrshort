<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserAdCampaignResource\Pages;
use App\Models\AdCampaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class UserAdCampaignResource extends Resource
{
    protected static ?string $model = AdCampaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationGroup = 'Reklam Yönetimi';
    
    protected static ?string $navigationLabel = 'User Ad Campaigns';
    
    protected static ?string $modelLabel = 'User Campaign';
    
    protected static ?string $pluralModelLabel = 'User Ad Campaigns';
    
    protected static ?int $navigationSort = 5;

    public static function getEloquentQuery(): Builder
    {
        // Only show user campaigns (not admin campaigns)
        return parent::getEloquentQuery()->where('campaign_type', 'user');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Campaign Info')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Campaign Name')
                            ->disabled(),
                        Forms\Components\Select::make('user_id')
                            ->label('Created By')
                            ->relationship('user', 'name')
                            ->disabled(),
                        Forms\Components\Select::make('approval_status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->visible(fn ($get) => $get('approval_status') === 'rejected'),
                    ])->columns(2),
                Forms\Components\Section::make('Targeting')
                    ->schema([
                        Forms\Components\KeyValue::make('targeting_rules')
                            ->label('Targeting Rules')
                            ->disabled(),
                    ]),
                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('total_impressions')
                            ->disabled(),
                        Forms\Components\TextInput::make('total_clicks')
                            ->disabled(),
                        Forms\Components\TextInput::make('budget')
                            ->prefix('$')
                            ->disabled(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Campaign Name')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('targeting_rules')
                    ->label('Target URL')
                    ->formatStateUsing(fn ($state) => $state['popup_url'] ?? $state['url'] ?? 'N/A')
                    ->limit(40),
                Tables\Columns\BadgeColumn::make('approval_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('total_clicks')
                    ->label('Clicks')
                    ->numeric(),
                Tables\Columns\TextColumn::make('budget')
                    ->label('Budget')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('approval_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (AdCampaign $record) => $record->approval_status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (AdCampaign $record) {
                        $record->update([
                            'approval_status' => 'approved',
                            'approved_at' => now(),
                            'rejection_reason' => null,
                        ]);
                        Notification::make()
                            ->title('Campaign approved successfully')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (AdCampaign $record) => $record->approval_status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Reason for rejection')
                            ->required(),
                    ])
                    ->action(function (AdCampaign $record, array $data) {
                        $record->update([
                            'approval_status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                            'is_active' => false,
                        ]);
                        Notification::make()
                            ->title('Campaign rejected')
                            ->warning()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserAdCampaigns::route('/'),
            'view' => Pages\ViewUserAdCampaign::route('/{record}'),
            'edit' => Pages\EditUserAdCampaign::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Admin cannot create user campaigns
    }
}
