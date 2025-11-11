<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalRequestResource\Pages;
use App\Filament\Resources\WithdrawalRequestResource\RelationManagers;
use App\Models\WithdrawalRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WithdrawalRequestResource extends Resource
{
    protected static ?string $model = WithdrawalRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge() // Durumları rozet olarak göster
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'returned' => 'secondary',
                        default => 'secondary',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('publisher_earnings')
                    ->label('Publisher Earnings')
                    ->money('USD') // Para birimi formatı (projenizin para birimine göre ayarlayın)
                    ->sortable(),
                Tables\Columns\TextColumn::make('referral_earnings')
                    ->label('Referral Earnings')
                    ->money('USD') // Para birimi formatı
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Total Amount')
                    ->money('USD') // Para birimi formatı
                    ->sortable(),
                Tables\Columns\TextColumn::make('withdrawal_method')
                    ->label('Withdrawal Method')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('withdrawal_account')
                    ->label('Withdrawal Account')
                    ->searchable(),
                // Action sütunu daha sonra aksiyonlar tanımlanırken eklenecek
            ])
            ->filters([
                //
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
            RelationManagers\ClicksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdrawalRequests::route('/'),
            'create' => Pages\CreateWithdrawalRequest::route('/create'),
            'edit' => Pages\EditWithdrawalRequest::route('/{record}/edit'),
        ];
    }
}
