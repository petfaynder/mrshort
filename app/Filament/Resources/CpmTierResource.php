<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CpmTierResource\Pages;
use App\Filament\Resources\CpmTierResource\RelationManagers;
use App\Models\CpmTier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CpmTierResource extends Resource
{
    protected static ?string $model = CpmTier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('default_publisher_cpm_rate')
                    ->label('Default Publisher CPM Rate')
                    ->required()
                    ->numeric()
                    ->step(0.0001)
                    ->default(0.0000),
                Forms\Components\TextInput::make('default_advertiser_cpm_rate')
                    ->label('Default Advertiser CPM Rate')
                    ->required()
                    ->numeric()
                    ->step(0.0001)
                    ->default(0.0000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('default_publisher_cpm_rate')
                    ->label('Default Publisher CPM Rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('default_advertiser_cpm_rate')
                    ->label('Default Advertiser CPM Rate')
                    ->numeric()
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
            'index' => Pages\ListCpmTiers::route('/'),
            'create' => Pages\CreateCpmTier::route('/create'),
            'edit' => Pages\EditCpmTier::route('/{record}/edit'),
        ];
    }
}
