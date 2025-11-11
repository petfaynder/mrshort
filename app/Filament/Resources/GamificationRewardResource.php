<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GamificationRewardResource\Pages;
use App\Filament\Resources\GamificationRewardResource\RelationManagers;
use App\Models\GamificationReward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GamificationRewardResource extends Resource
{
    protected static ?string $model = GamificationReward::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Ödül Adı'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->label('Açıklama'),
                Forms\Components\Select::make('type')
                    ->options([
                        'badge' => 'Rozet (Başarım)',
                        'points' => 'Puan',
                        'virtual_currency' => 'Sanal Para Birimi (Coin)',
                        'special_content' => 'Özel İçerik Erişimi',
                        'avatar_item' => 'Avatar Öğesi',
                    ])
                    ->required()
                    ->label('Ödül Türü'),
                Forms\Components\TextInput::make('value')
                    ->numeric()
                    ->label('Değer (Puan/Coin miktarı)'),
                Forms\Components\KeyValue::make('reward_config')
                    ->label('Ödül Konfigürasyonu')
                    ->nullable(),
                Forms\Components\FileUpload::make('image_path')
                    ->image()
                    ->directory('rewards')
                    ->label('Görsel'),
                Forms\Components\Select::make('gamification_goal_id')
                    ->relationship('gamificationGoal', 'title')
                    ->label('İlgili Hedef (Opsiyonel)'),
                Forms\Components\Select::make('level_id')
                    ->relationship('levelConfiguration', 'name') // Assuming LevelConfiguration model has a 'name' field
                    ->label('İlgili Seviye (Opsiyonel)'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif Mi?')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ödül Adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tür')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Değer')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Görsel'),
                Tables\Columns\TextColumn::make('gamificationGoal.title')
                    ->label('İlgili Hedef')
                    ->searchable(),
                Tables\Columns\TextColumn::make('levelConfiguration.name')
                    ->label('İlgili Seviye')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif Mi?')
                    ->boolean(),
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
            'index' => Pages\ListGamificationRewards::route('/'),
            'create' => Pages\CreateGamificationReward::route('/create'),
            'edit' => Pages\EditGamificationReward::route('/{record}/edit'),
        ];
    }
}
