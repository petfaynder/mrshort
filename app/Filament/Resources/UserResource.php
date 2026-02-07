<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Kullanıcı Yönetimi';
    
    protected static ?string $navigationLabel = 'Kullanıcılar';
    
    protected static ?string $modelLabel = 'Kullanıcı';
    
    protected static ?string $pluralModelLabel = 'Kullanıcılar';
    
    protected static ?int $navigationSort = 1;
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('created_at', today())->count() ?: null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Username')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->maxLength(255)
                    ->nullable() // Şifre alanı boş bırakılabilir
                    ->helperText('Şifreyi değiştirmek istemiyorsanız boş bırakın.'),
                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('plan')
                    ->label('Plan')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\DateTimePicker::make('expiration')
                    ->label('Expiration')
                    ->nullable(),
                Forms\Components\TextInput::make('earnings')
                    ->label('Earnings')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('link_earnings')
                    ->label('Link Earnings')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('referral_earnings')
                    ->label('Referral Earnings')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('payment_method')
                    ->label('Payment Method')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\Textarea::make('payment_account')
                    ->label('Payment Account')
                    ->nullable(),
                Forms\Components\TextInput::make('country')
                    ->label('Country')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\Toggle::make('is_admin')
                    ->label('Admin Mi?')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan')
                    ->label('Plan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiration')
                    ->label('Expiration')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('earnings')
                    ->label('Disable Earnings') // Geçici olarak earnings alanını kullanıyorum
                    ->sortable(),
                Tables\Columns\TextColumn::make('login_ip')
                    ->label('Login IP')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('register_ip')
                    ->label('Register IP')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country')
                    ->label('Country')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('modified')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('loginAsUser')
                    ->label('Login as User')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->url(fn (User $record): string => route('admin.users.login-as', ['user' => $record]))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('deleteAccount')
                    ->label('Delete Account')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(function (User $record, $livewire): void {
                        $record->delete();
                        \Filament\Notifications\Notification::make()
                            ->title('Account Deleted')
                            ->body('User account has been permanently deleted.')
                            ->success()
                            ->send();
                        $livewire->resetTable();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Delete Account')
                    ->modalDescription('Are you sure you want to permanently delete this user account? This action cannot be undone.'),
                Tables\Actions\Action::make('deactivateAccount')
                    ->label('Deactivate Account')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('reason')
                            ->label('Deactivation Reason')
                            ->options([
                                'terms_violation' => 'Terms of Service Violation',
                                'suspicious_activity' => 'Suspicious Activity',
                                'payment_fraud' => 'Payment Fraud',
                                'spam' => 'Spam / Abuse',
                                'user_request' => 'User Request',
                                'inactive' => 'Account Inactive',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('custom_reason')
                            ->label('Additional Details (Optional)')
                            ->placeholder('Provide more details about the deactivation...')
                            ->rows(3),
                    ])
                    ->action(function (User $record, array $data, $livewire): void {
                        $reasonLabels = [
                            'terms_violation' => 'Terms of Service Violation',
                            'suspicious_activity' => 'Suspicious Activity',
                            'payment_fraud' => 'Payment Fraud',
                            'spam' => 'Spam / Abuse',
                            'user_request' => 'User Request',
                            'inactive' => 'Account Inactive',
                            'other' => 'Other',
                        ];
                        
                        $fullReason = $reasonLabels[$data['reason']] ?? $data['reason'];
                        if (!empty($data['custom_reason'])) {
                            $fullReason .= ': ' . $data['custom_reason'];
                        }
                        
                        $record->update([
                            'status' => 'deactivated',
                            'deactivation_reason' => $fullReason,
                            'deactivated_at' => now(),
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Account Deactivated')
                            ->body("User account has been deactivated. Reason: {$fullReason}")
                            ->success()
                            ->send();
                        $livewire->resetTable();
                    })
                    ->modalHeading('Deactivate Account')
                    ->modalDescription('Select a reason for deactivating this account. The user will see this reason when they try to log in.')
                    ->visible(fn (User $record): bool => $record->status !== 'deactivated' && $record->status !== 'banned'),
                Tables\Actions\Action::make('reactivateAccount')
                    ->label('Reactivate Account')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (User $record, $livewire): void {
                        $record->update([
                            'status' => 'active',
                            'deactivation_reason' => null,
                            'deactivated_at' => null,
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Account Reactivated')
                            ->body("User account has been reactivated. Deactivation reason cleared and user can now access their account normally.")
                            ->success()
                            ->send();
                        $livewire->resetTable();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Reactivate Account')
                    ->modalDescription('This will restore the user\'s access to their account. They will be able to use all features again.')
                    ->visible(fn (User $record): bool => $record->status === 'deactivated'),
                Tables\Actions\Action::make('sendMessage')
                    ->label('Send Message')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->form([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->required()
                            ->rows(5),
                    ])
                    ->action(function (User $record, array $data): void {
                        // Create ticket for user
                        $ticket = \App\Models\Ticket::create([
                            'user_id' => $record->id,
                            'subject' => '[Admin Message] ' . $data['subject'],
                            'message' => $data['message'],
                            'status' => 'open',
                            'category' => 'general',
                            'priority' => 'high',
                        ]);
                        
                        // Add admin reply
                        $ticket->replies()->create([
                            'user_id' => auth()->id(),
                            'message' => $data['message'],
                        ]);
                        
                        // Mark user as having admin message
                        $record->update([
                            'has_admin_message' => true,
                            'admin_message_ticket_id' => $ticket->id,
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Message Sent')
                            ->body("Ticket created for {$record->name}. They will see a notification on their dashboard.")
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Send Message to User')
                    ->modalDescription('This message will create a support ticket and the user will see a notification on their dashboard.'),
                Tables\Actions\Action::make('viewReports')
                    ->label('View Reports')
                    ->url(fn (User $record): string => static::getUrl('reports', ['record' => $record]))
                    ->icon('heroicon-o-chart-bar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AdCampaignsRelationManager::class,
            RelationManagers\WithdrawalsRelationManager::class,
            RelationManagers\TicketsRelationManager::class,
            RelationManagers\LinksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'reports' => Pages\ViewUserReports::route('/{record}/reports'),
        ];
    }
}


