<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminLinkClickReportResource\Pages;
use App\Filament\Resources\AdminLinkClickReportResource\RelationManagers;
use App\Models\AdminLinkClickReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminLinkClickReportResource extends Resource
{
    protected static ?string $model = \App\Models\LinkClick::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Reports';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('link.user.name')
                    ->label('User Name')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('link.user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('link.user.email')
                    ->label('User Email')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('link.user', function ($query) use ($search) {
                            $query->where('email', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpm_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('device_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('os')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('browser')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('referrer')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_bot')
                    ->boolean()
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
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('link.user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by User'),
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
            'index' => Pages\ListAdminLinkClickReports::route('/'),
            'create' => Pages\CreateAdminLinkClickReport::route('/create'),
            'edit' => Pages\EditAdminLinkClickReport::route('/{record}/edit'),
        ];
    }
}
