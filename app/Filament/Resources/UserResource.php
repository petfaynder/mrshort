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
                    ->label('Hesabı Sil')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(function (User $record): void {
                        $record->delete();
                    })
                    ->requiresConfirmation(), // Silmeden önce onay iste
                Tables\Actions\Action::make('deactivateAccount')
                    ->label('Hesabı Deaktif Et')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->action(function (User $record): void {
                        $record->update(['status' => 'deactivated']);
                        \Filament\Notifications\Notification::make()
                            ->title('Hesap deaktif edildi')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Hesabı Deaktif Et')
                    ->modalDescription('Bu kullanıcının hesabını deaktif etmek istediğinizden emin misiniz?')
                    ->visible(fn (User $record): bool => $record->status !== 'deactivated'),
                Tables\Actions\Action::make('activateAccount')
                    ->label('Hesabı Aktif Et')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (User $record): void {
                        $record->update(['status' => 'active']);
                        \Filament\Notifications\Notification::make()
                            ->title('Hesap aktif edildi')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->visible(fn (User $record): bool => $record->status === 'deactivated'),
                Tables\Actions\Action::make('sendMessage')
                    ->label('Mesaj Gönder')
                    ->icon('heroicon-o-envelope')
                    ->form([
                        Forms\Components\TextInput::make('subject')
                            ->label('Konu')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('message')
                            ->label('Mesaj')
                            ->required()
                            ->rows(5),
                    ])
                    ->action(function (User $record, array $data): void {
                        \Illuminate\Support\Facades\Mail::to($record->email)->send(
                            new \Illuminate\Mail\Mailable(function ($message) use ($record, $data) {
                                $message->subject($data['subject'])
                                    ->html("<p>Merhaba {$record->name},</p><p>{$data['message']}</p>");
                            })
                        );
                        \Filament\Notifications\Notification::make()
                            ->title('Mesaj gönderildi')
                            ->body("{$record->email} adresine mesaj gönderildi.")
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Kullanıcıya Mesaj Gönder'),
                Tables\Actions\Action::make('viewReports')
                    ->label('Raporları Görüntüle')
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
            //
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
