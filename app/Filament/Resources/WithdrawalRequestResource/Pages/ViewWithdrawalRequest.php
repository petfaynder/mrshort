<?php

namespace App\Filament\Resources\WithdrawalRequestResource\Pages;

use App\Filament\Resources\WithdrawalRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Support\Enums\FontWeight;

class ViewWithdrawalRequest extends ViewRecord
{
    protected static string $resource = WithdrawalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('recalculate')
                ->label('Fraud Skoru Yeniden Hesapla')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $result = $this->record->calculateAndSaveFraudScore();
                    \Filament\Notifications\Notification::make()
                        ->title('Fraud skoru güncellendi')
                        ->body("Yeni skor: {$result['score']}")
                        ->success()
                        ->send();
                    $this->refreshFormData(['fraud_score', 'is_flagged', 'flag_reason']);
                }),
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        $stats = $this->record->getTrafficStats();
        $riskLevel = $this->record->getRiskLevel();

        return $infolist
            ->schema([
                // Özet Kartlar
                Section::make('Özet Bilgiler')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Group::make([
                                    TextEntry::make('amount')
                                        ->label('Talep Tutarı')
                                        ->money('USD')
                                        ->size(TextEntry\TextEntrySize::Large)
                                        ->weight(FontWeight::Bold),
                                ]),
                                Group::make([
                                    TextEntry::make('status')
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
                                        }),
                                ]),
                                Group::make([
                                    TextEntry::make('fraud_score')
                                        ->label('Risk Skoru')
                                        ->badge()
                                        ->color($riskLevel['color'])
                                        ->default(0),
                                ]),
                                Group::make([
                                    IconEntry::make('is_flagged')
                                        ->label('İşaretli')
                                        ->boolean()
                                        ->trueIcon('heroicon-o-flag')
                                        ->falseIcon('heroicon-o-check-circle')
                                        ->trueColor('danger')
                                        ->falseColor('success'),
                                ]),
                            ]),
                    ]),

                // Kullanıcı Bilgileri
                Section::make('Kullanıcı Bilgileri')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('Kullanıcı Adı'),
                                TextEntry::make('user.email')
                                    ->label('Email'),
                                TextEntry::make('created_at')
                                    ->label('Talep Tarihi')
                                    ->dateTime('d.m.Y H:i'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('payment_method')
                                    ->label('Ödeme Yöntemi')
                                    ->default('Belirtilmemiş'),
                                TextEntry::make('user.earnings')
                                    ->label('Toplam Kazanç')
                                    ->money('USD'),
                            ]),
                    ])->collapsible(),

                // Trafik İstatistikleri
                Section::make('📊 Trafik İstatistikleri')
                    ->description('Bu para çekme talebine bağlı tıklamaların analizi')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('total_clicks')
                                    ->label('Toplam Tıklama')
                                    ->state(fn () => number_format($stats['total_clicks']))
                                    ->size(TextEntry\TextEntrySize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->icon('heroicon-o-cursor-arrow-rays'),
                                TextEntry::make('bot_stats')
                                    ->label('Bot Tıklama')
                                    ->state(fn () => $stats['bot_clicks'] . ' (' . $stats['bot_percentage'] . '%)')
                                    ->color(fn () => $stats['bot_percentage'] > 20 ? 'danger' : ($stats['bot_percentage'] > 10 ? 'warning' : 'success'))
                                    ->icon('heroicon-o-cpu-chip'),
                                TextEntry::make('unique_ips')
                                    ->label('Unique IP')
                                    ->state(fn () => number_format($stats['unique_ips']))
                                    ->icon('heroicon-o-globe-alt'),
                                TextEntry::make('unique_countries')
                                    ->label('Ülke Sayısı')
                                    ->state(fn () => $stats['unique_countries'])
                                    ->icon('heroicon-o-flag'),
                            ]),
                    ]),

                // Cihaz Dağılımı
                Section::make('📱 Cihaz Dağılımı')
                    ->schema([
                        Grid::make(3)
                            ->schema(
                                collect($stats['top_devices'])->map(function ($data, $device) {
                                    return TextEntry::make("device_{$device}")
                                        ->label($device ?: 'Bilinmiyor')
                                        ->state("{$data['count']} ({$data['percentage']}%)");
                                })->values()->toArray()
                            ),
                    ])->collapsible()->collapsed(),

                // Tarayıcı Dağılımı
                Section::make('🌐 Tarayıcı Dağılımı')
                    ->schema([
                        Grid::make(3)
                            ->schema(
                                collect($stats['top_browsers'])->take(6)->map(function ($data, $browser) {
                                    return TextEntry::make("browser_{$browser}")
                                        ->label($browser ?: 'Bilinmiyor')
                                        ->state("{$data['count']} ({$data['percentage']}%)");
                                })->values()->toArray()
                            ),
                    ])->collapsible()->collapsed(),

                // İşletim Sistemi Dağılımı
                Section::make('💻 İşletim Sistemi Dağılımı')
                    ->schema([
                        Grid::make(3)
                            ->schema(
                                collect($stats['top_os'])->take(6)->map(function ($data, $os) {
                                    return TextEntry::make("os_{$os}")
                                        ->label($os ?: 'Bilinmiyor')
                                        ->state("{$data['count']} ({$data['percentage']}%)");
                                })->values()->toArray()
                            ),
                    ])->collapsible()->collapsed(),

                // Fraud Detayları
                Section::make('🔍 Fraud Analizi')
                    ->schema([
                        TextEntry::make('fraud_score')
                            ->label('Fraud Skoru')
                            ->badge()
                            ->size(TextEntry\TextEntrySize::Large)
                            ->color($riskLevel['color'])
                            ->default(0),
                        TextEntry::make('risk_level')
                            ->label('Risk Seviyesi')
                            ->state($riskLevel['label'])
                            ->badge()
                            ->color($riskLevel['color']),
                        TextEntry::make('flag_reason')
                            ->label('İşaretlenme Nedeni')
                            ->placeholder('İşaretlenmemiş')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
