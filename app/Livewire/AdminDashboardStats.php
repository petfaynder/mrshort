<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\WithdrawalRequest;
use App\Models\Ticket;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardStats extends Component
{
    // Temel Kazanç ve Görüntülenme Metrikleri
    public $totalPublisherEarnings = 0;
    public $totalLinkEarnings = 0;
    public $totalReferralEarnings = 0;
    public $totalViews = 0;

    // Kullanıcı Aktivitesi Metrikleri
    public $newUsersLast24Hours = 0;
    public $newUsersLast7Days = 0;
    // public $activeUsers = 0; // Opsiyonel

    // Link Aktivitesi Metrikleri
    public $newLinksLast24Hours = 0;
    public $newLinksLast7Days = 0;
    public $totalActiveLinks = 0;

    // Operasyonel Metrikler
    public $pendingWithdrawalRequestsCount = 0;
    public $pendingWithdrawalRequestsAmount = 0;
    public $openSupportTicketsCount = 0;

    // Hızlı Bakış İçin Özet Bilgiler
    public $topCountries = [];
    public $recentAnnouncements = [];

    // İstatistik Grafiği Verileri
    public $dailyClicksData = ['labels' => [], 'data' => []];
    public $chartLabels = [];
    public $chartData = [];


    // Detaylı Veri Tablosu Verileri
    public $dailyStatsTableData = [];

    public $dateFilter = 'last_30_days'; // Varsayılan filtre

    protected $listeners = ['dateFilterChanged' => 'updateDateFilter'];

    public function updateDateFilter($filter)
    {
        $this->dateFilter = $filter;
        $this->loadStats(); // Filtre değiştiğinde istatistikleri yeniden yükle
    }

    public function mount()
    {
        \Log::info('AdminDashboardStats mount() called.');
        $this->loadStats();
    }

    public function loadStats()
    {
        // Temel Kazanç ve Görüntülenme Metrikleri
        $this->totalPublisherEarnings = User::sum('earnings');
        $this->totalLinkEarnings = User::sum('link_earnings');
        $this->totalReferralEarnings = User::sum('referral_earnings');
        $this->totalViews = LinkClick::count();

        // Kullanıcı Aktivitesi Metrikleri
        $this->newUsersLast24Hours = User::where('created_at', '>=', Carbon::now()->subDay())->count();
        $this->newUsersLast7Days = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        \Log::info('AdminDashboardStats loadStats() - newUsersLast24Hours değeri: ' . $this->newUsersLast24Hours);

        // Link Aktivitesi Metrikleri
        $this->newLinksLast24Hours = Link::where('created_at', '>=', Carbon::now()->subDay())->count();
        $this->newLinksLast7Days = Link::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $this->totalActiveLinks = Link::count(); // Basitçe tüm linkler, 'active' durumu varsa ona göre filtrelenebilir

        // Operasyonel Metrikler
        $this->pendingWithdrawalRequestsCount = WithdrawalRequest::where('status', 'pending')->count();
        $this->pendingWithdrawalRequestsAmount = WithdrawalRequest::where('status', 'pending')->sum('amount');
        $this->openSupportTicketsCount = Ticket::where('status', 'open')->count();

        // Hızlı Bakış İçin Özet Bilgiler
        $this->topCountries = LinkClick::select('country', DB::raw('count(*) as total_clicks'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('total_clicks')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $totalSystemClicks = $this->totalViews > 0 ? $this->totalViews : 1; // Sıfıra bölme hatasını engelle
                return [
                    'name' => $item->country,
                    'clicks' => $item->total_clicks,
                    'percentage' => round(($item->total_clicks / $totalSystemClicks) * 100, 2)
                ];
            })->toArray();

        $this->recentAnnouncements = Announcement::orderByDesc('created_at')->take(3)->get();

        // Tarih filtresine göre başlangıç ve bitiş tarihlerini belirle
        $startDate = $this->getStartDateFromFilter();
        $endDate = Carbon::now();


        // İstatistik Grafiği Verileri (Günlük Tıklanma)
        $dailyClicks = LinkClick::selectRaw('DATE(created_at) as date, COUNT(*) as views')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $this->chartLabels = $dailyClicks->pluck('date')->toArray();
        $this->chartData = $dailyClicks->pluck('views')->toArray();
        $this->dailyClicksData = ['labels' => $this->chartLabels, 'data' => $this->chartData];


        // Detaylı Veri Tablosu Verileri (Kümülatif Yaklaşım)
        $tableData = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->toDateString();
            $viewsToday = LinkClick::whereDate('created_at', $dateStr)->count();

            // O gün sonu itibarıyla kümülatif toplamlar (Bu, gerçek günlük kazanç değildir)
            $totalLinkEarningsEndOfDay = User::sum('link_earnings'); 
            $totalReferralEarningsEndOfDay = User::sum('referral_earnings'); 
            
            $totalPublisherEarningsEndOfDay = $totalLinkEarningsEndOfDay + $totalReferralEarningsEndOfDay;
            // Günlük CPM'i o günkü tıklama ve o günkü *gerçek* kazanç üzerinden hesaplamak daha doğru olur.
            // Şimdilik, o günkü tıklamalar ve genel kümülatif kazanç üzerinden bir yaklaşım sunuluyor.
            // Bu, ileride `earnings_transactions` tablosu ile iyileştirilmelidir.
            $dailyCpm = 0;
            if ($viewsToday > 0) {
                 // Geçici olarak, günlük kazancı o günkü tıklama başına ortalama kazançla tahmin edebiliriz.
                 // Veya bu sütunu "N/A" olarak bırakabiliriz.
                 // $averageEarningPerClick = $this->totalPublisherEarnings / ($this->totalViews > 0 ? $this->totalViews : 1);
                 // $estimatedDailyEarning = $viewsToday * $averageEarningPerClick;
                 // $dailyCpm = ($estimatedDailyEarning / $viewsToday) * 1000;
                 // Şimdilik CPM'i genel ortalama üzerinden değil, sadece tıklama varsa bir değer göstermek için boş bırakıyoruz.
            }


            $tableData[] = [
                'date' => $dateStr,
                'views' => $viewsToday,
                'link_earnings' => number_format(0, 2), // Gerçek günlük için iyileştirme
                'referral_earnings' => number_format(0, 2), // Gerçek günlük için iyileştirme
                'total_publisher_earnings' => number_format(0, 2), // Gerçek günlük için iyileştirme
                'daily_cpm' => number_format(0, 2),
            ];
            $currentDate->addDay();
        }
        $this->dailyStatsTableData = array_reverse($tableData); // En son gün en üstte
        \Log::info('AdminDashboardStats loadStats() finished. totalPublisherEarnings: ' . $this->totalPublisherEarnings);
        \Log::info('totalPublisherEarnings değeri: ' . $this->totalPublisherEarnings);
    }

    private function getStartDateFromFilter()
    {
        switch ($this->dateFilter) {
            case 'last_7_days':
                return Carbon::now()->subDays(6)->startOfDay();
            case 'last_30_days':
                return Carbon::now()->subDays(29)->startOfDay();
            case 'this_month':
                return Carbon::now()->startOfMonth();
            case 'last_month':
                return Carbon::now()->subMonthNoOverflow()->startOfMonth();
            default:
                return Carbon::now()->subDays(29)->startOfDay();
        }
    }

    public function render()
    {
        \Log::info('AdminDashboardStats render() called. newUsersLast24Hours değeri before view: ' . $this->newUsersLast24Hours);
        \Log::info('AdminDashboardStats render() called. totalPublisherEarnings before view: ' . $this->totalPublisherEarnings);
        \Log::info('AdminDashboardStats render() - View\'a gönderilen veriler: ' . json_encode([
            'totalPublisherEarnings' => $this->totalPublisherEarnings,
            'totalLinkEarnings' => $this->totalLinkEarnings,
            'totalReferralEarnings' => $this->totalReferralEarnings,
            'totalViews' => $this->totalViews,
            'newUsersLast24Hours' => $this->newUsersLast24Hours,
            'newUsersLast7Days' => $this->newUsersLast7Days,
            'newLinksLast24Hours' => $this->newLinksLast24Hours,
            'newLinksLast7Days' => $this->newLinksLast7Days,
            'totalActiveLinks' => $this->totalActiveLinks,
            'pendingWithdrawalRequestsCount' => $this->pendingWithdrawalRequestsCount,
            'pendingWithdrawalRequestsAmount' => $this->pendingWithdrawalRequestsAmount,
            'openSupportTicketsCount' => $this->openSupportTicketsCount,
            'topCountries' => $this->topCountries,
            'recentAnnouncements' => $this->recentAnnouncements,
            'dailyClicksData' => $this->dailyClicksData,
            'chartLabels' => $this->chartLabels,
            'chartData' => $this->chartData,
            'dailyStatsTableData' => $this->dailyStatsTableData,
            'dateFilter' => $this->dateFilter,
        ]));
        return view('livewire.admin-dashboard-stats', [
            'totalPublisherEarnings' => $this->totalPublisherEarnings,
            'totalLinkEarnings' => $this->totalLinkEarnings,
            'totalReferralEarnings' => $this->totalReferralEarnings,
            'totalViews' => $this->totalViews,
            'newUsersLast24Hours' => $this->newUsersLast24Hours,
            'newUsersLast7Days' => $this->newUsersLast7Days,
            'newLinksLast24Hours' => $this->newLinksLast24Hours,
            'newLinksLast7Days' => $this->newLinksLast7Days,
            'totalActiveLinks' => $this->totalActiveLinks,
            'pendingWithdrawalRequestsCount' => $this->pendingWithdrawalRequestsCount,
            'pendingWithdrawalRequestsAmount' => $this->pendingWithdrawalRequestsAmount,
            'openSupportTicketsCount' => $this->openSupportTicketsCount,
            'topCountries' => $this->topCountries,
            'recentAnnouncements' => $this->recentAnnouncements,
            'dailyClicksData' => $this->dailyClicksData,
            'chartLabels' => $this->chartLabels,
            'chartData' => $this->chartData,
            'dailyStatsTableData' => $this->dailyStatsTableData,
            'dateFilter' => $this->dateFilter,
        ]);
    }
}
