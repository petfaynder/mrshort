<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Link;

class LinksRelationManager extends RelationManager
{
    protected static string $relationship = 'links';

    protected static ?string $title = 'Links';

    protected static ?string $icon = 'heroicon-o-link';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('url')
                    ->label('Destination URL')
                    ->required()
                    ->url()
                    ->maxLength(2000),
                Forms\Components\TextInput::make('alias')
                    ->label('Alias')
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alias')
            ->columns([
                Tables\Columns\TextColumn::make('alias')
                    ->searchable()
                    ->sortable()
                    ->label('Short Link')
                    ->formatStateUsing(fn ($state) => $state),
                Tables\Columns\TextColumn::make('url')
                    ->label('Destination')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('clicks')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('visit')
                    ->label('Visit')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Link $record): string => $record->original_url ?? '#')
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
