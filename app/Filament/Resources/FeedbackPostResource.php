<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackPostResource\Pages;
use App\Models\FeedbackPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FeedbackPostResource extends Resource
{
    protected static ?string $model = FeedbackPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Feedback System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'review' => 'Under Review',
                        'planned' => 'Planned',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'declined' => 'Declined',
                    ])
                    ->required()
                    ->default('review'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('vote_count')
                    ->sortable()
                    ->label('Votes'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'review' => 'gray',
                        'planned' => 'info',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'declined' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'review' => 'Under Review',
                        'planned' => 'Planned',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'declined' => 'Declined',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (FeedbackPost $record) => $record->update(['status' => 'planned']))
                    ->visible(fn (FeedbackPost $record) => $record->status === 'review'),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(fn (FeedbackPost $record) => $record->update(['status' => 'declined']))
                    ->visible(fn (FeedbackPost $record) => $record->status !== 'declined'),
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
            'index' => Pages\ListFeedbackPosts::route('/'),
            'create' => Pages\CreateFeedbackPost::route('/create'),
            'edit' => Pages\EditFeedbackPost::route('/{record}/edit'),
        ];
    }
}
