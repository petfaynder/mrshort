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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->icon('heroicon-o-x-circle') // İkonu değiştirdim
                    ->color('warning')
                    ->action(function (User $record): void {
                        // Kullanıcının hesabını deaktif etme mantığı buraya gelecek
                        // Örneğin: $record->update(['status' => 'deactivated']);
                    })
                    ->requiresConfirmation(), // Deaktif etmeden önce onay iste
                Tables\Actions\Action::make('sendMessage')
                    ->label('Mesaj Gönder')
                    ->icon('heroicon-o-envelope')
                    ->action(function (User $record): void {
                        // Mesaj gönderme modalını açacak veya ilgili işlemi yapacak kod buraya gelecek
                        // Şimdilik sadece bir placeholder
                        \Illuminate\Support\Facades\Notification::route('mail', $record->email)
                            ->notify(new \App\Notifications\AdminMessage($record, 'Test Mesajı')); // Örnek bildirim gönderme
                    })
                    ->requiresConfirmation(), // Mesaj göndermeden önce onay iste
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
