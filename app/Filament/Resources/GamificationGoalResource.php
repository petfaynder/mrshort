<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GamificationGoalResource\Pages;
use App\Filament\Resources\GamificationGoalResource\RelationManagers;
use App\Models\GamificationGoal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GamificationGoalResource extends Resource
{
    protected static ?string $model = GamificationGoal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                Forms\Components\Select::make('type')
                    ->options([
                        'shorten_links' => 'Link Kısaltma',
                        'clicks' => 'Tıklama Alma',
                        'shares' => 'Link Paylaşma',
                        'referrals' => 'Referans Getirme',
                        'earn_money' => 'Gelir Elde Etme',
                    ])
                    ->label('Hedef Türü')
                    ->required(),
                Forms\Components\KeyValue::make('goal_type_config')
                    ->label('Hedef Türü Konfigürasyonu')
                    ->nullable(),
                Forms\Components\TextInput::make('target_value')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('difficulty_level')
                    ->options([
                        'easy' => 'Kolay',
                        'medium' => 'Orta',
                        'hard' => 'Zor',
                        'expert' => 'Uzman',
                    ])
                    ->label('Zorluk Seviyesi'),
                Forms\Components\Select::make('category')
                    ->options([
                        'daily' => 'Günlük',
                        'weekly' => 'Haftalık',
                        'one_time' => 'Tek Seferlik',
                        'event_based' => 'Etkinlik Bazlı',
                    ])
                    ->label('Kategori')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif Mi?')
                    ->required(),
                Forms\Components\Select::make('reward_id')
                    ->relationship('reward', 'name')
                    ->label('Ödül'),
                Forms\Components\TextInput::make('points')
                    ->numeric()
                    ->label('Kazanılacak Puan'),
                Forms\Components\TextInput::make('coins')
                    ->numeric()
                    ->label('Kazanılacak Coin'),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Kullanıcı (Opsiyonel)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tür')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_value')
                    ->label('Hedef Değer')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('difficulty_level')
                    ->label('Zorluk Seviyesi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reward.name')
                    ->label('Ödül')
                    ->searchable(),
                Tables\Columns\TextColumn::make('points')
                    ->label('Puan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coins')
                    ->label('Coin')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif Mi?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable(),
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
            'index' => Pages\ListGamificationGoals::route('/'),
            'create' => Pages\CreateGamificationGoal::route('/create'),
            'edit' => Pages\EditGamificationGoal::route('/{record}/edit'),
        ];
    }
}
