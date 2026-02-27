<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkClick;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
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
use Illuminate\Pagination\LengthAwarePaginator;
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
    
    // Pagination flag for Livewire's built-in trait

    protected $queryString = ['startDate', 'endDate', 'selectedPreset', 'userId', 'userEmail'];

    public function mount(): void
    {
        // Initialize data array with defaults
        $this->data = [
            'userId' => null,
            'startDate' => null,
            'endDate' => null,
            'selectedPreset' => 'last_7_days',
        ];
        
        $this->selectedPreset = 'last_7_days';
        $this->applyPreset();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('userId')
                    ->label('Kullanıcı Seç')
                    ->options(fn () => User::limit(100)->pluck('name', 'id'))
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => 
                        User::where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->name)
                    ->nullable()
                    ->live(),
                DatePicker::make('startDate')
                    ->label('Başlangıç Tarihi')
                    ->live()
                    ->afterStateUpdated(fn () => $this->data['selectedPreset'] = null),
                DatePicker::make('endDate')
                    ->label('Bitiş Tarihi')
                    ->live()
                    ->afterStateUpdated(fn () => $this->data['selectedPreset'] = null),
                Select::make('selectedPreset')
                    ->label('Hızlı Seçim')
                    ->options([
                        'last_7_days' => 'Son 7 Gün',
                        'last_30_days' => 'Son 30 Gün',
                        'last_90_days' => 'Son 3 Ay',
                        'last_365_days' => 'Son 1 Yıl',
                        'all_time' => 'Tüm Zamanlar',
                    ])
                    ->live()
                    ->afterStateUpdated(fn () => $this->applyPreset()),
            ])
            ->columns(4)
            ->statePath('data');
    }
 
    public function render(): \Illuminate\Contracts\View\View
    {
        $this->startDate = $this->data['startDate'] ?? null;
        $this->endDate = $this->data['endDate'] ?? null;
        $this->selectedPreset = $this->data['selectedPreset'] ?? 'last_7_days';
        $this->userId = $this->data['userId'] ?? null;
        $this->userEmail = $this->data['userEmail'] ?? null;

        return view('livewire.admin.reports-manager');
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
        $this->dispatch('heatmap-data-updated', data: $this->clicksByCountryChartData);
        $this->dispatch('timechart-data-updated', data: $this->clicksOverTime);
    }

    public function updatedDataEndDate(): void
    {
        $this->selectedPreset = null;
        $this->dispatch('heatmap-data-updated', data: $this->clicksByCountryChartData);
        $this->dispatch('timechart-data-updated', data: $this->clicksOverTime);
    }
    

 
    private function applyPreset()
    {
        $now = now();
        $preset = $this->data['selectedPreset'] ?? $this->selectedPreset ?? 'last_7_days';
        
        switch ($preset) {
            case 'last_7_days':
                $startDate = $now->copy()->subDays(6)->startOfDay()->toDateString();
                $endDate = $now->endOfDay()->toDateString();
                break;
            case 'last_30_days':
                $startDate = $now->copy()->subDays(29)->startOfDay()->toDateString();
                $endDate = $now->endOfDay()->toDateString();
                break;
            case 'last_90_days':
                $startDate = $now->copy()->subDays(89)->startOfDay()->toDateString();
                $endDate = $now->endOfDay()->toDateString();
                break;
            case 'last_365_days':
                $startDate = $now->copy()->subDays(364)->startOfDay()->toDateString();
                $endDate = $now->endOfDay()->toDateString();
                break;
            case 'all_time':
                $startDate = null;
                $endDate = null;
                break;
            default:
                $startDate = $now->copy()->subDays(6)->startOfDay()->toDateString();
                $endDate = $now->endOfDay()->toDateString();
                $preset = 'last_7_days';
                break;
        }
        
        // Update both component properties AND form data
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->selectedPreset = $preset;
        
        $this->data['startDate'] = $startDate;
        $this->data['endDate'] = $endDate;
        $this->data['selectedPreset'] = $preset;
        
        // Refill form with updated data
        $this->form->fill($this->data);
        
        // Form properties mutated, trigger events for frontend
        $this->dispatch('heatmap-data-updated', data: $this->clicksByCountryChartData);
        $this->dispatch('timechart-data-updated', data: $this->clicksOverTime);
        $this->resetPage('linksPage'); // Reset table page to 1 on filter
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
        
        $this->dispatch('heatmap-data-updated', data: $this->clicksByCountryChartData);
        $this->dispatch('timechart-data-updated', data: $this->clicksOverTime);
        $this->resetPage('linksPage');
    }
 
    public function clearUserFilter(): void
    {
        $this->data['userId'] = null;
        $this->data['userEmail'] = null;
        $this->data['searchUserQuery'] = null;
        $this->foundUsers = [];
        $this->form->fill($this->data);
        
        $this->dispatch('heatmap-data-updated', data: $this->clicksByCountryChartData);
        $this->dispatch('timechart-data-updated', data: $this->clicksOverTime);
        $this->resetPage('linksPage');
    }
 
    #[Computed]
    public function clicksByReferrer(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('referrer, count(*) as total')
                    ->groupBy('referrer')
                    ->orderByDesc('total')
                    ->get();
    }
 
    #[Computed]
    public function clicksByLink(): LengthAwarePaginator
    {
        $paginator = $this->getBaseQuery()
            ->join('links', 'link_clicks.link_id', '=', 'links.id')
            ->selectRaw('link_clicks.link_id, links.original_url, links.code, count(*) as total_clicks, sum(0.001) as earnings')
            ->groupBy('link_clicks.link_id', 'links.original_url', 'links.code')
            ->orderByDesc('total_clicks')
            ->paginate(25, ['*'], 'linksPage');

        // Map through items to format them nicely
        $paginator->getCollection()->transform(function ($linkStats) {
            return [
                'link_id' => $linkStats->link_id,
                'original_url' => $linkStats->original_url,
                'short_link' => url($linkStats->code),
                'total_clicks' => $linkStats->total_clicks,
                'earnings' => number_format($linkStats->earnings, 4), // 4 decimals precision for micro-cpm
            ];
        });

        return $paginator;
    }
 
    #[Computed]
    public function clicksByCountryChartData(): array
    {
        $clicksByCountry = $this->getBaseQuery()
                                    ->with('country:id,name,iso_code') // Limit columns mapped from Country relation
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
 
    #[Computed]
    public function clicksByDeviceType(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('device_type, count(*) as total')
                    ->groupBy('device_type')
                    ->orderByDesc('total')
                    ->get();
    }
 
    #[Computed]
    public function clicksByOs(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('os, count(*) as total')
                    ->groupBy('os')
                    ->orderByDesc('total')
                    ->get();
    }
 
    #[Computed]
    public function clicksByBrowser(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('browser, count(*) as total')
                    ->groupBy('browser')
                    ->orderByDesc('total')
                    ->get();
    }
 
    #[Computed]
    public function clicksOverTime(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('DATE(created_at) as click_date, count(*) as total')
                    ->groupBy('click_date')
                    ->orderBy('click_date')
                    ->get();
    }
 
    #[Computed]
    public function uniqueClicksByLink(): Collection
    {
        // For performance, this handles only unique distinct ip fetching
        return $this->getBaseQuery()
                    ->selectRaw('link_id, COUNT(DISTINCT ip_address) as unique_clicks')
                    ->groupBy('link_id')
                    ->get()
                    ->pluck('unique_clicks', 'link_id');
    }
 
    #[Computed]
    public function clicksByBotStatus(): Collection
    {
        return $this->getBaseQuery()
                    ->selectRaw('is_bot, count(*) as total')
                    ->groupBy('is_bot')
                    ->get();
    }
 
    #[Computed]
    public function clicksByRecentClickCount(): Collection
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
                $data = $this->clicksByCountryChartData;
                $labels = $this->clicksByCountryChartData['labels'];
                $exportData = new Collection();
                foreach ($labels as $index => $label) {
                    $exportData->push(['Ülke' => $label, 'Tıklama Sayısı' => $data['data'][$index]]);
                }
                $data = $exportData;
                $headings = ['Ülke', 'Tıklama Sayısı'];
                $fileName = 'ulkeler_raporu.csv';
                break;
            case 'countries_table':
                 $data = $this->clicksByCountryChartData;
                $labels = $this->clicksByCountryChartData['labels'];
                $exportData = new Collection();
                foreach ($labels as $index => $label) {
                    $exportData->push(['Ülke' => $label, 'Tıklama Sayısı' => $data['data'][$index]]);
                }
                $data = $exportData;
                $headings = ['Ülke', 'Tıklama Sayısı'];
                $fileName = 'ulkeler_tablo_raporu.csv';
                break;
            case 'links':
                // For export we might want to bypass pagination but let's just use the computed for simplicity and limit safety
                // Or better yet, write a raw query export for ALL. For now matching existing functionality:
                $data = $this->clicksByLink->getCollection();
                 $uniqueClicks = $this->uniqueClicksByLink;
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
                $data = $this->clicksByReferrer;
                $headings = ['Yönlendiren Domain', 'Tıklama Sayısı'];
                $fileName = 'yonlendirenler_raporu.csv';
                break;
            case 'device_types':
                $data = $this->clicksByDeviceType;
                $headings = ['Cihaz Türü', 'Tıklama Sayısı'];
                $fileName = 'cihaz_turleri_raporu.csv';
                break;
            case 'operating_systems':
                $data = $this->clicksByOs;
                $headings = ['İşletim Sistemi', 'Tıklama Sayısı'];
                $fileName = 'isletim_sistemleri_raporu.csv';
                break;
            case 'browsers':
                $data = $this->clicksByBrowser;
                $headings = ['Tarayıcı', 'Tıklama Sayısı'];
                $fileName = 'tarayicilar_raporu.csv';
                break;
            case 'time_trends':
                 $data = $this->clicksOverTime;
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
                $data = collect($this->clicksByCountryChartData);
                $view = 'reports.pdf.countries';
                $fileName = 'ulkeler_raporu.pdf';
                break;
            case 'countries_table':
                 $data = collect($this->clicksByCountryChartData);
                 $view = 'reports.pdf.countries_table';
                 $fileName = 'ulkeler_tablo_raporu.pdf';
                 break;
            case 'links':
                $data = collect($this->clicksByLink->items());
                 $uniqueClicks = $this->uniqueClicksByLink;
                 $data = $data->map(function ($item) use ($uniqueClicks) {
                     $item['unique_clicks'] = $uniqueClicks->get($item['link_id'], 0);
                     unset($item['link_id']);
                     return $item;
                 });
                $view = 'reports.pdf.links';
                $fileName = 'linkler_raporu.pdf';
                break;
            case 'referrers':
                $data = $this->clicksByReferrer;
                $view = 'reports.pdf.referrers';
                $fileName = 'yonlendirenler_raporu.pdf';
                break;
            case 'device_types':
                $data = $this->clicksByDeviceType;
                $view = 'reports.pdf.device_types';
                $fileName = 'cihaz_turleri_raporu.pdf';
                break;
            case 'operating_systems':
                $data = $this->clicksByOs;
                $view = 'reports.pdf.operating_systems';
                $fileName = 'isletim_sistemleri_raporu.pdf';
                break;
            case 'browsers':
                $data = $this->clicksByBrowser;
                $view = 'reports.pdf.browsers';
                $fileName = 'tarayicilar_raporu.pdf';
                break;
            case 'time_trends':
                 $data = $this->clicksOverTime;
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