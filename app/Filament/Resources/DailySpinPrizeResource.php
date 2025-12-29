<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailySpinPrizeResource\Pages;
use App\Models\DailySpinPrize;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DailySpinPrizeResource extends Resource
{
    protected static ?string $model = DailySpinPrize::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Features';

    protected static ?string $navigationLabel = 'Daily Spin';

    protected static ?string $modelLabel = 'Spin Ödülü';

    protected static ?string $pluralModelLabel = 'Spin Ödülleri';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ödül Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ödül Adı')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('Ödül Türü')
                            ->options([
                                'points' => 'Puan',
                                'reward_id' => 'Envanter Ödülü',
                                'streak_freeze' => 'Streak Freeze',
                                'xp_multiplier' => 'XP Çarpanı',
                            ])
                            ->default('points')
                            ->required(),
                        Forms\Components\TextInput::make('value')
                            ->label('Değer')
                            ->numeric()
                            ->default(0)
                            ->helperText('Puan için puan miktarı, XP çarpanı için yüzde değer'),
                    ])->columns(3),

                Forms\Components\Section::make('Çark Ayarları')
                    ->schema([
                        Forms\Components\TextInput::make('probability')
                            ->label('Olasılık (%)')
                            ->numeric()
                            ->default(10)
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->required()
                            ->helperText('Tüm dilimlerin olasılık toplamı 100 olmalı'),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Dilim Rengi')
                            ->default('#6B7280'),
                        Forms\Components\TextInput::make('icon')
                            ->label('İkon (Heroicon)')
                            ->placeholder('heroicon-o-gift')
                            ->helperText('Opsiyonel'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),
                    ])->columns(4),

                Forms\Components\Section::make('Durum')
                    ->schema([
                        Forms\Components\Toggle::make('is_jackpot')
                            ->label('Jackpot mu?')
                            ->helperText('Jackpot ödülleri özel animasyonla gösterilir'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label('Renk'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Ödül Adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tür')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'points' => 'Puan',
                        'reward_id' => 'Envanter',
                        'streak_freeze' => 'Streak Freeze',
                        'xp_multiplier' => 'XP Çarpanı',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('value')
                    ->label('Değer')
                    ->numeric(),
                Tables\Columns\TextColumn::make('probability')
                    ->label('Olasılık')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_jackpot')
                    ->label('Jackpot')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
            'index' => Pages\ListDailySpinPrizes::route('/'),
            'create' => Pages\CreateDailySpinPrize::route('/create'),
            'edit' => Pages\EditDailySpinPrize::route('/{record}/edit'),
        ];
    }
}


