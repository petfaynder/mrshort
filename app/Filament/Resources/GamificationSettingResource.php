<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GamificationSettingResource\Pages;
use App\Filament\Resources\GamificationSettingResource\RelationManagers;
use App\Models\GamificationSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GamificationSettingResource extends Resource
{
    protected static ?string $model = GamificationSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('setting_key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label('Ayar Anahtarı'),
                Forms\Components\TextInput::make('setting_value')
                    ->required()
                    ->maxLength(255)
                    ->label('Ayar Değeri')
                    ->hint(fn (string $state, Forms\Get $get) => $get('setting_key') === 'points_to_currency_rate' ? 'Örn: 0.01 (1 puan = 0.01 birim para)' : null)
                    ->numeric(fn (Forms\Get $get) => $get('setting_key') === 'points_to_currency_rate'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->label('Açıklama'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('setting_key')
                    ->label('Ayar Anahtarı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('setting_value')
                    ->label('Ayar Değeri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('setting_key')
                    ->options([
                        'points_to_currency_rate' => 'Puan Çevrim Kuru',
                        'gamification_enabled' => 'Gamification Etkin',
                        // Diğer ayar anahtarları buraya eklenebilir
                    ])
                    ->label('Ayar Anahtarı'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListGamificationSettings::route('/'),
            'create' => Pages\CreateGamificationSetting::route('/create'),
            'edit' => Pages\EditGamificationSetting::route('/{record}/edit'),
        ];
    }
}