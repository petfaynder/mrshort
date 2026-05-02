<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkClick;
use Carbon\Carbon;

class ReportsManager extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $targetUserId;

    public $startDate;
    public $endDate;
    public $selectedPreset = 'last_30_days'; // Varsayılan olarak son 30 gün
    public $reportData = [];
    public $totalClicks = 0;
    public $totalEarnings = 0.0;
    public $clicksByCountryChartData = ['labels' => [], 'data' => []];
    public $heatmapData = [];
    public $clicksOverTime = [];
    public $clicksByDeviceType = [];
    public $clicksByOs = [];
    public $clicksByBrowser = [];
    public $clicksByLink = [];
    public $uniqueClicksByLink = [];
    public $clicksByReferrer = [];
    public $clicksByBotStatus = [];
    public $clicksByRecentClickCount = [];

    public $sortBy = 'total_clicks';
    public $sortDirection = 'desc';


    protected $listeners = ['generateReport' => 'generateReport']; // Livewire event listener

    public function mount($user = null)
    {
        // If a user is passed (e.g. from admin panel), use that user's ID
        // Otherwise fall back to the currently authenticated user
        if ($user) {
            $this->targetUserId = $user instanceof \App\Models\User ? $user->id : (int) $user;
        } else {
            $this->targetUserId = Auth::id();
        }

        $this->setDatesFromPreset();
        $this->generateReport();
    }

    public function updatedSelectedPreset($value)
    {
        $this->setDatesFromPreset();
        $this->generateReport();
    }

    public function updatedStartDate($value)
    {
        $this->generateReport();
    }

    public function updatedEndDate($value)
    {
        $this->generateReport();
    }

    private function setDatesFromPreset()
    {
        switch ($this->selectedPreset) {
            case 'last_7_days':
                $this->startDate = Carbon::now()->subDays(7)->format('Y-m-d');
                break;
            case 'last_30_days':
                $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
                break;
            case 'last_90_days':
                $this->startDate = Carbon::now()->subDays(90)->format('Y-m-d');
                break;
            case 'last_365_days':
                $this->startDate = Carbon::now()->subDays(365)->format('Y-m-d');
                break;
            case 'all_time':
                $this->startDate = null; // Tüm zamanlar için başlangıç tarihi yok
                break;
        }
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function generateReport()
    {
        $userId = $this->targetUserId;

        // ── Build base query builder (NOT executed, cloned per dimension) ───────
        $baseQuery = LinkClick::whereHas('link', function ($q) use ($userId) {
                                    $q->where('user_id', $userId);
                                })
                                ->where('is_skipped', false);

        if ($this->startDate) {
            $baseQuery->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        }

        // ── Aggregate scalars — no rows loaded into PHP ────────────────────────
        $this->totalClicks   = (clone $baseQuery)->count();
        $this->totalEarnings = (clone $baseQuery)->sum('cpm_rate') / 1000;

        // ── Country distribution ───────────────────────────────────────────────
        $countryRows = (clone $baseQuery)
            ->selectRaw('country_id, COUNT(*) as total')
            ->groupBy('country_id')
            ->orderByDesc('total')
            ->with('country')
            ->get();

        $validCountryRows = $countryRows->filter(fn($r) => $r->country !== null);

        $this->clicksByCountryChartData = [
            'labels' => $validCountryRows->pluck('country.iso_code')->values()->toArray(),
            'data'   => $validCountryRows->pluck('total')->values()->toArray(),
        ];

        $this->heatmapData = $countryRows->map(function ($r) {
            if ($r->country && $r->country->latitude && $r->country->longitude) {
                return ['lat' => $r->country->latitude, 'lng' => $r->country->longitude, 'count' => (int) $r->total];
            }
            return null;
        })->filter()->values()->toArray();

        $this->dispatch('heatmap-data-updated', data: $this->clicksByCountryChartData);

        // ── Time trends ───────────────────────────────────────────────────────
        $this->clicksOverTime = (clone $baseQuery)
            ->selectRaw('DATE(created_at) as click_date, COUNT(*) as total')
            ->groupBy('click_date')
            ->orderBy('click_date')
            ->get()
            ->map(fn($r) => ['click_date' => $r->click_date, 'total' => (int) $r->total])
            ->values();

        // ── Device type ───────────────────────────────────────────────────────
        $this->clicksByDeviceType = (clone $baseQuery)
            ->selectRaw('device_type, COUNT(*) as total')
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get()
            ->map(fn($r) => ['device_type' => $r->device_type, 'total' => (int) $r->total])
            ->values()
            ->toArray();

        // ── OS ────────────────────────────────────────────────────────────────
        $this->clicksByOs = (clone $baseQuery)
            ->selectRaw('os, COUNT(*) as total')
            ->groupBy('os')
            ->orderByDesc('total')
            ->get()
            ->map(fn($r) => ['os' => $r->os, 'total' => (int) $r->total])
            ->values()
            ->toArray();

        // ── Browser ───────────────────────────────────────────────────────────
        $this->clicksByBrowser = (clone $baseQuery)
            ->selectRaw('browser, COUNT(*) as total')
            ->groupBy('browser')
            ->orderByDesc('total')
            ->get()
            ->map(fn($r) => ['browser' => $r->browser, 'total' => (int) $r->total])
            ->values()
            ->toArray();

        // ── Per-link stats (total + unique clicks + earnings) ─────────────────
        $linkRows = (clone $baseQuery)
            ->selectRaw('link_id, COUNT(*) as total_clicks, SUM(cpm_rate) as total_cpm, COUNT(DISTINCT ip_address) as unique_ips')
            ->groupBy('link_id')
            ->with('link')
            ->get();

        $sortField = $this->sortBy;
        $sortDir   = $this->sortDirection;

        $clicksByLinkData = $linkRows->map(function ($r) {
            $link = $r->link;
            if (!$link) return null;
            return [
                'link_id'      => $link->id,
                'original_url' => $link->original_url,
                'short_link'   => $link->shortLink(),
                'total_clicks' => (int) $r->total_clicks,
                'earnings'     => round($r->total_cpm / 1000, 6),
            ];
        })->filter()->values();

        $this->clicksByLink = ($sortDir === 'asc'
            ? $clicksByLinkData->sortBy($sortField)
            : $clicksByLinkData->sortByDesc($sortField)
        )->values()->all();

        // Unique clicks per link — already computed via COUNT(DISTINCT) above
        $this->uniqueClicksByLink = $linkRows->pluck('unique_ips', 'link_id');

        // ── Referrer ──────────────────────────────────────────────────────────
        $this->clicksByReferrer = (clone $baseQuery)
            ->selectRaw('referrer, COUNT(*) as total')
            ->groupBy('referrer')
            ->orderByDesc('total')
            ->get()
            ->map(fn($r) => ['referrer' => $r->referrer, 'total' => (int) $r->total])
            ->values()
            ->toArray();

        // ── Bot status ────────────────────────────────────────────────────────
        $this->clicksByBotStatus = (clone $baseQuery)
            ->selectRaw('is_bot, COUNT(*) as total')
            ->groupBy('is_bot')
            ->orderByDesc('total')
            ->get()
            ->map(fn($r) => ['is_bot' => (bool) $r->is_bot, 'total' => (int) $r->total])
            ->values()
            ->toArray();

        // ── Recent click count distribution ───────────────────────────────────
        $this->clicksByRecentClickCount = (clone $baseQuery)
            ->selectRaw('recent_click_count, COUNT(*) as total')
            ->groupBy('recent_click_count')
            ->orderBy('recent_click_count')
            ->get()
            ->map(fn($r) => ['recent_click_count' => $r->recent_click_count, 'total' => (int) $r->total])
            ->values()
            ->toArray();


    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'desc';
        }

        $this->sortBy = $field;
        $this->generateReport();
    }

    public function render()
    {
        return view('livewire.user.reports-manager', [
            'clicksByCountryChartData' => $this->clicksByCountryChartData,
            'heatmapData' => $this->heatmapData,
            'clicksOverTime' => collect($this->clicksOverTime), // Blade'de döngü için Collection'a çevir
            'clicksByDeviceType' => $this->clicksByDeviceType,
            'clicksByOs' => $this->clicksByOs,
            'clicksByBrowser' => $this->clicksByBrowser,
            'clicksByLink' => $this->clicksByLink,
            'uniqueClicksByLink' => $this->uniqueClicksByLink,
            'clicksByReferrer' => $this->clicksByReferrer,
            'clicksByBotStatus' => $this->clicksByBotStatus,
            'clicksByRecentClickCount' => $this->clicksByRecentClickCount,
        ]);
    }

    public function exportCsv($reportType)
    {
        $fileName = 'report_' . $reportType . '_' . Carbon::now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($reportType) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $this->getCsvHeaders($reportType));

            foreach ($this->getCsvData($reportType) as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getCsvHeaders($reportType)
    {
        switch ($reportType) {
            case 'countries':
            case 'countries_table':
                return ['Country', 'Click Count'];
            case 'time_trends':
                return ['Date', 'Click Count'];
            case 'device_types':
                return ['Device Type', 'Click Count'];
            case 'operating_systems':
                return ['Operating System', 'Click Count'];
            case 'browsers':
                return ['Browser', 'Click Count'];
            case 'links':
                return ['Original Link', 'Shortened Link', 'Unique Click', 'Total Click', 'Earnings ($)'];
            case 'referrers':
                return ['Referrer Domain', 'Click Count'];
            case 'bot_status':
                return ['Bot Status', 'Click Count'];
            case 'recent_click_count':
                return ['Click Count in Last 1 Minute', 'Click Count'];
            default:
                return [];
        }
    }

    private function getCsvData($reportType)
    {
        $data = [];
        switch ($reportType) {
            case 'countries':
            case 'countries_table':
                foreach ($this->clicksByCountryChartData['labels'] as $index => $label) {
                    $data[] = [$label, $this->clicksByCountryChartData['data'][$index]];
                }
                break;
            case 'time_trends':
                foreach ($this->clicksOverTime as $item) {
                    $data[] = [$item['click_date'], $item['total']];
                }
                break;
            case 'device_types':
                foreach ($this->clicksByDeviceType as $item) {
                    $data[] = [$item['device_type'] ?? 'Unknown', $item['total']];
                }
                break;
            case 'operating_systems':
                foreach ($this->clicksByOs as $item) {
                    $data[] = [$item['os'] ?? 'Unknown', $item['total']];
                }
                break;
            case 'browsers':
                foreach ($this->clicksByBrowser as $item) {
                    $data[] = [$item['browser'] ?? 'Unknown', $item['total']];
                }
                break;
            case 'links':
                foreach ($this->clicksByLink as $item) {
                    $data[] = [
                        $item['original_url'],
                        $item['short_link'],
                        $this->uniqueClicksByLink->get($item['link_id'], 0),
                        $item['total_clicks'],
                        $item['earnings'],
                    ];
                }
                break;
            case 'referrers':
                foreach ($this->clicksByReferrer as $item) {
                    $data[] = [$item['referrer'] ?? 'Direct / Unknown', $item['total']];
                }
                break;
            case 'bot_status':
                foreach ($this->clicksByBotStatus as $item) {
                    $data[] = [$item['is_bot'] ? 'Bot' : 'Organic', $item['total']];
                }
                break;
            case 'recent_click_count':
                foreach ($this->clicksByRecentClickCount as $item) {
                    $data[] = [$item['recent_click_count'], $item['total']];
                }
                break;
        }
        return $data;
    }
}
