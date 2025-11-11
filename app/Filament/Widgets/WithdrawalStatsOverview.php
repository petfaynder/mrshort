<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User; // Kullanıcı bakiyeleri için User modelini kullanacağız
use App\Models\WithdrawalRequest; // Para çekme talepleri için WithdrawalRequest modelini kullanacağız
use Illuminate\Support\Facades\DB; // Veritabanı sorguları için

class WithdrawalStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0; // Sayfanın en üstünde görünmesi için

    protected function getStats(): array
    {
        // Örnek istatistik verileri (gerçek sorgularla değiştirilecek)
        $publishersAvailableBalance = User::sum('earnings'); // Kullanıcıların toplam bakiyesi
        $referralEarnings = User::sum('referral_earnings'); // Kullanıcıların toplam referans kazancı
        $pendingWithdrawals = WithdrawalRequest::where('status', 'pending')->sum('amount'); // Bekleyen para çekme taleplerinin toplam miktarı
        $totalWithdrawals = WithdrawalRequest::where('status', 'completed')->sum('amount'); // Tamamlanmış para çekme taleplerinin toplam miktarı

        return [
            Stat::make('Publishers Available Balance', '$' . number_format($publishersAvailableBalance, 2))
                ->description('Yayıncıların Kullanılabilir Bakiyesi')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Örnek grafik verisi
                ->color('info'),
            Stat::make('Referral Earnings', '$' . number_format($referralEarnings, 2))
                ->description('Referans Kazançları')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Örnek grafik verisi
                ->color('primary'),
            Stat::make('Pending Withdrawn', '$' . number_format($pendingWithdrawals, 2))
                ->description('Bekleyen Para Çekme Talepleri')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Örnek grafik verisi
                ->color('warning'),
            Stat::make('Total Withdraw', '$' . number_format($totalWithdrawals, 2))
                ->description('Toplam Çekilen Miktar')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Örnek grafik verisi
                ->color('success'),
        ];
    }
}