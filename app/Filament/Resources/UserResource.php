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
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Kullanıcı Yönetimi';
    
    protected static ?string $navigationLabel = 'Kullanıcılar';
    
    protected static ?string $modelLabel = 'Kullanıcı';
    
    protected static ?string $pluralModelLabel = 'Kullanıcılar';
    
    protected static ?int $navigationSort = 1;
    
    // Performance fix: Use cache to avoid N+1 count per page load for the badge
    public static function getNavigationBadge(): ?string
    {
        return cache()->remember('admin_users_today_count', 60, function () {
            return static::getModel()::whereDate('created_at', today())->count() ?: null;
        });
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('User Details')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Core Info')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Username')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('email')
                                        ->label('Email')
                                        ->email()
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('first_name')
                                        ->label('First Name')
                                        ->maxLength(255)
                                        ->nullable(),
                                    TextInput::make('last_name')
                                        ->label('Last Name')
                                        ->maxLength(255)
                                        ->nullable(),
                                    TextInput::make('password')
                                        ->label('Password (Secure)')
                                        ->password()
                                        ->maxLength(255)
                                        ->autocomplete('new-password')
                                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                        ->dehydrated(fn ($state) => filled($state))
                                        ->required(fn (string $context): bool => $context === 'create')
                                        ->helperText('Şifreyi değiştirmek istemiyorsanız boş bırakın. Güvenlik için şifre sıfırlanmaz.'),
                                    Select::make('status')
                                        ->label('Account Status')
                                        ->options([
                                            'active' => 'Active',
                                            'deactivated' => 'Deactivated',
                                            'banned' => 'Banned',
                                            'pending' => 'Pending',
                                        ])
                                        ->default('active')
                                        ->required()
                                        ->helperText('Not: Hesabı güvenli kapatmak için Tablo sayfasındaki "Deactivate" / "Ban" butonlarını kullanmanız önerilir.'),
                                ]),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Finance & Subscriptions')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Select::make('plan')
                                        ->label('Subscription Plan')
                                        ->options([
                                            'free' => 'Free Plan',
                                            'pro' => 'Pro Plan',
                                            'vip' => 'VIP Plan',
                                        ])
                                        ->default('free')
                                        ->required(),
                                    Select::make('vip_level_id')
                                        ->label('VIP Level')
                                        ->relationship('vipLevel', 'name')
                                        ->nullable(),
                                    Forms\Components\DateTimePicker::make('expiration')
                                        ->label('Plan Expiration Date')
                                        ->nullable(),
                                    TextInput::make('earnings')
                                        ->label('Total Earnings')
                                        ->numeric()->step(0.0001)
                                        ->default(0)
                                        ->nullable(),
                                    TextInput::make('link_earnings')
                                        ->label('Link Earnings')
                                        ->numeric()->step(0.0001)
                                        ->default(0)
                                        ->nullable(),
                                    TextInput::make('referral_earnings')
                                        ->label('Referral Earnings')
                                        ->numeric()->step(0.0001)
                                        ->default(0)
                                        ->nullable(),
                                ]),
                                Forms\Components\Grid::make(2)->schema([
                                    Select::make('payment_method')
                                        ->label('Payment Method')
                                        ->options([
                                            'paypal' => 'PayPal',
                                            'crypto' => 'Crypto/USDT',
                                            'bank_transfer' => 'Bank Transfer',
                                            'payeer' => 'Payeer',
                                        ])
                                        ->nullable(),
                                    Forms\Components\Textarea::make('payment_account')
                                        ->label('Payment Account (Cüzdan/IBAN)')
                                        ->nullable()
                                        ->rows(1),
                                ]),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Gamification')
                            ->icon('heroicon-o-star')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    TextInput::make('gamification_points')
                                        ->label('XP / Game Points')
                                        ->numeric()
                                        ->default(0),
                                    TextInput::make('virtual_currency')
                                        ->label('Virtual Currency')
                                        ->numeric()
                                        ->default(0),
                                    TextInput::make('monthly_goal')
                                        ->label('Monthly Reach Goal')
                                        ->numeric()
                                        ->default(0),
                                    TextInput::make('current_streak')
                                        ->label('Current Streak (Gün)')
                                        ->numeric()
                                        ->default(0),
                                    TextInput::make('longest_streak')
                                        ->label('Longest Streak')
                                        ->numeric()
                                        ->default(0),
                                    TextInput::make('streak_freeze_available')
                                        ->label('Streak Freezes (Dondurma)')
                                        ->numeric()
                                        ->default(0),
                                ]),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Settings & Meta')
                            ->icon('heroicon-o-cog-8-tooth')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Toggle::make('is_admin')
                                        ->label('Sistem Yöneticisi (Admin) Mi?')
                                        ->inline(false)
                                        ->default(false),
                                    TextInput::make('referral_code')
                                        ->label('Referral Code (Davet Kodu)')
                                        ->maxLength(255)
                                        ->nullable(),
                                ]),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Toggle::make('telegram_bonus_enabled')
                                        ->label('Telegram Traffic Bonus Enabled?')
                                        ->inline(false)
                                        ->default(false),
                                    TextInput::make('deactivation_reason')
                                        ->label('Deactivation Reason')
                                        ->maxLength(255)
                                        ->nullable()
                                        ->disabled(),
                                ]),
                                Forms\Components\Section::make('Read-Only Meta Data')
                                    ->description('Sistem logları ve tarihler. Bu veriler otomatik güncellenir.')
                                    ->schema([
                                        Forms\Components\Grid::make(2)->schema([
                                            Forms\Components\Placeholder::make('created_at')
                                                ->label('Kayıt Tarihi (Registered At)')
                                                ->content(fn (?User $record): string => $record?->created_at ? $record->created_at->format('d M Y, H:i') : '-'),
                                            Forms\Components\Placeholder::make('updated_at')
                                                ->label('Son Güncelleme (Updated At)')
                                                ->content(fn (?User $record): string => $record?->updated_at ? $record->updated_at->format('d M Y, H:i') : '-'),
                                            Forms\Components\Placeholder::make('last_login_at')
                                                ->label('Son Giriş (Last Login)')
                                                ->content(fn (?User $record): string => $record?->last_login_at ? $record->last_login_at->format('d M Y, H:i') : 'Hiç giriş yapmadı'),
                                            Forms\Components\Placeholder::make('deactivated_at')
                                                ->label('Kapatılma Tarihi (Deactivated At)')
                                                ->content(fn (?User $record): string => $record?->deactivated_at ? $record->deactivated_at->format('d M Y, H:i') : '-'),
                                        ]),
                                    ])
                                    ->collapsible()
                                    ->collapsed(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->paginated([25, 50, 100, 250])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'warning' => fn ($state) => in_array($state, ['deactivated', 'pending']),
                        'danger' => 'banned',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan')
                    ->label('Plan')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'free' => 'gray',
                        'pro' => 'primary',
                        'vip' => 'warning',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('earnings')
                    ->label('Earnings')
                    ->money('USD') // veya sistemin para birimi
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('expiration')
                    ->label('Expiration')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('register_ip')
                    ->label('Register IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modified')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'deactivated' => 'Deactivated',
                        'banned' => 'Banned',
                        'pending' => 'Pending',
                    ]),
                SelectFilter::make('plan')
                    ->options([
                        'free' => 'Free Plan',
                        'pro' => 'Pro Plan',
                        'vip' => 'VIP Plan',
                    ]),
                TernaryFilter::make('is_admin')
                    ->label('Admin Status'),
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
                    ->label('Deactivate')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->form([
                        Select::make('reason')
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
                    ->label('Reactivate')
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
                    ->visible(fn (User $record): bool => in_array($record->status, ['deactivated', 'banned', 'pending'])),
                Tables\Actions\Action::make('sendMessage')
                    ->label('Send Msg')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->form([
                        TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->required()
                            ->rows(5),
                    ])
                    ->action(function (User $record, array $data): void {
                        $ticket = \App\Models\Ticket::create([
                            'user_id' => $record->id,
                            'subject' => '[Admin Message] ' . $data['subject'],
                            'message' => $data['message'],
                            'status' => 'open',
                            'category' => 'general',
                            'priority' => 'high',
                        ]);
                        
                        $ticket->replies()->create([
                            'user_id' => auth()->id(),
                            'message' => $data['message'],
                        ]);
                        
                        $record->update([
                            'has_admin_message' => true,
                            'admin_message_ticket_id' => $ticket->id,
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Message Sent')
                            ->body("Ticket created for {$record->name}.")
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Send Message to User')
                    ->modalDescription('Creates a support ticket and notifies the user.'),
                Tables\Actions\Action::make('viewReports')
                    ->label('Reports')
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
