<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkClick;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use App\Models\User;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Country;
use App\Models\Link;
 
class ReportsManager extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public ?array $data = [];

    public $startDate;
    public $endDate;
    public $selectedPreset = 'last_7_days';
    public $userId;
    public $userEmail;
    public $searchUserQuery;
    public $foundUsers = [];

    protected $queryString = ['startDate', 'endDate', 'selectedPreset', 'userId', 'userEmail'];

    public function mount(): void
    {
        $this->form->fill();
        $this->applyPreset();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('searchUserQuery')
                    ->label('Kullanıcı Ara')
                    ->placeholder('Kullanıcı adı veya e-posta ile ara')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn () => $this->searchUsers()),
                Select::make('userId')
                    ->label('Kullanıcı Seç')
                    ->options(function () {
                        if ($this->searchUserQuery) {
                            return $this->foundUsers->pluck('name', 'id')->toArray();
                        }
                        if ($this->userId) {
                            $user = User::find($this->userId);
                            return $user ? [$user->id => $user->name . ' (' . $user->email . ')'] : [];
                        }
                        return [];
                    })
                    ->searchable()
                    ->nullable()
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        if ($state) {
                            $user = User::find($state);
                            $this->userEmail = $user->email;
                        } else {
                            $this->userEmail = null;
                        }
                        $this->searchUserQuery = null;
                        $this->foundUsers = [];
                    }),
                DatePicker::make('startDate')
                    ->label('Başlangıç Tarihi')
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->selectedPreset = null),
                DatePicker::make('endDate')
                    ->label('Bitiş Tarihi')
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->selectedPreset = null),
                Select::make('selectedPreset')
                    ->label('Hızlı Seçim')
                    ->options([
                        'last_7_days' => 'Son 7 Gün',
                        'last_30_days' => 'Son 30 Gün',
                        'last_90_days' => 'Son 3 Ay',
                        'last_365_days' => 'Son 1 Yıl',
                        'all_time' => 'Tüm Zamanlar',
                    ])
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->applyPreset()),
            ])
            ->statePath('data');
    }
 
    public function render(): \Illuminate\Contracts\View\View
    {
        $this->startDate = $this->data['startDate'] ?? null;
        $this->endDate = $this->data['endDate'] ?? null;
        $this->selectedPreset = $this->data['selectedPreset'] ?? 'last_7_days';
        $this->userId = $this->data['userId'] ?? null;
        $this->userEmail = $this->data['userEmail'] ?? null;

        $clicksByCountryChartData = $this->getClicksByCountryChartData();
        $clicksByLink = $this->getClicksByLink();
        $clicksByReferrer = $this->getClicksByReferrer();
        $clicksByDeviceType = $this->getClicksByDeviceType();
        $clicksByOs = $this->getClicksByOs();
        $clicksByBrowser = $this->getClicksByBrowser();
        $clicksOverTime = $this->getClicksOverTime();
        $uniqueClicksByLink = $this->getUniqueClicksByLink();
        $clicksByBotStatus = $this->getClicksByBotStatus();
        $clicksByRecentClickCount = $this->getClicksByRecentClickCount();
 
        return view('livewire.admin.reports-manager', [
            'clicksByCountryChartData' => $clicksByCountryChartData,
            'clicksByLink' => $clicksByLink,
            'clicksByReferrer' => $clicksByReferrer,
            'clicksByDeviceType' => $clicksByDeviceType,
            'clicksByOs' => $clicksByOs,
            'clicksByBrowser' => $clicksByBrowser,
            'clicksOverTime' => $clicksOverTime,
            'uniqueClicksByLink' => $uniqueClicksByLink,
            'clicksByBotStatus' => $clicksByBotStatus,
            'clicksByRecentClickCount' => $clicksByRecentClickCount,
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LinkClick::query())
            ->columns([
                TextColumn::make('link.user.name')
                    ->label('User Name')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('link.user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                TextColumn::make('link.user.email')
                    ->label('User Email')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('link.user', function ($query) use ($search) {
                            $query->where('email', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('country.name')
                    ->label('Country')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cpm_rate')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('device_type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('os')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('browser')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('referrer')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_bot')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->relationship('link.user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by User'),
                SelectFilter::make('country')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Country'),
                SelectFilter::make('device_type')
                    ->options(LinkClick::distinct()->pluck('device_type', 'device_type')->toArray())
                    ->label('Filter by Device Type'),
                SelectFilter::make('os')
                    ->options(LinkClick::distinct()->pluck('os', 'os')->toArray())
                    ->label('Filter by OS'),
                SelectFilter::make('browser')
                    ->options(LinkClick::distinct()->pluck('browser', 'browser')->toArray())
                    ->label('Filter by Browser'),
                SelectFilter::make('is_bot')
                    ->options([
                        true => 'Bot',
                        false => 'Organik',
                    ])
                    ->label('Filter by Bot Status'),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
 
    public function updatedDataSelectedPreset(): void
    {
        $this->applyPreset();
    }

    public function updatedDataStartDate(): void
    {
        $this->selectedPreset = null;
    }

    public function updatedDataEndDate(): void
    {
        $this->selectedPreset = null;
    }
 
    private function applyPreset()
    {
        $now = now();
        switch ($this->selectedPreset) {
            case 'last_7_days':
                $this->startDate = $now->copy()->subDays(6)->startOfDay()->toDateString();
                $this->endDate = $now->endOfDay()->toDateString();
                break;
            case 'last_30_days':
                $this->startDate = $now->copy()->subDays(29)->startOfDay()->toDateString();
                $this->endDate = $now->endOfDay()->toDateString();
                break;
            case 'last_90_days':
                $this->startDate = $now->copy()->subDays(89)->startOfDay()->toDateString();
                $this->endDate = $now->endOfDay()->toDateString();
                break;
            case 'last_365_days':
                $this->startDate = $now->copy()->subDays(364)->startOfDay()->toDateString();
                $this->endDate = $now->endOfDay()->toDateString();
                break;
            case 'all_time':
                $this->startDate = null;
                $this->endDate = null;
                break;
            default:
                // Handle custom date range if needed, or default to last 7 days
                if (!$this->startDate || !$this->endDate) {
                     $this->selectedPreset = 'last_7_days';
                     $this->applyPreset();
                }
                break;
        }
    }
 
    private function getBaseQuery(): Builder
    {
        $query = LinkClick::query();
 
        if ($this->data['userId'] ?? null) {
            $query->whereHas('link.user', function (Builder $q) {
                $q->where('id', $this->data['userId']);
            });
        } elseif ($this->data['userEmail'] ?? null) {
            $query->whereHas('link.user', function (Builder $q) {
                $q->where('email', $this->data['userEmail']);
            });
        }
 
        if (($this->data['startDate'] ?? null) && ($this->data['endDate'] ?? null)) {
            $query->whereBetween('link_clicks.created_at', [$this->data['startDate'] . ' 00:00:00', $this->data['endDate'] . ' 23:59:59']);
        }
 
        return $query;
    }
 
    public function searchUsers(): void
    {
        if (empty($this->data['searchUserQuery'])) {
            $this->foundUsers = [];
            return;
        }
 
        $this->foundUsers = User::where('name', 'like', '%' . $this->data['searchUserQuery'] . '%')
                                            ->orWhere('email', 'like', '%' . $this->data['searchUserQuery'] . '%')
                                            ->limit(10)
                                            ->get();
    }
 
    public function selectUser(int $userId, string $userEmail): void
    {
        $this->data['userId'] = $userId;
        $this->data['userEmail'] = $userEmail;
        $this->data['searchUserQuery'] = null;
        $this->foundUsers = [];
        $this->form->fill($this->data);
    }
 
    public function clearUserFilter(): void
    {
        $this->data['userId'] = null;
        $this->data['userEmail'] = null;
        $this->data['searchUserQuery'] = null;
        $this->foundUsers = [];
        $this->form->fill($this->data);
    }
 
    private function getClicksByReferrer(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('referrer, count(*) as total')
                    ->groupBy('referrer')
                    ->orderByDesc('total')
                    ->get();
    }
 
    private function getClicksByLink(): Collection
    {
        $clicksByLinkData = $this->getBaseQuery()
            ->join('links', 'link_clicks.link_id', '=', 'links.id')
            ->selectRaw('link_clicks.link_id, links.original_url, links.code, count(*) as total_clicks, sum(0.001) as earnings')
            ->groupBy('link_clicks.link_id', 'links.original_url', 'links.code')
            ->get();
 
        return $clicksByLinkData->map(function ($linkStats) {
            return [
                'link_id' => $linkStats->link_id,
                'original_url' => $linkStats->original_url,
                'short_link' => url($linkStats->code),
                'total_clicks' => $linkStats->total_clicks,
                'earnings' => number_format($linkStats->earnings, 2),
            ];
        });
    }
 
    private function getClicksByCountryChartData(): array
    {
        $clicksByCountry = $this->getBaseQuery()
                                    ->with('country')
                                    ->selectRaw('country_id, count(*) as total')
                                    ->groupBy('country_id')
                                    ->has('country')
                                    ->orderByDesc('total')
                                    ->get();
 
        $labels = $clicksByCountry->pluck('country')->map(fn($country) => $country->name ?? 'Bilinmiyor')->toArray();
        $data = $clicksByCountry->pluck('total')->toArray();
 
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
 
    private function getClicksByDeviceType(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('device_type, count(*) as total')
                    ->groupBy('device_type')
                    ->orderByDesc('total')
                    ->get();
    }
 
    private function getClicksByOs(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('os, count(*) as total')
                    ->groupBy('os')
                    ->orderByDesc('total')
                    ->get();
    }
 
    private function getClicksByBrowser(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('browser, count(*) as total')
                    ->groupBy('browser')
                    ->orderByDesc('total')
                    ->get();
    }
 
    private function getClicksOverTime(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('DATE(created_at) as click_date, count(*) as total')
                    ->groupBy('click_date')
                    ->orderBy('click_date')
                    ->get();
    }
 
    private function getUniqueClicksByLink(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('link_id, COUNT(DISTINCT ip_address) as unique_clicks')
                    ->groupBy('link_id')
                    ->get()
                    ->pluck('unique_clicks', 'link_id');
    }
 
    private function getClicksByBotStatus(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('is_bot, count(*) as total')
                    ->groupBy('is_bot')
                    ->get();
    }
 
    private function getClicksByRecentClickCount(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('recent_click_count, count(*) as total')
                    ->groupBy('recent_click_count')
                    ->orderBy('recent_click_count')
                    ->get();
    }
 
     // Export methods
     public function exportCsv($reportType)
     {
        $data = collect();
        $headings = [];
        $fileName = 'report.csv';
 
        switch ($reportType) {
            case 'countries':
                $data = $this->getClicksByCountryChartData()['data'];
                $labels = $this->getClicksByCountryChartData()['labels'];
                $exportData = new Collection();
                foreach ($labels as $index => $label) {
                    $exportData->push(['Ülke' => $label, 'Tıklama Sayısı' => $data[$index]]);
                }
                $data = $exportData;
                $headings = ['Ülke', 'Tıklama Sayısı'];
                $fileName = 'ulkeler_raporu.csv';
                break;
            case 'countries_table':
                 $data = $this->getClicksByCountryChartData()['data'];
                $labels = $this->getClicksByCountryChartData()['labels'];
                $exportData = new Collection();
                foreach ($labels as $index => $label) {
                    $exportData->push(['Ülke' => $label, 'Tıklama Sayısı' => $data[$index]]);
                }
                $data = $exportData;
                $headings = ['Ülke', 'Tıklama Sayısı'];
                $fileName = 'ulkeler_tablo_raporu.csv';
                break;
            case 'links':
                $data = $this->getClicksByLink();
                 $uniqueClicks = $this->getUniqueClicksByLink();
                 $exportData = $data->map(function ($item) use ($uniqueClicks) {
                     $item['Tekil Tıklama'] = $uniqueClicks->get($item['link_id'], 0);
                     unset($item['link_id']);
                     return $item;
                 });
                 $data = $exportData;
                $headings = ['Orijinal Link', 'Kısaltılmış Link', 'Tekil Tıklama', 'Toplam Tıklama', 'Kazanç ($)'];
                $fileName = 'linkler_raporu.csv';
                break;
            case 'referrers':
                $data = $this->getClicksByReferrer();
                $headings = ['Yönlendiren Domain', 'Tıklama Sayısı'];
                $fileName = 'yonlendirenler_raporu.csv';
                break;
            case 'device_types':
                $data = $this->getClicksByDeviceType();
                $headings = ['Cihaz Türü', 'Tıklama Sayısı'];
                $fileName = 'cihaz_turleri_raporu.csv';
                break;
            case 'operating_systems':
                $data = $this->getClicksByOs();
                $headings = ['İşletim Sistemi', 'Tıklama Sayısı'];
                $fileName = 'isletim_sistemleri_raporu.csv';
                break;
            case 'browsers':
                $data = $this->getClicksByBrowser();
                $headings = ['Tarayıcı', 'Tıklama Sayısı'];
                $fileName = 'tarayicilar_raporu.csv';
                break;
            case 'time_trends':
                 $data = $this->getClicksOverTime();
                 $headings = ['Tarih', 'Tıklama Sayısı'];
                 $fileName = 'zaman_trendleri_raporu.csv';
                 break;
            default:
                return;
        }
 
        if (!($data instanceof Collection)) {
             $data = collect($data);
        }
 
        return Excel::download(new class($data, $headings) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;
            private $headings;
 
            public function __construct($data, $headings)
            {
                $this->data = $data;
                $this->headings = $headings;
            }
 
            public function collection()
            {
                return $this->data;
            }
 
            public function headings(): array
            {
                return $this->headings;
            }
        }, $fileName);
    }
 
    public function exportPdf($reportType)
    {
        $data = collect();
        $view = '';
        $fileName = 'report.pdf';
 
        switch ($reportType) {
            case 'countries':
                $data = $this->getClicksByCountryChartData();
                $view = 'reports.pdf.countries';
                $fileName = 'ulkeler_raporu.pdf';
                break;
            case 'countries_table':
                 $data = $this->getClicksByCountryChartData();
                 $view = 'reports.pdf.countries_table';
                 $fileName = 'ulkeler_tablo_raporu.pdf';
                 break;
            case 'links':
                $data = $this->getClicksByLink();
                 $uniqueClicks = $this->getUniqueClicksByLink();
                 $data = $data->map(function ($item) use ($uniqueClicks) {
                     $item['unique_clicks'] = $uniqueClicks->get($item['link_id'], 0);
                     unset($item['link_id']);
                     return $item;
                 });
                $view = 'reports.pdf.links';
                $fileName = 'linkler_raporu.pdf';
                break;
            case 'referrers':
                $data = $this->getClicksByReferrer();
                $view = 'reports.pdf.referrers';
                $fileName = 'yonlendirenler_raporu.pdf';
                break;
            case 'device_types':
                $data = $this->getClicksByDeviceType();
                $view = 'reports.pdf.device_types';
                $fileName = 'cihaz_turleri_raporu.pdf';
                break;
            case 'operating_systems':
                $data = $this->getClicksByOs();
                $view = 'reports.pdf.operating_systems';
                $fileName = 'isletim_sistemleri_raporu.pdf';
                break;
            case 'browsers':
                $data = $this->getClicksByBrowser();
                $view = 'reports.pdf.browsers';
                $fileName = 'tarayicilar_raporu.pdf';
                break;
            case 'time_trends':
                 $data = $this->getClicksOverTime();
                 $view = 'reports.pdf.time_trends';
                 $fileName = 'zaman_trendleri_raporu.pdf';
                 break;
            default:
                return;
        }
 
        if (empty($view) || $data->isEmpty()) {
            return;
        }
 
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, ['data' => $data]);
 
        return $pdf->download($fileName);
    }
}