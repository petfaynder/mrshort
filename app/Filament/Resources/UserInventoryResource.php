<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserInventoryResource\Pages;
use App\Filament\Resources\UserInventoryResource\RelationManagers;
use App\Models\UserInventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserInventoryResource extends Resource
{
    protected static ?string $model = UserInventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Kullanıcı'),
                Forms\Components\Select::make('reward_id')
                    ->relationship('reward', 'name')
                    ->required()
                    ->label('Ödül'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif Mi?')
                    ->required(),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Son Kullanma Tarihi')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reward.name')
                    ->label('Ödül')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif Mi?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Son Kullanma Tarihi')
                    ->dateTime()
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
            'index' => Pages\ListUserInventories::route('/'),
            'create' => Pages\CreateUserInventory::route('/create'),
            'edit' => Pages\EditUserInventory::route('/{record}/edit'),
        ];
    }
}
