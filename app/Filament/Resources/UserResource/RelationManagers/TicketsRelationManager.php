<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Ticket;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    protected static ?string $title = 'Support Tickets';

    protected static ?string $icon = 'heroicon-o-ticket';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'answered' => 'Answered',
                        'closed' => 'Closed',
                    ])
                    ->required(),
                Forms\Components\Select::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subject')
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'open',
                        'warning' => 'answered',
                        'success' => 'closed',
                    ]),
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'secondary' => 'low',
                        'warning' => 'medium',
                        'danger' => 'high',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Update')
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
                Tables\Actions\Action::make('view_ticket')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Ticket $record): string => route('filament.admin.resources.tickets.edit', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
