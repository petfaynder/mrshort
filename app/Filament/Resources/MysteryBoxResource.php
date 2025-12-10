<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MysteryBoxResource\Pages;
use App\Models\MysteryBox;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MysteryBoxResource extends Resource
{
    protected static ?string $model = MysteryBox::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Gamification';

    protected static ?string $navigationLabel = 'Mystery Boxes';

    protected static ?string $modelLabel = 'Gizem Kutusu';

    protected static ?string $pluralModelLabel = 'Gizem Kutuları';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kutu Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('İsim')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('tier')
                            ->label('Seviye')
                            ->options([
                                'bronze' => 'Bronz',
                                'silver' => 'Gümüş',
                                'gold' => 'Altın',
                                'diamond' => 'Elmas',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(2),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Renk'),
                        Forms\Components\TextInput::make('icon')
                            ->label('İkon')
                            ->default('🎁'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('İçerik')
                    ->schema([
                        Forms\Components\Repeater::make('contents')
                            ->label('Olası Ödüller')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Tür')
                                    ->options([
                                        'points' => 'Puan',
                                        'reward_id' => 'Rozet/Ödül',
                                        'streak_freeze' => 'Streak Freeze',
                                    ])
                                    ->required()
                                    ->reactive(),
                                Forms\Components\TextInput::make('min')
                                    ->label('Min')
                                    ->numeric()
                                    ->visible(fn ($get) => $get('type') === 'points'),
                                Forms\Components\TextInput::make('max')
                                    ->label('Max')
                                    ->numeric()
                                    ->visible(fn ($get) => $get('type') === 'points'),
                                Forms\Components\TextInput::make('value')
                                    ->label('Değer')
                                    ->numeric()
                                    ->visible(fn ($get) => $get('type') !== 'points'),
                                Forms\Components\TextInput::make('probability')
                                    ->label('Olasılık (%)')
                                    ->numeric()
                                    ->required()
                                    ->default(50),
                            ])
                            ->columns(4)
                            ->defaultItems(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('İsim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tier')
                    ->label('Seviye')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bronze' => 'warning',
                        'silver' => 'gray',
                        'gold' => 'warning',
                        'diamond' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('userBoxes_count')
                    ->label('Dağıtılan')
                    ->counts('userBoxes'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tier')
                    ->options([
                        'bronze' => 'Bronz',
                        'silver' => 'Gümüş',
                        'gold' => 'Altın',
                        'diamond' => 'Elmas',
                    ]),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMysteryBoxes::route('/'),
            'create' => Pages\CreateMysteryBox::route('/create'),
            'edit' => Pages\EditMysteryBox::route('/{record}/edit'),
        ];
    }
}
