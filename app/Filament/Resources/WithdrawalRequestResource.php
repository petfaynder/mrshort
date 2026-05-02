<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalRequestResource\Pages;
use App\Filament\Resources\WithdrawalRequestResource\RelationManagers;
use App\Models\WithdrawalRequest;
use App\Services\FraudScoreService;
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

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationGroup = 'Finansal İşlemler';
    
    protected static ?string $navigationLabel = 'Para Çekme Talepleri';
    
    protected static ?string $modelLabel = 'Para Çekme Talebi';
    
    protected static ?string $pluralModelLabel = 'Para Çekme Talepleri';
    
    protected static ?int $navigationSort = 1;
    
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        // Flagged pending varsa kırmızı
        $flaggedCount = static::getModel()::where('status', 'pending')->where('is_flagged', true)->count();
        return $flaggedCount > 0 ? 'danger' : 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Talep Bilgileri')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Kullanıcı')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Tutar')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending'   => 'Beklemede',
                                'approved'  => 'Onaylandı',
                                'completed' => 'Tamamlandı',
                                'cancelled' => 'İptal Edildi',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('payment_method')
                            ->label('Ödeme Yöntemi'),
                    ])->columns(2),

                Forms\Components\Section::make('💳 Ödeme Hesabı Detayları')
                    ->description('Kullanıcının ödemeyi almak istediği hesap bilgileri')
                    ->schema([
                        Forms\Components\KeyValue::make('payment_details')
                            ->label('Ödeme Detayları')
                            ->keyLabel('Alan')
                            ->valueLabel('Değer')
                            ->disabled()
                            ->columnSpanFull()
                            ->helperText('PayPal: email | Banka: iban, account_holder, bank_name, swift_bic'),
                    ])->collapsible()->collapsed(false),
                
                Forms\Components\Section::make('Fraud Analizi')
                    ->schema([
                        Forms\Components\TextInput::make('fraud_score')
                            ->label('Fraud Skoru')
                            ->disabled(),
                        Forms\Components\Toggle::make('is_flagged')
                            ->label('İşaretli')
                            ->disabled(),
                        Forms\Components\Textarea::make('flag_reason')
                            ->label('İşaretlenme Nedeni')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_flagged')
                    ->label('🚩')
                    ->boolean()
                    ->trueIcon('heroicon-o-flag')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('danger')
                    ->falseColor('gray')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('amount')
                    ->label('Tutar')
                    ->money('USD')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Beklemede',
                        'approved' => 'Onaylandı',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    })
                    ->sortable(),
                
                // Trafik İstatistikleri
                Tables\Columns\TextColumn::make('clicks_count')
                    ->label('Tıklama')
                    ->counts('clicks')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('bot_percentage')
                    ->label('Bot %')
                    ->getStateUsing(function (WithdrawalRequest $record): string {
                        $total = $record->clicks()->count();
                        if ($total === 0) return '-';
                        $bots = $record->clicks()->where('is_bot', true)->count();
                        return round($bots / $total * 100, 1) . '%';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match(true) {
                        $state === '-' => 'gray',
                        (float) rtrim($state, '%') > 20 => 'danger',
                        (float) rtrim($state, '%') > 10 => 'warning',
                        default => 'success',
                    }),
                    
                Tables\Columns\TextColumn::make('unique_ips')
                    ->label('Unique IP')
                    ->getStateUsing(function (WithdrawalRequest $record): int {
                        return $record->clicks()->distinct('ip_address')->count('ip_address');
                    })
                    ->numeric(),
                    
                Tables\Columns\TextColumn::make('fraud_score')
                    ->label('Risk')
                    ->badge()
                    ->formatStateUsing(fn (?int $state): string => match(true) {
                        $state === null || $state === 0 => "⚪ -",
                        $state <= 20 => "🟢 $state",
                        $state <= 40 => "🟡 $state",
                        $state <= 60 => "🟠 $state",
                        default => "🔴 $state",
                    })
                    ->color(fn (?int $state): string => match(true) {
                        $state === null || $state === 0 => 'gray',
                        $state <= 20 => 'success',
                        $state <= 40 => 'warning',
                        $state <= 60 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Ödeme')
                    ->placeholder('Belirtilmemiş'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'approved' => 'Onaylandı',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal',
                    ]),
                Tables\Filters\TernaryFilter::make('is_flagged')
                    ->label('İşaretli')
                    ->trueLabel('Sadece İşaretliler')
                    ->falseLabel('İşaretsizler')
                    ->placeholder('Tümü'),
                Tables\Filters\Filter::make('high_risk')
                    ->label('Yüksek Riskli (Score > 40)')
                    ->query(fn (Builder $query): Builder => $query->where('fraud_score', '>', 40)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detay'),
                Tables\Actions\Action::make('recalculate_fraud')
                    ->label('🔄')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->tooltip('Fraud Skoru Yeniden Hesapla')
                    ->action(function (WithdrawalRequest $record): void {
                        $result = $record->calculateAndSaveFraudScore();
                        \Filament\Notifications\Notification::make()
                            ->title('Fraud skoru güncellendi')
                            ->body("Yeni skor: {$result['score']}")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('approve')
                    ->label('Onayla')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (WithdrawalRequest $record): void {
                        $record->update(['status' => 'approved']);
                        
                        // Send email notification to user
                        \App\Services\EmailService::sendWithdrawalStatusEmail($record, 'approved');
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Talep onaylandı')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Talebi Onayla')
                    ->modalDescription(fn (WithdrawalRequest $record): string => 
                        $record->is_flagged 
                            ? "⚠️ DİKKAT: Bu talep işaretli! Neden: {$record->flag_reason}. Yine de onaylamak istiyor musunuz?"
                            : 'Bu para çekme talebini onaylamak istediğinizden emin misiniz?'
                    )
                    ->visible(fn (WithdrawalRequest $record): bool => $record->status === 'pending'),
                Tables\Actions\Action::make('complete')
                    ->label('Tamamla')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->action(function (WithdrawalRequest $record): void {
                        $record->update(['status' => 'completed']);
                        
                        // Send email notification to user
                        \App\Services\EmailService::sendWithdrawalStatusEmail($record, 'completed');
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Ödeme tamamlandı')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Ödemeyi Tamamla')
                    ->modalDescription('Bu ödemeyi tamamlandı olarak işaretlemek istediğinizden emin misiniz?')
                    ->visible(fn (WithdrawalRequest $record): bool => $record->status === 'approved'),
                Tables\Actions\Action::make('reject')
                    ->label('Reddet')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (WithdrawalRequest $record): void {
                        $record->update(['status' => 'cancelled']);
                        
                        // Send email notification to user
                        \App\Services\EmailService::sendWithdrawalStatusEmail($record, 'cancelled', 'Your withdrawal request was rejected by admin.');
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Talep reddedildi')
                            ->warning()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Talebi Reddet')
                    ->visible(fn (WithdrawalRequest $record): bool => in_array($record->status, ['pending', 'approved'])),
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
            'view' => Pages\ViewWithdrawalRequest::route('/{record}'),
            'edit' => Pages\EditWithdrawalRequest::route('/{record}/edit'),
        ];
    }
}


