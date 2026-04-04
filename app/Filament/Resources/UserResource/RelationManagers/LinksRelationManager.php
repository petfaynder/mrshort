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
                Forms\Components\TextInput::make('original_url')
                    ->label('Destination URL')
                    ->required()
                    ->url()
                    ->maxLength(2000),
                Forms\Components\TextInput::make('code')
                    ->label('Short Code')
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->label('Short Link')
                    ->formatStateUsing(fn (Link $record) => $record->shortLink())
                    ->copyable()
                    ->copyableState(fn (Link $record) => $record->shortLink()),
                Tables\Columns\TextColumn::make('original_url')
                    ->label('Destination')
                    ->limit(40)
                    ->searchable()
                    ->tooltip(fn (Link $record) => $record->original_url),
                Tables\Columns\TextColumn::make('clicks')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_hidden')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye-slash')
                    ->falseIcon('heroicon-o-eye')
                    ->trueColor('warning')
                    ->falseColor('success')
                    ->label('Hidden'),
                Tables\Columns\IconColumn::make('is_blocked')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->label('Blocked'),
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

