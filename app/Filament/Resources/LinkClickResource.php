<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkClickResource\Pages;
use App\Filament\Resources\LinkClickResource\RelationManagers;
use App\Models\LinkClick;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LinkClickResource extends Resource
{
    protected static ?string $model = LinkClick::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('link.code')
                    ->label('Short Link Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpm_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('device_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('os')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('browser')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('referrer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_bot')
                    ->boolean()
                    ->sortable(),
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
            'index' => Pages\ListLinkClicks::route('/'),
            'create' => Pages\CreateLinkClick::route('/create'),
            'edit' => Pages\EditLinkClick::route('/{record}/edit'),
        ];
    }
}
