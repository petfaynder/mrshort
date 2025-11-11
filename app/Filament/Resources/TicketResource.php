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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->columnSpanFull()
                    ->disabled(), // Mesaj değiştirilemez
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
                    ->schema([
                        Forms\Components\Placeholder::make('initial_message')
                            ->label('Initial Message')
                            ->content(function (Ticket $record) {
                                return new \Illuminate\Support\HtmlString(view('filament.components.ticket-message', [
                                    'message' => $record->message,
                                    'user' => $record->user,
                                    'timestamp' => $record->created_at,
                                    'is_admin' => false, // İlk mesaj kullanıcıdan gelir
                                ])->render());
                            })
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('replies')
                            ->relationship('replies')
                            ->schema([
                                Forms\Components\Placeholder::make('reply_message')
                                    ->label(fn ($state, $record) => $record->user->is_admin ? 'Admin' : $record->user->name)
                                    ->content(function ($state, $record) {
                                         return new \Illuminate\Support\HtmlString(view('filament.components.ticket-message', [
                                            'message' => $record->message,
                                            'user' => $record->user,
                                            'timestamp' => $record->created_at,
                                            'is_admin' => $record->user->is_admin,
                                        ])->render());
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->label('Replies')
                            ->hiddenLabel()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['user']['name'] ?? null)
                            ->defaultItems(0)
                            ->disableItemCreation()
                            ->disableItemDeletion()
                            ->disableItemMovement()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('admin_reply')
                            ->label('Reply')
                            ->placeholder('Write your reply here...')
                            ->hidden(fn (Ticket $record) => $record->status === 'closed' || $record->status === 'resolved') // Kapalı veya çözülmüşse gizle
                            ->columnSpanFull(),

                        Forms\Components\Actions::make([
                            \Filament\Forms\Components\Actions\Action::make('send_reply')
                                ->label('Send Reply')
                                ->action(function (Ticket $record, array $data, \Filament\Resources\Pages\EditRecord $livewire) {
                                    // Form verilerine $livewire->data ile eriş
                                    $adminReply = $livewire->data['admin_reply'] ?? null;

                                    \Illuminate\Support\Facades\Log::info('Send Reply Action Data:', $data); // Loglama eklendi
                                    \Illuminate\Support\Facades\Log::info('Admin Reply Value:', [$adminReply]); // admin_reply değerini logla

                                    if ($adminReply) {
                                        $record->replies()->create([
                                            'user_id' => auth()->id(),
                                            'message' => $adminReply,
                                        ]);

                                        // Durumu "in_progress" olarak güncelle (isteğe bağlı)
                                        if ($record->status === 'open') {
                                            $record->status = 'in_progress';
                                            $record->save();
                                        }

                                        // Form alanını temizle
                                        $livewire->data['admin_reply'] = null;

                                        // Ticket kaydını yeniden yükle
                                        $livewire->record = $record->load('replies.user');

                                        \Filament\Notifications\Notification::make()
                                            ->title('Reply sent')
                                            ->success()
                                            ->send();
                                    } else {
                                         \Filament\Notifications\Notification::make()
                                            ->title('Reply cannot be empty')
                                            ->danger()
                                            ->send();
                                    }
                                })
                                ->hidden(fn (Ticket $record) => $record->status === 'closed' || $record->status === 'resolved'), // Kapalı veya çözülmüşse gizle
                        ]),
                    ])
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->searchable()
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
