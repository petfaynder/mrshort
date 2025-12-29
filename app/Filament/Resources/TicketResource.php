<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    
    protected static ?string $navigationGroup = 'Support';
    
    protected static ?string $navigationLabel = 'Tickets';
    
    protected static ?string $modelLabel = 'Ticket';
    
    protected static ?string $pluralModelLabel = 'Tickets';
    
    protected static ?int $navigationSort = 1;
    
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'open')->count();
        return $count > 0 ? (string) $count : null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name') // Kullanıcı adını göster
                            ->label('User')
                            ->required()
                            ->disabled(), // Kullanıcı değiştirilemez
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->label('Subject')
                            ->maxLength(191)
                            ->disabled(), // Konu değiştirilemez
                        Forms\Components\Select::make('category')
                            ->options([
                                'payment' => 'Payment',
                                'technical' => 'Technical',
                                'account' => 'Account',
                                'general' => 'General',
                            ])
                            ->label('Category')
                            ->required(),
                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                            ])
                            ->label('Priority')
                            ->required(),
                    ]),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->label('Message')
                    ->columnSpanFull()
                    ->disabled()
                    ->hidden(), // Mesaj artık conversation view içinde gösteriliyor, burada gizle

                Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'closed' => 'Closed',
                        'resolved' => 'Resolved',
                    ])
                    ->label('Status')
                    ->required(),

                Forms\Components\Section::make('Conversation')
                    ->description('View message history and send replies')
                    ->schema([
                        Forms\Components\View::make('filament.components.ticket-conversation')
                            ->viewData(fn (Ticket $record) => ['record' => $record->load('replies.user')])
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('admin_reply')
                            ->label('Your Reply')
                            ->placeholder('Write your reply here...')
                            ->rows(4)
                            ->hidden(fn (Ticket $record) => $record->status === 'closed' || $record->status === 'resolved')
                            ->columnSpanFull(),

                        Forms\Components\Actions::make([
                            \Filament\Forms\Components\Actions\Action::make('send_reply')
                                ->label('Send Reply')
                                ->icon('heroicon-o-paper-airplane')
                                ->color('success')
                                ->action(function (Ticket $record, array $data, \Filament\Resources\Pages\EditRecord $livewire) {
                                    $adminReply = $livewire->data['admin_reply'] ?? null;

                                    if ($adminReply) {
                                        $record->replies()->create([
                                            'user_id' => auth()->id(),
                                            'message' => $adminReply,
                                        ]);

                                        if ($record->status === 'open') {
                                            $record->status = 'in_progress';
                                            $record->save();
                                        }

                                        $livewire->data['admin_reply'] = null;
                                        $livewire->record = $record->load('replies.user');

                                        \Filament\Notifications\Notification::make()
                                            ->title('Reply sent successfully!')
                                            ->success()
                                            ->send();
                                    } else {
                                         \Filament\Notifications\Notification::make()
                                            ->title('Reply cannot be empty')
                                            ->danger()
                                            ->send();
                                    }
                                })
                                ->hidden(fn (Ticket $record) => $record->status === 'closed' || $record->status === 'resolved'),
                        ]),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
   }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name') // Kullanıcı adını göster
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'closed' => 'Closed',
                        'resolved' => 'Resolved',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'danger',
                        'in_progress' => 'warning',
                        'closed' => 'gray',
                        'resolved' => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'payment' => 'Payment',
                        'technical' => 'Technical',
                        'account' => 'Account',
                        'general' => 'General',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                        default => 'gray',
                    })
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'closed' => 'Closed',
                        'resolved' => 'Resolved',
                    ])
                    ->label('Status'),
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'payment' => 'Payment',
                        'technical' => 'Technical',
                        'account' => 'Account',
                        'general' => 'General',
                    ])
                    ->label('Category'),
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ])
                    ->label('Priority'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Reply')
                    ->icon('heroicon-o-chat-bubble-left-right'),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}


