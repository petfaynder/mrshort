<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelConfigurationResource\Pages;
use App\Filament\Resources\LevelConfigurationResource\RelationManagers;
use App\Models\LevelConfiguration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LevelConfigurationResource extends Resource
{
    protected static ?string $model = LevelConfiguration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('level')
                    ->required()
                    ->numeric()
                    ->unique(ignoreRecord: true)
                    ->label('Seviye'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Seviye Adı'),
                Forms\Components\TextInput::make('required_experience')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label('Gerekli Deneyim Puanı'),
                Forms\Components\Textarea::make('rewards_description')
                    ->maxLength(65535)
                    ->label('Ödül Açıklaması'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('level')
                    ->label('Seviye')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Seviye Adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('required_experience')
                    ->label('Gerekli Deneyim Puanı')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rewards_description')
                    ->label('Ödül Açıklaması')
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
            'index' => Pages\ListLevelConfigurations::route('/'),
            'create' => Pages\CreateLevelConfiguration::route('/create'),
            'edit' => Pages\EditLevelConfiguration::route('/{record}/edit'),
        ];
    }
}
