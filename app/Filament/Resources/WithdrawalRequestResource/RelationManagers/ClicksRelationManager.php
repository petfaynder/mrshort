<?php

namespace App\Filament\Resources\WithdrawalRequestResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'clicks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('RelationManager')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('RelationManager')
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')->label('IP Adresi'),
                Tables\Columns\TextColumn::make('country')->label('Ülke')->placeholder('Bilinmiyor'),
                Tables\Columns\TextColumn::make('device_type')->label('Cihaz Türü')->placeholder('Bilinmiyor'),
                Tables\Columns\TextColumn::make('os')->label('İşletim Sistemi')->placeholder('Bilinmiyor'),
                Tables\Columns\TextColumn::make('browser')->label('Tarayıcı')->placeholder('Bilinmiyor'),
                Tables\Columns\TextColumn::make('referrer')->label('Yönlendiren')->placeholder('Doğrudan / Bilinmiyor'),
                Tables\Columns\IconColumn::make('is_bot')->label('Bot')->boolean(),
                Tables\Columns\TextColumn::make('recent_click_count')->label('Son Tıklama Sayısı'),
                Tables\Columns\TextColumn::make('created_at')->label('Tıklama Zamanı')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
