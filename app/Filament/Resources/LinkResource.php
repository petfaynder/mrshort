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

    protected static ?string $navigationIcon = 'heroicon-o-link';
    
    protected static ?string $navigationGroup = 'Link Yönetimi';
    
    protected static ?string $navigationLabel = 'Linkler';
    
    protected static ?string $modelLabel = 'Link';
    
    protected static ?string $pluralModelLabel = 'Linkler';
    
    protected static ?int $navigationSort = 1;

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
                FormTextInput::make('original_url')
                    ->label('Long URL')
                    ->required()
                    ->url(),
                FormTextInput::make('title')
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
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->paginated([10, 25, 50, 100])
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->default(fn (\App\Models\Link $record) => $record->original_url)
                    ->limit(50),
                Tables\Columns\TextColumn::make('code')
                    ->label('Short Link')
                    ->formatStateUsing(fn (string $state, \App\Models\Link $record): string => $record->shortLink())
                    ->url(fn (\App\Models\Link $record): ?string => $record->code ? route('stats', ['code' => $record->code]) : null)
                    ->copyable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('code')
                    ->label('Alias')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Username')
                    ->searchable()
                    ->url(fn (\App\Models\Link $record): ?string =>
                        $record->user_id
                            ? \App\Filament\Resources\UserResource::getUrl('edit', ['record' => $record->user_id])
                            : null
                    )
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
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
                                      ->orWhere('code', 'like', "%{$data['search']}%")
                                      ->orWhere('original_url', 'like', "%{$data['search']}%");
                            });
                        }
                        return $query; // Query'yi döndürmeyi unutmayın
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('visit')
                    ->label('Visit')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Link $record) => $record->original_url)
                    ->openUrlInNewTab(),
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
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('hide')
                        ->label('Hide Selected')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each->update(['is_hidden' => true]);
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('unhide')
                        ->label('Unhide Selected')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each->update(['is_hidden' => false]);
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('deleteWithStats')
                        ->label('Delete Selected with Stats')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function ($record) {
                                // İstatistikleri silme (LinkClick modelini kullanarak)
                                $record->clicks()->delete();
                                // Linki silme
                                $record->delete();
                            });
                        })
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('heroicon-o-trash'),
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


