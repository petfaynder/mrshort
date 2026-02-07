<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Competitions';
    protected static ?string $navigationLabel = 'Takımlar';
    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Takım Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Takım Adı')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(3),
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->directory('teams'),
                    ]),

                Forms\Components\Section::make('Lider ve Ayarlar')
                    ->schema([
                        Forms\Components\Select::make('leader_id')
                            ->label('Lider')
                            ->relationship('leader', 'name')
                            ->searchable()
                            ->required(),
                        Forms\Components\Toggle::make('is_public')
                            ->label('Herkese Açık')
                            ->default(true),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('İstatistikler')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('member_count')
                                    ->label('Üye Sayısı')
                                    ->numeric()
                                    ->disabled(),
                                Forms\Components\TextInput::make('total_points')
                                    ->label('Toplam Puan')
                                    ->numeric()
                                    ->disabled(),
                                Forms\Components\TextInput::make('weekly_points')
                                    ->label('Haftalık Puan')
                                    ->numeric()
                                    ->disabled(),
                            ]),
                    ])
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Takım Adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('leader.name')
                    ->label('Lider')
                    ->searchable(),
                Tables\Columns\TextColumn::make('member_count')
                    ->label('Üyeler')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_points')
                    ->label('Toplam Puan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('weekly_points')
                    ->label('Haftalık')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_public')
                    ->label('Açık')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('weekly_points', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
                Tables\Filters\TernaryFilter::make('is_public')
                    ->label('Herkese Açık'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('resetWeekly')
                    ->label('Haftalık Sıfırla')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(fn (Team $record) => $record->resetWeeklyPoints()),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('resetAllWeekly')
                        ->label('Tüm Haftalıkları Sıfırla')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->resetWeeklyPoints()),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TeamMembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}


