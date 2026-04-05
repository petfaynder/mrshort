<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkSuggestionResource\Pages;
use App\Models\LinkSuggestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class LinkSuggestionResource extends Resource
{
    protected static ?string $model = LinkSuggestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Link Suggestions';

    protected static ?string $modelLabel = 'Suggestion';

    protected static ?string $pluralModelLabel = 'Link Suggestions';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Suggestion Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Post at Peak Hours'),
                        Forms\Components\Textarea::make('text')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('e.g. Share your links between 18:00–22:00 for best results.'),
                    ]),
                Forms\Components\Section::make('Appearance')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Material Symbol Icon')
                            ->required()
                            ->maxLength(50)
                            ->default('lightbulb')
                            ->placeholder('e.g. trending_up, public, attach_money')
                            ->helperText('Use Material Symbols icon name (fonts.google.com/icons)'),
                        Forms\Components\Select::make('color')
                            ->label('Color Theme')
                            ->required()
                            ->options([
                                'green'  => '🟢 Green',
                                'blue'   => '🔵 Blue',
                                'amber'  => '🟡 Amber',
                                'purple' => '🟣 Purple',
                                'rose'   => '🔴 Rose',
                                'cyan'   => '🔵 Cyan',
                                'orange' => '🟠 Orange',
                                'teal'   => '🟢 Teal',
                                'indigo' => '🔵 Indigo',
                                'pink'   => '🩷 Pink',
                            ])
                            ->default('blue'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive suggestions will not appear on the dashboard'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower number = higher priority (used for admin sorting only)'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->formatStateUsing(fn (string $state): string => $state)
                    ->width('80px'),
                Tables\Columns\TextColumn::make('color')
                    ->label('Color')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'green'  => 'success',
                        'blue'   => 'info',
                        'amber'  => 'warning',
                        'purple' => 'gray',
                        'rose'   => 'danger',
                        'cyan'   => 'info',
                        'orange' => 'warning',
                        'teal'   => 'success',
                        'indigo' => 'info',
                        'pink'   => 'danger',
                        default  => 'gray',
                    })
                    ->width('90px'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),
                Tables\Columns\TextColumn::make('text')
                    ->label('Description')
                    ->searchable()
                    ->limit(60)
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),
                Tables\Filters\SelectFilter::make('color')
                    ->options([
                        'green'  => 'Green',
                        'blue'   => 'Blue',
                        'amber'  => 'Amber',
                        'purple' => 'Purple',
                        'rose'   => 'Rose',
                        'cyan'   => 'Cyan',
                        'orange' => 'Orange',
                        'teal'   => 'Teal',
                        'indigo' => 'Indigo',
                        'pink'   => 'Pink',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('seedDefaults')
                    ->label('Seed 50 Default Suggestions')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('Seed Default Suggestions')
                    ->modalDescription('This will load 50 default suggestions from the config file. Existing suggestions will NOT be overwritten.')
                    ->action(function () {
                        if (LinkSuggestion::count() > 0) {
                            Notification::make()
                                ->title('Table already has data. Clear all suggestions first if you want to re-seed.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $suggestions = config('link_suggestions', []);
                        foreach ($suggestions as $index => $item) {
                            LinkSuggestion::create([
                                'icon'       => $item['icon'],
                                'color'      => $item['color'],
                                'title'      => $item['title'],
                                'text'       => $item['text'],
                                'is_active'  => true,
                                'sort_order' => $index,
                            ]);
                        }

                        Notification::make()
                            ->title('Seeded ' . count($suggestions) . ' default suggestions.')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLinkSuggestions::route('/'),
            'create' => Pages\CreateLinkSuggestion::route('/create'),
            'edit'   => Pages\EditLinkSuggestion::route('/{record}/edit'),
        ];
    }
}
