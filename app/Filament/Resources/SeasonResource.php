<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeasonResource\Pages;
use App\Models\Season;
use App\Models\SeasonReward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeasonResource extends Resource
{
    protected static ?string $model = Season::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Gamification';
    protected static ?string $navigationLabel = 'Sezonlar & Battle Pass';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sezon Bilgileri')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Sezon Adı')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('theme')
                                    ->label('Tema')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(3),
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Sezon Görseli')
                            ->image()
                            ->directory('seasons'),
                    ]),

                Forms\Components\Section::make('Tarih ve Fiyatlandırma')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('start_at')
                                    ->label('Başlangıç Tarihi')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('end_at')
                                    ->label('Bitiş Tarihi')
                                    ->required(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('premium_price_points')
                                    ->label('Premium Fiyatı (Puan)')
                                    ->numeric()
                                    ->default(5000),
                                Forms\Components\TextInput::make('premium_price_money')
                                    ->label('Premium Fiyatı ($)')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(50),
                            ]),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(false),
                    ]),

                Forms\Components\Section::make('Sezon Ödülleri')
                    ->schema([
                        Forms\Components\Repeater::make('rewards')
                            ->relationship()
                            ->schema([
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\TextInput::make('level')
                                            ->label('Seviye')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(30),
                                        Forms\Components\Toggle::make('is_premium')
                                            ->label('Premium')
                                            ->default(false),
                                        Forms\Components\Select::make('reward_type')
                                            ->label('Ödül Türü')
                                            ->options([
                                                'points' => 'Puan',
                                                'mystery_box' => 'Gizem Kutusu',
                                                'badge' => 'Rozet',
                                                'avatar_frame' => 'Avatar Çerçevesi',
                                                'profile_theme' => 'Profil Teması',
                                                'xp_boost' => 'XP Boost',
                                                'streak_freeze' => 'Streak Freeze',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('reward_value')
                                            ->label('Değer')
                                            ->required(),
                                    ]),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('reward_name')
                                            ->label('Ödül Adı')
                                            ->required(),
                                        Forms\Components\TextInput::make('reward_icon')
                                            ->label('İkon'),
                                    ]),
                            ])
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                isset($state['level']) 
                                    ? 'Seviye ' . $state['level'] . ($state['is_premium'] ? ' (Premium)' : ' (Ücretsiz)')
                                    : null
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Görsel')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Sezon Adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('theme')
                    ->label('Tema')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->label('Başlangıç')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->label('Bitiş')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rewards_count')
                    ->label('Ödül Sayısı')
                    ->counts('rewards'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeasons::route('/'),
            'create' => Pages\CreateSeason::route('/create'),
            'edit' => Pages\EditSeason::route('/{record}/edit'),
        ];
    }
}
