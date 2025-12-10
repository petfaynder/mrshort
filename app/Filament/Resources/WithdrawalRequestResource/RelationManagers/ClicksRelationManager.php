<?php

namespace App\Filament\Resources\WithdrawalRequestResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'clicks';

    protected static ?string $title = 'Tıklama Detayları';

    protected static ?string $modelLabel = 'Tıklama';

    protected static ?string $pluralModelLabel = 'Tıklamalar';

    public function table(Table $table): Table
    {
        // Özet istatistikler hesapla
        $clicks = $this->getOwnerRecord()->clicks;
        $total = $clicks->count();
        $bots = $clicks->where('is_bot', true)->count();
        $uniqueIps = $clicks->unique('ip_address')->count();
        $countries = $clicks->unique('country_id')->count();
        
        $headerStats = $total > 0 
            ? "📊 Toplam: " . number_format($total) . 
              " | 🤖 Bot: {$bots} (" . round($bots / $total * 100, 1) . "%) " .
              " | 🌍 Ülke: {$countries} " .
              " | 🔗 Unique IP: " . number_format($uniqueIps)
            : "Henüz tıklama yok";

        return $table
            ->heading($headerStats)
            ->description('Bu withdrawal talebine bağlı tıklamaların listesi')
            ->recordTitleAttribute('ip_address')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('IP kopyalandı'),
                Tables\Columns\TextColumn::make('country')
                    ->label('Ülke')
                    ->placeholder('Bilinmiyor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('device_type')
                    ->label('Cihaz')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Desktop' => 'primary',
                        'Mobile' => 'success',
                        'Tablet' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('os')
                    ->label('OS')
                    ->placeholder('Bilinmiyor'),
                Tables\Columns\TextColumn::make('browser')
                    ->label('Tarayıcı')
                    ->placeholder('Bilinmiyor'),
                Tables\Columns\TextColumn::make('referrer')
                    ->label('Referrer')
                    ->placeholder('Doğrudan')
                    ->limit(30)
                    ->tooltip(fn ($state) => $state),
                Tables\Columns\IconColumn::make('is_bot')
                    ->label('Bot')
                    ->boolean()
                    ->trueIcon('heroicon-o-cpu-chip')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('danger')
                    ->falseColor('success'),
                Tables\Columns\TextColumn::make('cpm_rate')
                    ->label('CPM')
                    ->numeric(4)
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_bot')
                    ->label('Bot Durumu')
                    ->trueLabel('Sadece Botlar')
                    ->falseLabel('Sadece Gerçek')
                    ->placeholder('Tümü'),
                Tables\Filters\SelectFilter::make('device_type')
                    ->label('Cihaz Türü')
                    ->options([
                        'Desktop' => 'Desktop',
                        'Mobile' => 'Mobile',
                        'Tablet' => 'Tablet',
                        'Other' => 'Diğer',
                    ]),
                Tables\Filters\SelectFilter::make('country')
                    ->label('Ülke')
                    ->searchable()
                    ->preload()
                    ->options(function () {
                        return $this->getOwnerRecord()->clicks()
                            ->whereNotNull('country')
                            ->distinct()
                            ->pluck('country', 'country')
                            ->toArray();
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_csv')
                    ->label('CSV İndir')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $clicks = $this->getOwnerRecord()->clicks;
                        $csv = "IP,Ülke,Cihaz,OS,Tarayıcı,Referrer,Bot,Tarih\n";
                        
                        foreach ($clicks as $click) {
                            $csv .= implode(',', [
                                $click->ip_address,
                                $click->country ?? 'Bilinmiyor',
                                $click->device_type ?? 'Bilinmiyor',
                                $click->os ?? 'Bilinmiyor',
                                $click->browser ?? 'Bilinmiyor',
                                '"' . str_replace('"', '""', $click->referrer ?? 'Doğrudan') . '"',
                                $click->is_bot ? 'Evet' : 'Hayır',
                                $click->created_at->format('Y-m-d H:i:s'),
                            ]) . "\n";
                        }
                        
                        return response()->streamDownload(function () use ($csv) {
                            echo $csv;
                        }, 'clicks_' . $this->getOwnerRecord()->id . '.csv');
                    }),
            ])
            ->actions([
                // Tek tek aksiyon gerekmez
            ])
            ->bulkActions([
                // Bulk aksiyon gerekmez
            ])
            ->paginated([10, 25, 50, 100]);
    }
}
