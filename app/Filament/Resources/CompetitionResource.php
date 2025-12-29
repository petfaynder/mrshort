<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompetitionResource\Pages;
use App\Models\Competition;
use App\Models\GamificationReward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompetitionResource extends Resource
{
    protected static ?string $model = Competition::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Competitions';

    protected static ?string $navigationLabel = 'Yarışmalar';

    protected static ?string $modelLabel = 'Yarışma';

    protected static ?string $pluralModelLabel = 'Yarışmalar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Yarışma Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(2),
                        Forms\Components\Select::make('type')
                            ->label('Yarışma Türü')
                            ->options([
                                'clicks' => 'En Çok Tıklama',
                                'links' => 'En Çok Link',
                                'referrals' => 'En Çok Referans',
                                'earnings' => 'En Yüksek Kazanç',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Tarih Aralığı')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Başlangıç')
                            ->required(),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('Bitiş')
                            ->required()
                            ->after('start_date'),
                    ])->columns(2),

                Forms\Components\Section::make('Ödül Yapısı')
                    ->schema([
                        Forms\Components\Select::make('badge_reward_id')
                            ->label('1. için Rozet')
                            ->options(GamificationReward::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),
                        Forms\Components\Repeater::make('prize_structure')
                            ->label('Puan Ödülleri')
                            ->schema([
                                Forms\Components\TextInput::make('rank')
                                    ->label('Sıra (Başlangıç)')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('rank_to')
                                    ->label('Sıra (Bitiş)')
                                    ->numeric()
                                    ->helperText('Tek sıra için boş bırakın'),
                                Forms\Components\TextInput::make('points')
                                    ->label('Puan')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(3)
                            ->default([
                                ['rank' => 1, 'rank_to' => null, 'points' => 10000],
                                ['rank' => 2, 'rank_to' => null, 'points' => 5000],
                                ['rank' => 3, 'rank_to' => null, 'points' => 2500],
                                ['rank' => 4, 'rank_to' => 10, 'points' => 1000],
                                ['rank' => 11, 'rank_to' => 50, 'points' => 500],
                                ['rank' => 51, 'rank_to' => 100, 'points' => 250],
                            ]),
                    ]),
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
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'clicks' => 'Tıklama',
                        'links' => 'Link',
                        'referrals' => 'Referans',
                        'earnings' => 'Kazanç',
                        default => $state,
                    })
                    ->badge(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Başlangıç')
                    ->dateTime('d.m.Y H:i'),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Bitiş')
                    ->dateTime('d.m.Y H:i'),
                Tables\Columns\TextColumn::make('entries_count')
                    ->label('Katılımcı')
                    ->counts('entries'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'clicks' => 'Tıklama',
                        'links' => 'Link',
                        'referrals' => 'Referans',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('distribute')
                    ->label('Ödül Dağıt')
                    ->icon('heroicon-o-gift')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Competition $record) {
                        $result = $record->distributeRewards();
                        \Filament\Notifications\Notification::make()
                            ->title($result . ' kullanıcıya ödül dağıtıldı')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Competition $record) => $record->hasEnded()),
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
            'index' => Pages\ListCompetitions::route('/'),
            'create' => Pages\CreateCompetition::route('/create'),
            'edit' => Pages\EditCompetition::route('/{record}/edit'),
        ];
    }
}


