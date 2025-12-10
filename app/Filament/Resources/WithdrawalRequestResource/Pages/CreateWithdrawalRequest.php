<?php

namespace App\Filament\Resources\WithdrawalRequestResource\Pages;

use App\Filament\Resources\WithdrawalRequestResource;
use App\Models\LinkClick;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWithdrawalRequest extends CreateRecord
{
    protected static string $resource = WithdrawalRequestResource::class;

    protected function afterCreate(): void
    {
        $withdrawalRequest = $this->getRecord();

        \Log::info('AfterCreate: Withdrawal Request created', [
            'id' => $withdrawalRequest->id, 
            'user_id' => $withdrawalRequest->user_id
        ]);

        // Kullanıcının henüz bir para çekme talebiyle ilişkilendirilmemiş tıklamalarını bul
        // NOT: link_clicks tablosunda user_id yok, link relationship üzerinden bul
        $clicksToUpdate = LinkClick::whereHas('link', function($query) use ($withdrawalRequest) {
            $query->where('user_id', $withdrawalRequest->user_id);
        })->whereNull('withdrawal_id')->get();

        \Log::info('AfterCreate: Clicks to update found', [
            'count' => $clicksToUpdate->count(), 
            'click_ids' => $clicksToUpdate->pluck('id')->toArray()
        ]);

        // Bulunan tıklamaların withdrawal_id alanını güncelle
        foreach ($clicksToUpdate as $click) {
            $click->withdrawal_id = $withdrawalRequest->id;
            $click->save();
        }

        \Log::info('AfterCreate: Clicks linked to withdrawal', [
            'withdrawal_id' => $withdrawalRequest->id,
            'linked_count' => $clicksToUpdate->count()
        ]);

        // Fraud score hesapla ve kaydet
        $fraudResult = $withdrawalRequest->calculateAndSaveFraudScore();

        \Log::info('AfterCreate: Fraud score calculated', [
            'withdrawal_id' => $withdrawalRequest->id,
            'fraud_score' => $fraudResult['score'],
            'is_flagged' => $fraudResult['is_flagged'],
            'flag_reason' => $fraudResult['flag_reason']
        ]);

        // Flagged ise admin'e bildirim gönder
        if ($fraudResult['is_flagged']) {
            \Filament\Notifications\Notification::make()
                ->title('⚠️ Şüpheli Para Çekme Talebi')
                ->body("Talep #{$withdrawalRequest->id} otomatik olarak işaretlendi: {$fraudResult['flag_reason']}")
                ->warning()
                ->persistent()
                ->send();
        }
    }
}
