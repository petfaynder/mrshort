<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRewardResource\Pages;
use App\Filament\Resources\UserRewardResource\RelationManagers;
use App\Models\UserReward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserRewardResource extends Resource
{
    protected static ?string $model = UserReward::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Kullanıcı'),
                Forms\Components\Select::make('reward_id')
                    ->relationship('reward', 'name')
                    ->required()
                    ->label('Ödül'),
                Forms\Components\Toggle::make('is_claimed')
                    ->label('Talep Edildi Mi?')
                    ->required(),
                Forms\Components\DateTimePicker::make('claimed_at')
                    ->label('Talep Edilme Tarihi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reward.name')
                    ->label('Ödül')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_claimed')
                    ->label('Talep Edildi Mi?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('claimed_at')
                    ->label('Talep Edilme Tarihi')
                    ->dateTime()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Kullanıcıya Göre Filtrele'),
                Tables\Filters\SelectFilter::make('reward_id')
                    ->relationship('reward', 'name')
                    ->label('Ödüle Göre Filtrele'),
                Tables\Filters\TernaryFilter::make('is_claimed')
                    ->label('Talep Durumu')
                    ->nullable()
                    ->trueLabel('Talep Edildi')
                    ->falseLabel('Talep Edilmedi')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_claimed', true),
                        false: fn (Builder $query) => $query->where('is_claimed', false),
                        blank: fn (Builder $query) => $query,
                    ),
                Tables\Filters\Filter::make('claimed_at')
                    ->form([
                        Forms\Components\DatePicker::make('claimed_from')
                            ->placeholder('Talep Başlangıç Tarihi'),
                        Forms\Components\DatePicker::make('claimed_until')
                            ->placeholder('Talep Bitiş Tarihi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['claimed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('claimed_at', '>=', $date),
                            )
                            ->when(
                                $data['claimed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('claimed_at', '<=', $date),
                            );
                    }),
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
            'index' => Pages\ListUserRewards::route('/'),
            'create' => Pages\CreateUserReward::route('/create'),
            'edit' => Pages\EditUserReward::route('/{record}/edit'),
        ];
    }
}
