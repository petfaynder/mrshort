<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserAchievementResource\Pages;
use App\Filament\Resources\UserAchievementResource\RelationManagers;
use App\Models\UserAchievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserAchievementResource extends Resource
{
    protected static ?string $model = UserAchievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('goal_id')
                    ->relationship('goal', 'title')
                    ->required(),
                Forms\Components\TextInput::make('current_value')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DateTimePicker::make('completed_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı Adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('goal.title')
                    ->label('Hedef Başlığı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('current_value')
                    ->label('Mevcut Değer')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('goal.target_value')
                    ->label('Hedef Değer')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('completed_at')
                    ->label('Tamamlandı Mı?')
                    ->boolean()
                    ->getStateUsing(fn (UserAchievement $record): bool => $record->completed_at !== null),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Tamamlanma Tarihi')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncelleme Tarihi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Kullanıcıya Göre Filtrele'),
                Tables\Filters\SelectFilter::make('goal_id')
                    ->relationship('goal', 'title')
                    ->label('Hedefe Göre Filtrele'),
                Tables\Filters\TernaryFilter::make('completed')
                    ->label('Tamamlanma Durumu')
                    ->nullable()
                    ->trueLabel('Tamamlandı')
                    ->falseLabel('Tamamlanmadı')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('completed_at'),
                        false: fn (Builder $query) => $query->whereNull('completed_at'),
                        blank: fn (Builder $query) => $query,
                    ),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder('Başlangıç Tarihi'),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder('Bitiş Tarihi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
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
            'index' => Pages\ListUserAchievements::route('/'),
            'create' => Pages\CreateUserAchievement::route('/create'),
            'edit' => Pages\EditUserAchievement::route('/{record}/edit'),
        ];
    }
}
