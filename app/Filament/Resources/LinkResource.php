<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkResource\Pages;
use App\Filament\Resources\LinkResource\RelationManagers;
use App\Models\Link;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TextInput as TableTextInput; // İsim çakışmasını çözmek için takma ad kullanıldı
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput as FormTextInput; // İsim çakışmasını çözmek için takma ad kullanıldı

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('is_hidden')
                    ->label('Status')
                    ->options([
                        false => 'Active',
                        true => 'Hidden',
                    ])
                    ->required(),
                Forms\Components\FormTextInput::make('original_url')
                    ->label('Long URL')
                    ->required()
                    ->url(),
                Forms\Components\FormTextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                // Description alanı modelde mevcut değil, daha sonra eklenebilir.
                // Forms\Components\Textarea::make('description')
                //     ->label('Description')
                //     ->maxLength(65535),
                Forms\Components\DatePicker::make('expires_at')
                    ->label('Expiration date'),
                // Advertising Type alanı modelde mevcut değil, daha sonra eklenebilir.
                // Forms\Components\Select::make('advertising_type')
                //     ->label('Advertising Type')
                //     ->options([
                //         'type1' => 'Type 1', // Örnek seçenekler, gerçek değerlerle değiştirilmeli
                //         'type2' => 'Type 2',
                //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        \Log::info('LinkResource table method called.');
        if (class_exists(\Filament\Tables\Filters\TableTextInput::class)) {
            \Log::info('Filament\Tables\Filters\TextInput class exists.');
        } else {
            \Log::error('Filament\Tables\Filters\TextInput class does NOT exist.');
        }
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('short_url')
                    ->label('Short Link')
                    ->url(fn (\App\Models\Link $record): ?string => $record->short_url ? route('stats', ['code' => $record->short_url]) : null) // Stats linki
                    ->suffix(fn (\App\Models\Link $record): string => 'Created on: ' . $record->created_at->format('Y-m-d')), // Oluşturulma bilgisi
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // ID Filtresi
                Filter::make('id')
                    ->form([
                        FormTextInput::make('id')
                            ->label('Link ID')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $data['id']
                            ? $query->where('id', $data['id'])
                            : $query;
                    }),

                // Kullanıcı Filtresi (Mevcut SelectFilter kalacak)
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User'),

                // Alias Filtresi
                Filter::make('code')
                    ->form([
                        FormTextInput::make('code')
                            ->label('Alias')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $data['code']
                            ? $query->where('code', 'like', "%{$data['code']}%")
                            : $query;
                    }),

                // Arama Filtresi
                Filter::make('search')
                    ->form([
                        FormTextInput::make('search')
                            ->label('Title, Desc. or URL')
                            ->placeholder('Search...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (! empty($data['search'])) {
                            $query->where(function (Builder $query) use ($data) {
                                $query->where('title', 'like', "%{$data['search']}%")
                                      // ->orWhere('description', 'like', "%{$data['search']}%") // Description alanı modelde mevcut değil
                                      ->orWhere('original_url', 'like', "%{$data['search']}%");
                            });
                        }
                        return $query; // Query'yi döndürmeyi unutmayın
                    }),

                // Advertising Type filtresi (yorum satırı olarak kalacak)
                // Tables\Filters\SelectFilter::make('advertising_type')
                //     ->options([
                //         'type1' => 'Type 1', // Örnek seçenekler, gerçek değerlerle değiştirilmeli
                //         'type2' => 'Type 2',
                //     ])
                //     ->label('Advertising Type'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('hide')
                    ->label('Hide')
                    ->action(function (\App\Models\Link $record) {
                        $record->update(['is_hidden' => true]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (\App\Models\Link $record): bool => !$record->is_hidden), // Zaten gizli değilse göster
                Tables\Actions\Action::make('unhide')
                    ->label('Unhide')
                    ->action(function (\App\Models\Link $record) {
                        $record->update(['is_hidden' => false]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (\App\Models\Link $record): bool => $record->is_hidden), // Sadece gizliyse göster
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('deleteWithStats')
                    ->label('Delete with stats')
                    ->action(function (\App\Models\Link $record) {
                        // İstatistikleri silme (LinkClick modelini kullanarak)
                        $record->clicks()->delete();
                        // Linki silme
                        $record->delete();
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('hide')
                        ->label('Hide Selected')
                        ->action(function (Illuminate\Support\Collection $records) {
                            $records->each->update(['is_hidden' => true]);
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('unhide')
                        ->label('Unhide Selected')
                        ->action(function (Illuminate\Support\Collection $records) {
                            $records->each->update(['is_hidden' => false]);
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('deleteWithStats')
                        ->label('Delete Selected with Stats')
                        ->action(function (Illuminate\Support\Collection $records) {
                            $records->each(function ($record) {
                                // İstatistikleri silme (LinkClick modelini kullanarak)
                                $record->clicks()->delete();
                                // Linki silme
                                $record->delete();
                            });
                        })
                        ->requiresConfirmation(),
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
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
            'hidden' => Pages\ListHiddenLinks::route('/hidden'),
            'inactive' => Pages\ListInactiveLinks::route('/inactive'),
        ];
    }
}
