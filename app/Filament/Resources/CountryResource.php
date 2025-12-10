<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    
    protected static ?string $navigationGroup = 'Sistem Ayarları';
    
    protected static ?string $navigationLabel = 'Ülkeler';
    
    protected static ?string $modelLabel = 'Ülke';
    
    protected static ?string $pluralModelLabel = 'Ülkeler';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Ülke Adı')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('iso_code')
                    ->label('ISO Kodu')
                    ->required()
                    ->maxLength(2)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('cpm_tier_id')
                    ->label('CPM Seviyesi')
                    ->relationship('cpmTier', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('iso_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpmTier.name')
                    ->label('CPM Tier')
                    ->searchable()
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
