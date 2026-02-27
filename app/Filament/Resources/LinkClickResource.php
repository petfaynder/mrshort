<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkClickResource\Pages;
use App\Models\LinkClick;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class LinkClickResource extends Resource
{
    protected static ?string $model = LinkClick::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';
    
    protected static ?string $navigationGroup = 'Link Yönetimi';
    
    protected static ?string $navigationLabel = 'Tıklamalar';
    
    protected static ?string $modelLabel = 'Tıklama';
    
    protected static ?string $pluralModelLabel = 'Tıklamalar';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        // Log verisi olduğu için form şemasına gerek yok
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->paginated([10, 25, 50, 100])
            ->columns([
                Tables\Columns\TextColumn::make('link.user.name')
                    ->label('User Name')
                    ->description(fn (LinkClick $record): string => $record->link->user->email ?? '')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('link.user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('link.code')
                    ->label('Short Link')
                    ->description(fn (LinkClick $record): string => $record->link->title ?? 'Bilinmeyen Link')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpm_rate')
                    ->label('CPM Rate')
                    ->numeric(
                        decimalPlaces: 4,
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('device_type')
                    ->label('Device')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('os')
                    ->label('OS')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('browser')
                    ->label('Browser')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('referrer')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_bot')
                    ->label('Bot?')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->relationship('link.user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by User'),
                SelectFilter::make('link_id')
                    ->relationship('link', 'code')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Link Code'),
                SelectFilter::make('country_id')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Country'),
                TernaryFilter::make('is_bot')
                    ->label('Bot Status')
                    ->placeholder('All Clicks')
                    ->trueLabel('Only Bots')
                    ->falseLabel('Real Users'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('From'),
                        DatePicker::make('created_until')->label('To'),
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
                // Tıklamalar log kaydıdır, düzenlenemez. Sadece görüntülenebilir veya silinebilir.
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
            'index' => Pages\ListLinkClicks::route('/'),
        ];
    }
}
