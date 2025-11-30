<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkClick;
use Carbon\Carbon;

class ReportsManager extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

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

    public function mount()
    {
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
        $user = Auth::user();

        $query = LinkClick::whereHas('link', function ($query) use ($user) {
                                $query->where('user_id', $user->id);
                            });

        if ($this->startDate) {
            $query->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        }

        $clicks = $query->with('link', 'country')->get(); // Link ve Country ilişkilerini yükle

        $this->totalClicks = $clicks->count();
        $this->totalEarnings = $clicks->sum('cpm_rate') / 1000; // CPM oranına göre toplam kazanç

        // Ülkelere göre tıklama dağılımı
        $clicksByCountry = $clicks->groupBy('country_id')->map(function ($group, $countryId) {
            $countryModel = \App\Models\Country::find($countryId); // Country modelini doğrudan ID ile çek
            $countryName = $countryModel ? $countryModel->iso_code : 'Bilinmiyor';
            return [
                'country' => $countryModel,
                'total' => $group->count(),
            ];
        })->sortByDesc('total');

        $this->clicksByCountryChartData = [
            'labels' => $clicksByCountry->pluck('country.iso_code')->map(fn($iso) => $iso ?? 'Bilinmiyor')->toArray(),
            'data' => $clicksByCountry->pluck('total')->toArray(),
        ];

        $this->heatmapData = $clicksByCountry->map(function ($item) {
            if ($item['country'] && $item['country']->latitude && $item['country']->longitude) {
                return [
                    'lat' => $item['country']->latitude,
                    'lng' => $item['country']->longitude,
                    'count' => $item['total']
                ];
            }
            return null;
        })->filter()->values()->toArray();

        $this->dispatch('heatmap-data-updated', data: $this->clicksByCountryChartData);

        // Zamana göre tıklama trendleri
        $this->clicksOverTime = $clicks->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        })->map(function ($row, $date) {
            return [
                'click_date' => $date,
                'total' => $row->count(),
            ];
        })->sortBy('click_date')->values();


        // Cihaz türlerine göre tıklamalar
        $this->clicksByDeviceType = $clicks->groupBy('device_type')->map(function ($group) {
            return [
                'device_type' => $group->first()->device_type,
                'total' => $group->count(),
            ];
        })->sortByDesc('total')->values();

        // İşletim sistemlerine göre tıklamalar
        $this->clicksByOs = $clicks->groupBy('os')->map(function ($group) {
            return [
                'os' => $group->first()->os,
                'total' => $group->count(),
            ];
        })->sortByDesc('total')->values();

        // Tarayıcılara göre tıklamalar
        $this->clicksByBrowser = $clicks->groupBy('browser')->map(function ($group) {
            return [
                'browser' => $group->first()->browser,
                'total' => $group->count(),
            ];
        })->sortByDesc('total')->values();

        // Linklere göre tıklamalar
        $clicksByLinkData = $clicks->groupBy('link_id')->map(function ($group) {
            $link = $group->first()->link;
            return [
                'link_id' => $link->id,
                'original_url' => $link->original_url,
                'short_link' => $link->shortLink(),
                'total_clicks' => $group->count(),
                'earnings' => $group->sum('cpm_rate') / 1000,
            ];
        });

        if ($this->sortDirection === 'asc') {
            $this->clicksByLink = $clicksByLinkData->sortBy($this->sortBy)->values()->all();
        } else {
            $this->clicksByLink = $clicksByLinkData->sortByDesc($this->sortBy)->values()->all();
        }

        // Tekil tıklamalar (IP adresine göre)
        $this->uniqueClicksByLink = $clicks->groupBy('link_id')->mapWithKeys(function ($group, $linkId) {
            return [$linkId => $group->unique('ip_address')->count()];
        });

        // Yönlendiren domainlere göre tıklamalar
        $this->clicksByReferrer = $clicks->groupBy('referrer')->map(function ($group) {
            return [
                'referrer' => $group->first()->referrer,
                'total' => $group->count(),
            ];
        })->sortByDesc('total')->values();

        // Bot durumuna göre tıklamalar
        $this->clicksByBotStatus = $clicks->groupBy('is_bot')->map(function ($group) {
            return [
                'is_bot' => $group->first()->is_bot,
                'total' => $group->count(),
            ];
        })->sortByDesc('total')->values();

        // Son 1 dakikadaki tıklama sayısına göre dağılım
        $this->clicksByRecentClickCount = $clicks->groupBy('recent_click_count')->map(function ($group) {
            return [
                'recent_click_count' => $group->first()->recent_click_count,
                'total' => $group->count(),
            ];
        })->sortBy('recent_click_count')->values();
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
                return ['Ülke', 'Tıklama Sayısı'];
            case 'time_trends':
                return ['Tarih', 'Tıklama Sayısı'];
            case 'device_types':
                return ['Cihaz Türü', 'Tıklama Sayısı'];
            case 'operating_systems':
                return ['İşletim Sistemi', 'Tıklama Sayısı'];
            case 'browsers':
                return ['Tarayıcı', 'Tıklama Sayısı'];
            case 'links':
                return ['Orijinal Link', 'Kısaltılmış Link', 'Tekil Tıklama', 'Toplam Tıklama', 'Kazanç ($)'];
            case 'referrers':
                return ['Yönlendiren Domain', 'Tıklama Sayısı'];
            case 'bot_status':
                return ['Bot Durumu', 'Tıklama Sayısı'];
            case 'recent_click_count':
                return ['Son 1 Dakikadaki Tıklama Sayısı', 'Tıklama Sayısı'];
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
                    $data[] = [$item['device_type'] ?? 'Bilinmiyor', $item['total']];
                }
                break;
            case 'operating_systems':
                foreach ($this->clicksByOs as $item) {
                    $data[] = [$item['os'] ?? 'Bilinmiyor', $item['total']];
                }
                break;
            case 'browsers':
                foreach ($this->clicksByBrowser as $item) {
                    $data[] = [$item['browser'] ?? 'Bilinmiyor', $item['total']];
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
                    $data[] = [$item['referrer'] ?? 'Doğrudan / Bilinmiyor', $item['total']];
                }
                break;
            case 'bot_status':
                foreach ($this->clicksByBotStatus as $item) {
                    $data[] = [$item['is_bot'] ? 'Bot' : 'Organik', $item['total']];
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
