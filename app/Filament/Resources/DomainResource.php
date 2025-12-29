<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DomainResource\Pages;
use App\Models\Domain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Domain Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Display Name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('MrShort'),
                            
                        Forms\Components\TextInput::make('domain')
                            ->label('Domain')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('mrshort.com')
                            ->helperText('Enter domain without protocol (http/https)'),
                            
                        Forms\Components\Select::make('protocol')
                            ->label('Protocol')
                            ->options([
                                'https' => 'HTTPS (Recommended)',
                                'http' => 'HTTP',
                            ])
                            ->default('https')
                            ->required(),
                            
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Domain')
                            ->helperText('Only one domain can be active at a time. Setting this as active will deactivate other domains.')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('domain')
                    ->label('Domain')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->description(fn (Domain $record): string => $record->getBaseUrl()),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('setActive')
                    ->label('Set as Active')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Set Domain as Active')
                    ->modalDescription('This will make all short links use this domain. Old domain links will redirect to this domain.')
                    ->modalSubmitActionLabel('Yes, Set as Active')
                    ->hidden(fn (Domain $record): bool => $record->is_active)
                    ->action(function (Domain $record): void {
                        $record->setAsActive();
                        
                        Notification::make()
                            ->title('Domain Activated')
                            ->body("{$record->name} ({$record->domain}) is now the active domain.")
                            ->success()
                            ->send();
                    }),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (Domain $record): bool => $record->is_active),
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
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}


