<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyChallengePoolResource\Pages;
use App\Models\DailyChallengePool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DailyChallengePoolResource extends Resource
{
    protected static ?string $model = DailyChallengePool::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Gamification';

    protected static ?string $navigationLabel = 'Daily Challenges';

    protected static ?string $modelLabel = 'Günlük Görev';

    protected static ?string $pluralModelLabel = 'Günlük Görevler';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Görev Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(2),
                    ]),

                Forms\Components\Section::make('Görev Ayarları')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Görev Türü')
                            ->options([
                                'shorten_links' => 'Link Kısalt',
                                'get_clicks' => 'Tıklama Al',
                                'different_countries' => 'Farklı Ülkelerden Tıklama',
                                'share_links' => 'Link Paylaş',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('target_value')
                            ->label('Hedef Değer')
                            ->numeric()
                            ->required()
                            ->helperText('Kullanıcının ulaşması gereken sayı'),
                        Forms\Components\Select::make('difficulty')
                            ->label('Zorluk')
                            ->options([
                                'easy' => 'Kolay',
                                'medium' => 'Orta',
                                'hard' => 'Zor',
                            ])
                            ->default('medium')
                            ->required(),
                        Forms\Components\TextInput::make('points_reward')
                            ->label('Puan Ödülü')
                            ->numeric()
                            ->default(50)
                            ->required(),
                    ])->columns(2),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
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
                        'shorten_links' => 'Link Kısalt',
                        'get_clicks' => 'Tıklama Al',
                        'different_countries' => 'Farklı Ülke',
                        'share_links' => 'Link Paylaş',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('target_value')
                    ->label('Hedef')
                    ->numeric(),
                Tables\Columns\TextColumn::make('difficulty')
                    ->label('Zorluk')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'easy' => 'success',
                        'medium' => 'warning',
                        'hard' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('points_reward')
                    ->label('Puan')
                    ->numeric(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('difficulty')
                    ->options([
                        'easy' => 'Kolay',
                        'medium' => 'Orta',
                        'hard' => 'Zor',
                    ]),
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
            'index' => Pages\ListDailyChallengePools::route('/'),
            'create' => Pages\CreateDailyChallengePool::route('/create'),
            'edit' => Pages\EditDailyChallengePool::route('/{record}/edit'),
        ];
    }
}
