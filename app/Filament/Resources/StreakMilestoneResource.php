<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StreakMilestoneResource\Pages;
use App\Models\StreakMilestone;
use App\Models\GamificationReward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StreakMilestoneResource extends Resource
{
    protected static ?string $model = StreakMilestone::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    protected static ?string $navigationGroup = 'Gamification';

    protected static ?string $navigationLabel = 'Streak Milestones';

    protected static ?string $modelLabel = 'Streak Milestone';

    protected static ?string $pluralModelLabel = 'Streak Milestones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Milestone Ayarları')
                    ->schema([
                        Forms\Components\TextInput::make('days_required')
                            ->label('Gün Sayısı')
                            ->numeric()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Bu milestone için gereken ardışık gün sayısı'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Ödüller')
                    ->schema([
                        Forms\Components\TextInput::make('points_reward')
                            ->label('Puan Ödülü')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Select::make('badge_reward_id')
                            ->label('Rozet Ödülü')
                            ->options(GamificationReward::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Bonus')
                    ->schema([
                        Forms\Components\Select::make('bonus_type')
                            ->label('Bonus Türü')
                            ->options([
                                'xp_boost' => 'XP Boost (%)',
                                'streak_freeze' => 'Streak Freeze',
                            ])
                            ->nullable(),
                        Forms\Components\TextInput::make('bonus_value')
                            ->label('Bonus Değeri')
                            ->numeric()
                            ->helperText('XP Boost için yüzde, Streak Freeze için adet'),
                        Forms\Components\TextInput::make('bonus_duration_hours')
                            ->label('Bonus Süresi (Saat)')
                            ->numeric()
                            ->helperText('XP Boost için geçerli'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('days_required')
                    ->label('Gün')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('points_reward')
                    ->label('Puan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('badgeReward.name')
                    ->label('Rozet')
                    ->placeholder('Yok'),
                Tables\Columns\TextColumn::make('bonus_type')
                    ->label('Bonus')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'xp_boost' => 'XP Boost',
                        'streak_freeze' => 'Streak Freeze',
                        default => '-',
                    }),
                Tables\Columns\TextColumn::make('bonus_value')
                    ->label('Bonus Değer'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('days_required')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
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
            'index' => Pages\ListStreakMilestones::route('/'),
            'create' => Pages\CreateStreakMilestone::route('/create'),
            'edit' => Pages\EditStreakMilestone::route('/{record}/edit'),
        ];
    }
}
