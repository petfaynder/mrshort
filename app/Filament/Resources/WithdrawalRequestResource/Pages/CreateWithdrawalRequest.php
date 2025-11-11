<?php

namespace App\Filament\Resources\WithdrawalRequestResource\Pages;

use App\Filament\Resources\WithdrawalRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWithdrawalRequest extends CreateRecord
{
    protected static string $resource = WithdrawalRequestResource::class;

    protected function afterCreate(): void
    {
        $withdrawalRequest = $this->getRecord();

        \Log::info('AfterCreate: Withdrawal Request created', ['id' => $withdrawalRequest->id, 'user_id' => $withdrawalRequest->user_id]);

        // Kullanıcının henüz bir para çekme talebiyle ilişkilendirilmemiş tıklamalarını bul
        $clicksToUpdate = \App\Models\LinkClick::where('user_id', $withdrawalRequest->user_id)
            ->whereNull('withdrawal_id')
            ->get();

        \Log::info('AfterCreate: Clicks to update found', ['count' => $clicksToUpdate->count(), 'clicks' => $clicksToUpdate->pluck('id')->toArray()]);

        // Bulunan tıklamaların withdrawal_id alanını güncelle
        foreach ($clicksToUpdate as $click) {
            $click->withdrawal_id = $withdrawalRequest->id;
            $click->save();
        }

        \Log::info('AfterCreate: Clicks updated', ['withdrawal_id' => $withdrawalRequest->id]);
    }
}
