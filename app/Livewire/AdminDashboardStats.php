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

    // Yeni Widget Verileri
    public $recentLinks = [];
    public $userGrowthData = ['labels' => [], 'data' => []];
    public $earningsComparisonData = ['admin' => 0, 'publisher' => 0];
    public $recentActivity = [];

    public $dateFilter = 'last_30_days';

    protected $listeners = ['dateFilterChanged' => 'updateDateFilter'];

    public function updateDateFilter($filter)
    {
        $this->dateFilter = $filter;
        $this->loadStats();
    }

    public function mount()
    {
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

        // Link Aktivitesi Metrikleri
        $this->newLinksLast24Hours = Link::where('created_at', '>=', Carbon::now()->subDay())->count();
        $this->newLinksLast7Days = Link::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $this->totalActiveLinks = Link::count();

        // Operasyonel Metrikler
        $this->pendingWithdrawalRequestsCount = WithdrawalRequest::where('status', 'pending')->count();
        $this->pendingWithdrawalRequestsAmount = WithdrawalRequest::where('status', 'pending')->sum('amount');
        $this->openSupportTicketsCount = Ticket::where('status', 'open')->count();

        // Top Countries - improved query
        $this->topCountries = LinkClick::select('country', DB::raw('count(*) as total_clicks'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->orderByDesc('total_clicks')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $totalSystemClicks = $this->totalViews > 0 ? $this->totalViews : 1;
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

        $this->chartLabels = $dailyClicks->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray();
        $this->chartData = $dailyClicks->pluck('views')->toArray();
        $this->dailyClicksData = ['labels' => $this->chartLabels, 'data' => $this->chartData];

        // Detaylı Veri Tablosu - Gerçek Günlük Kazanç Hesaplaması
        $dailyEarnings = LinkClick::selectRaw('DATE(created_at) as date, COUNT(*) as views, SUM(CASE WHEN cpm_rate > 0 THEN 1 ELSE 0 END) as paid_views, SUM(cpm_rate / 1000) as earnings')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $this->dailyStatsTableData = $dailyEarnings->map(function ($item) {
            $views = $item->views ?? 0;
            $paidViews = $item->paid_views ?? 0;
            $earnings = $item->earnings ?? 0;
            $dailyCpm = $paidViews > 0 ? ($earnings / $paidViews) * 1000 : 0;
            
            return [
                'date' => $item->date,
                'views' => $views,
                'paid_views' => $paidViews,
                'link_earnings' => '$' . number_format($earnings, 4),
                'referral_earnings' => '$0.00', // Referral günlük olarak ayrı hesaplanmalı
                'total_publisher_earnings' => '$' . number_format($earnings, 4),
                'daily_cpm' => '$' . number_format($dailyCpm, 4),
            ];
        })->toArray();

        // === YENİ WIDGET VERİLERİ ===

        // 1. Son Kısaltılan Linkler
        $this->recentLinks = Link::with('user')
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(function ($link) {
                return [
                    'short_code' => $link->short_code,
                    'original_url' => \Str::limit($link->original_url, 40),
                    'user' => $link->user ? $link->user->name : 'Anonymous',
                    'clicks' => $link->clicks()->count(),
                    'created_at' => $link->created_at->diffForHumans(),
                ];
            })->toArray();

        // 2. Kullanıcı Büyüme Grafiği (Son 12 Hafta)
        $userGrowth = User::selectRaw('YEARWEEK(created_at, 1) as week, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subWeeks(12))
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get();

        $this->userGrowthData = [
            'labels' => $userGrowth->pluck('week')->map(function ($week) {
                $year = substr($week, 0, 4);
                $weekNum = substr($week, 4);
                return 'W' . $weekNum;
            })->toArray(),
            'data' => $userGrowth->pluck('count')->toArray()
        ];

        // 3. Admin Kar vs Yayıncı Kazanç
        $totalSystemEarnings = LinkClick::sum(DB::raw('cpm_rate / 1000'));
        $publisherPayout = User::sum('link_earnings');
        $adminProfit = $totalSystemEarnings - $publisherPayout;
        
        $this->earningsComparisonData = [
            'admin' => max(0, $adminProfit),
            'publisher' => $publisherPayout,
            'total' => $totalSystemEarnings
        ];

        // 4. Canlı Aktivite Feed
        $this->recentActivity = collect()
            ->merge(
                User::orderByDesc('created_at')->take(5)->get()->map(fn($u) => [
                    'type' => 'user_registered',
                    'icon' => 'user-plus',
                    'color' => 'sky',
                    'message' => $u->name . ' joined',
                    'time' => $u->created_at,
                ])
            )
            ->merge(
                Link::orderByDesc('created_at')->take(5)->get()->map(fn($l) => [
                    'type' => 'link_created',
                    'icon' => 'link',
                    'color' => 'emerald',
                    'message' => 'New link: ' . $l->short_code,
                    'time' => $l->created_at,
                ])
            )
            ->merge(
                WithdrawalRequest::where('status', 'pending')->orderByDesc('created_at')->take(5)->get()->map(fn($w) => [
                    'type' => 'withdrawal_pending',
                    'icon' => 'banknotes',
                    'color' => 'amber',
                    'message' => 'Withdrawal: $' . number_format($w->amount, 2),
                    'time' => $w->created_at,
                ])
            )
            ->sortByDesc('time')
            ->take(10)
            ->map(function ($item) {
                $item['time_ago'] = Carbon::parse($item['time'])->diffForHumans();
                return $item;
            })
            ->values()
            ->toArray();
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
            // Yeni widgetlar
            'recentLinks' => $this->recentLinks,
            'userGrowthData' => $this->userGrowthData,
            'earningsComparisonData' => $this->earningsComparisonData,
            'recentActivity' => $this->recentActivity,
        ]);
    }
}
