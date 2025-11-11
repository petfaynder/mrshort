<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdSettingResource\Pages;
use App\Filament\Resources\AdSettingResource\RelationManagers;
use App\Models\AdSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class AdSettingResource extends Resource
{
    protected static ?string $model = AdSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth'; // Ayarlar ikonu

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('key')
                    ->disabled() // Key alanı değiştirilememeli
                    ->required()
                    ->maxLength(255),
                TextInput::make('value')
                    ->label('Değer')
                    ->nullable(),
                TextInput::make('admin_campaign_weight')
                    ->label('Admin Kampanya Ağırlığı (%)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                TextInput::make('user_campaign_weight')
                    ->label('Kullanıcı Kampanya Ağırlığı (%)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                TextInput::make('admin_popup_weight')
                    ->label('Admin Pop-up Ağırlığı (%)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                TextInput::make('user_popup_weight')
                    ->label('Kullanıcı Pop-up Ağırlığı (%)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('admin_campaign_weight')
                    ->label('Admin Kampanya Ağırlığı (%)')
                    ->sortable(),
                TextColumn::make('user_campaign_weight')
                    ->label('Kullanıcı Kampanya Ağırlığı (%)')
                    ->sortable(),
                TextColumn::make('admin_popup_weight')
                    ->label('Admin Pop-up Ağırlığı (%)')
                    ->sortable(),
                TextColumn::make('user_popup_weight')
                    ->label('Kullanıcı Pop-up Ağırlığı (%)')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdSettings::route('/'),
            'create' => Pages\CreateAdSetting::route('/create'),
            'edit' => Pages\EditAdSetting::route('/{record}/edit'),
        ];
    }

    // Tek bir ayar kaydı olacağı için create ve delete işlemlerini devre dışı bırakabiliriz
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}
