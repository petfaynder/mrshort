<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VipLevelResource\Pages;
use App\Models\VipLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VipLevelResource extends Resource
{
    protected static ?string $model = VipLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Gamification';
    protected static ?string $navigationLabel = 'VIP Seviyeleri';
    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Seviye Bilgileri')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Seviye Adı')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Bronze, Silver, Gold...'),
                                Forms\Components\TextInput::make('icon')
                                    ->label('İkon')
                                    ->placeholder('🥉, 🥈, 🥇...'),
                                Forms\Components\ColorPicker::make('color')
                                    ->label('Renk'),
                            ]),
                        Forms\Components\TextInput::make('order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),
                    ]),

                Forms\Components\Section::make('Kazanç Aralığı')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('min_earnings')
                                    ->label('Minimum Kazanç ($)')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->default(0),
                                Forms\Components\TextInput::make('max_earnings')
                                    ->label('Maksimum Kazanç ($)')
                                    ->numeric()
                                    ->prefix('$')
                                    ->helperText('Boş bırakılırsa sınırsız'),
                            ]),
                    ]),

                Forms\Components\Section::make('Bonuslar')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('cpm_bonus_percent')
                                    ->label('CPM Bonus (%)')
                                    ->numeric()
                                    ->suffix('%')
                                    ->default(0),
                                Forms\Components\TextInput::make('spin_extra')
                                    ->label('Ekstra Günlük Spin')
                                    ->numeric()
                                    ->default(0),
                            ]),
                        Forms\Components\TagsInput::make('benefits')
                            ->label('Ek Avantajlar')
                            ->placeholder('Avantaj ekle...')
                            ->helperText('Örn: Hızlı para çekme, Öncelikli destek'),
                    ]),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('icon')
                    ->label('İkon'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Seviye Adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('earning_range')
                    ->label('Kazanç Aralığı'),
                Tables\Columns\TextColumn::make('cpm_bonus_percent')
                    ->label('CPM Bonus')
                    ->formatStateUsing(fn ($state) => $state > 0 ? '+' . $state . '%' : '-'),
                Tables\Columns\TextColumn::make('spin_extra')
                    ->label('Ekstra Spin')
                    ->formatStateUsing(fn ($state) => $state > 0 ? '+' . $state : '-'),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Kullanıcılar')
                    ->counts('users'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make(),
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
            'index' => Pages\ListVipLevels::route('/'),
            'create' => Pages\CreateVipLevel::route('/create'),
            'edit' => Pages\EditVipLevel::route('/{record}/edit'),
        ];
    }
}


