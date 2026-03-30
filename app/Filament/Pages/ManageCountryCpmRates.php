<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Country;
use App\Models\CpmRate;
use App\Models\CpmCampaign;
use App\Services\CpmCampaignService;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;use Illuminate\Support\Collection;
use Carbon\Carbon;

class ManageCountryCpmRates extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string $view = 'filament.pages.manage-country-cpm-rates';

    protected static ?string $navigationGroup = 'Reklam Yönetimi';

    protected static ?string $navigationLabel = 'Ülke CPM Oranları';

    public Collection $countries;
    public array $data = [];
    
    // Campaign properties
    public ?CpmCampaign $activeCampaign = null;
    public bool $showCampaignModal = false;
    public string $campaignName = '';
    public float $campaignMultiplier = 2.0;
    public ?string $campaignStartDate = null;
    public ?string $campaignEndDate = null;

    public function mount(): void
    {
        $this->countries = Country::orderBy('name')->get();
        
        // Initialize country rates data
        foreach ($this->countries as $country) {
            $cpmRate = CpmRate::where('country_id', $country->id)->first();
            $this->data['country_rates'][$country->id]['publisher_rate'] = $cpmRate->publisher_rate ?? 0.0000;
            $this->data['country_rates'][$country->id]['advertiser_rate'] = $cpmRate->advertiser_rate ?? 0.0000;
        }
        
        // Load active campaign if exists
        $this->activeCampaign = app(CpmCampaignService::class)->getActiveCampaign();
    }

    protected function getFormSchema(): array
    {
        return [];
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('applyMarketRates')
                ->label('Apply Market Rates')
                ->icon('heroicon-o-globe-alt')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Apply Pre-defined Market Rates')
                ->modalDescription('This will fill in competitive CPM rates for all countries based on market research (cuty.io, exe.io averages). You can still manually adjust individual rates before saving.')
                ->modalSubmitActionLabel('Apply Rates')
                ->action(function () {
                    $this->applyMarketRates();
                }),

            Action::make('startCampaign')
                ->label('Start 2X Campaign')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->visible(fn () => !$this->activeCampaign)
                ->form([
                    TextInput::make('campaignName')
                        ->label('Campaign Name')
                        ->required()
                        ->default('2X CPM Promotion')
                        ->maxLength(255),
                    TextInput::make('campaignMultiplier')
                        ->label('Multiplier')
                        ->required()
                        ->numeric()
                        ->default(2.0)
                        ->minValue(1.1)
                        ->maxValue(10)
                        ->step(0.1)
                        ->suffix('x'),
                    DateTimePicker::make('campaignStartDate')
                        ->label('Start Date')
                        ->required()
                        ->default(now())
                        ->minDate(now()),
                    DateTimePicker::make('campaignEndDate')
                        ->label('End Date')
                        ->required()
                        ->minDate(now()->addHour())
                        ->after('campaignStartDate'),
                ])
                ->action(function (array $data) {
                    $this->startCampaign($data);
                }),
                
            Action::make('stopCampaign')
                ->label('Stop Active Campaign')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $this->activeCampaign !== null)
                ->requiresConfirmation()
                ->modalHeading('Stop Campaign')
                ->modalDescription('Are you sure you want to stop the active campaign? All CPM rates will be reverted to their original values.')
                ->action(function () {
                    $this->stopCampaign();
                }),
        ];
    }

    public function startCampaign(array $data)
    {
        try {
            $service = app(CpmCampaignService::class);
            
            $campaign = $service->startCampaign(
                name: $data['campaignName'],
                multiplier: (float) $data['campaignMultiplier'],
                startDate: Carbon::parse($data['campaignStartDate']),
                endDate: Carbon::parse($data['campaignEndDate'])
            );

            Notification::make()
                ->title("Campaign '{$campaign->name}' started successfully!")
                ->body("All publisher CPM rates have been multiplied by {$campaign->multiplier}x")
                ->success()
                ->send();

            // Redirect to reload the page with updated values
            return redirect()->to(static::getUrl());

        } catch (\Exception $e) {
            Notification::make()
                ->title('Campaign Start Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function stopCampaign()
    {
        try {
            $service = app(CpmCampaignService::class);
            $service->stopCampaign();

            $campaignName = $this->activeCampaign->name;
            $this->activeCampaign = null;

            Notification::make()
                ->title("Campaign '{$campaignName}' stopped successfully!")
                ->body('All CPM rates have been reverted to original values.')
                ->success()
                ->send();

            // Redirect to reload the page with updated values
            return redirect()->to(static::getUrl());

        } catch (\Exception $e) {
            Notification::make()
                ->title('Campaign Stop Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function save(): void
    {
        if ($this->activeCampaign) {
            Notification::make()
                ->title('Cannot Save During Active Campaign')
                ->body('Please stop the active campaign before manually editing CPM rates.')
                ->warning()
                ->send();
            return;
        }

        $data = $this->data;

        foreach ($this->countries as $country) {
            $publisherRate = $data['country_rates'][$country->id]['publisher_rate'];
            $advertiserRate = $data['country_rates'][$country->id]['advertiser_rate'];

            CpmRate::updateOrCreate(
                ['country_id' => $country->id],
                [
                    'publisher_rate' => $publisherRate,
                    'advertiser_rate' => $advertiserRate,
                ]
            );
        }

        Notification::make()
            ->title('CPM rates updated successfully.')
            ->success()
            ->send();
    }

    public function applyMarketRates(): void
    {
        // Pre-defined competitive rates based on cuty.io / exe.io market analysis
        // publisher_rate = what we pay per 1000 views | advertiser_rate = what advertisers pay us
        $marketRates = [
            // Tier 1 — Premium English-speaking & Nordic
            'US' => ['publisher' => 10.00, 'advertiser' => 15.00],
            'CA' => ['publisher' => 8.50,  'advertiser' => 12.50],
            'GB' => ['publisher' => 9.00,  'advertiser' => 13.00],
            'AU' => ['publisher' => 8.00,  'advertiser' => 11.50],
            'NZ' => ['publisher' => 6.50,  'advertiser' => 9.50],
            'IE' => ['publisher' => 6.00,  'advertiser' => 8.50],
            'NO' => ['publisher' => 7.50,  'advertiser' => 11.00],
            'SE' => ['publisher' => 6.50,  'advertiser' => 9.50],
            'DK' => ['publisher' => 6.50,  'advertiser' => 9.50],
            'FI' => ['publisher' => 6.00,  'advertiser' => 8.50],
            'IS' => ['publisher' => 5.50,  'advertiser' => 8.00],
            'CH' => ['publisher' => 7.00,  'advertiser' => 10.50],
            'DE' => ['publisher' => 7.50,  'advertiser' => 11.00],
            'NL' => ['publisher' => 6.50,  'advertiser' => 9.50],
            'BE' => ['publisher' => 5.50,  'advertiser' => 8.00],
            'AT' => ['publisher' => 5.50,  'advertiser' => 8.00],
            'LU' => ['publisher' => 6.00,  'advertiser' => 8.50],
            'LI' => ['publisher' => 6.00,  'advertiser' => 8.50],
            // Tier 2 — Western Europe, Gulf, Developed Asia
            'FR' => ['publisher' => 5.50,  'advertiser' => 8.00],
            'ES' => ['publisher' => 4.50,  'advertiser' => 6.50],
            'IT' => ['publisher' => 4.50,  'advertiser' => 6.50],
            'PT' => ['publisher' => 3.80,  'advertiser' => 5.50],
            'GR' => ['publisher' => 3.50,  'advertiser' => 5.00],
            'MT' => ['publisher' => 3.50,  'advertiser' => 5.00],
            'CY' => ['publisher' => 3.50,  'advertiser' => 5.00],
            'MC' => ['publisher' => 5.00,  'advertiser' => 7.50],
            'SM' => ['publisher' => 4.00,  'advertiser' => 6.00],
            'VA' => ['publisher' => 4.00,  'advertiser' => 6.00],
            'AD' => ['publisher' => 3.80,  'advertiser' => 5.50],
            'AE' => ['publisher' => 5.50,  'advertiser' => 8.00],
            'SA' => ['publisher' => 5.00,  'advertiser' => 7.50],
            'QA' => ['publisher' => 4.50,  'advertiser' => 6.50],
            'KW' => ['publisher' => 4.50,  'advertiser' => 6.50],
            'BH' => ['publisher' => 3.80,  'advertiser' => 5.50],
            'OM' => ['publisher' => 3.50,  'advertiser' => 5.00],
            'IL' => ['publisher' => 4.50,  'advertiser' => 6.50],
            'JP' => ['publisher' => 4.50,  'advertiser' => 6.50],
            'KR' => ['publisher' => 4.00,  'advertiser' => 6.00],
            'SG' => ['publisher' => 5.50,  'advertiser' => 8.00],
            'HK' => ['publisher' => 4.50,  'advertiser' => 6.50],
            'TW' => ['publisher' => 3.50,  'advertiser' => 5.00],
            'PL' => ['publisher' => 3.80,  'advertiser' => 5.50],
            'CZ' => ['publisher' => 3.50,  'advertiser' => 5.00],
            'HU' => ['publisher' => 3.20,  'advertiser' => 4.50],
            'RO' => ['publisher' => 3.00,  'advertiser' => 4.50],
            'BG' => ['publisher' => 3.00,  'advertiser' => 4.50],
            'HR' => ['publisher' => 3.20,  'advertiser' => 4.50],
            'SI' => ['publisher' => 3.50,  'advertiser' => 5.00],
            'SK' => ['publisher' => 3.20,  'advertiser' => 4.50],
            'EE' => ['publisher' => 3.50,  'advertiser' => 5.00],
            'LV' => ['publisher' => 3.20,  'advertiser' => 4.50],
            'LT' => ['publisher' => 3.20,  'advertiser' => 4.50],
            // Tier 2/3 — Latin America
            'MX' => ['publisher' => 3.50,  'advertiser' => 5.00],
            'BR' => ['publisher' => 3.50,  'advertiser' => 5.00],
            'AR' => ['publisher' => 2.50,  'advertiser' => 3.50],
            'CL' => ['publisher' => 2.80,  'advertiser' => 4.00],
            'CO' => ['publisher' => 2.50,  'advertiser' => 3.50],
            'PE' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'VE' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'EC' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'BO' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'PY' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'UY' => ['publisher' => 2.50,  'advertiser' => 3.50],
            'CR' => ['publisher' => 2.50,  'advertiser' => 3.50],
            'PA' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'GT' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'SV' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'HN' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'NI' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'CU' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'DO' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'JM' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'TT' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'BS' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'BB' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'GY' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'SR' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'HT' => ['publisher' => 1.50,  'advertiser' => 2.50],
            'PR' => ['publisher' => 3.50,  'advertiser' => 5.00],
            // Southeast & South Asia
            'TH' => ['publisher' => 3.20,  'advertiser' => 4.80],
            'MY' => ['publisher' => 3.00,  'advertiser' => 4.50],
            'ID' => ['publisher' => 2.80,  'advertiser' => 4.00],
            'PH' => ['publisher' => 2.80,  'advertiser' => 4.00],
            'VN' => ['publisher' => 2.50,  'advertiser' => 3.80],
            'BN' => ['publisher' => 2.50,  'advertiser' => 3.80],
            'MM' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'KH' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'LA' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'TL' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'IN' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'PK' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'BD' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'LK' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'NP' => ['publisher' => 1.70,  'advertiser' => 2.60],
            'MV' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'BT' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'CN' => ['publisher' => 2.80,  'advertiser' => 4.00],
            'MN' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'MO' => ['publisher' => 3.00,  'advertiser' => 4.50],
            // Eastern Europe & Central Asia
            'RU' => ['publisher' => 2.50,  'advertiser' => 3.80],
            'UA' => ['publisher' => 2.50,  'advertiser' => 3.80],
            'BY' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'RS' => ['publisher' => 2.50,  'advertiser' => 3.80],
            'BA' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'ME' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'MK' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'AL' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'GE' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'AM' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'AZ' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'MD' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'KZ' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'UZ' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'KG' => ['publisher' => 1.70,  'advertiser' => 2.60],
            'TJ' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'TM' => ['publisher' => 1.70,  'advertiser' => 2.60],
            // Middle East & North Africa
            'TR' => ['publisher' => 3.00,  'advertiser' => 4.50],
            'EG' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'DZ' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'MA' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'TN' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'LY' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'SD' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'SS' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'IQ' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'SY' => ['publisher' => 1.70,  'advertiser' => 2.60],
            'JO' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'LB' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'PS' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'YE' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'IR' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'AF' => ['publisher' => 1.50,  'advertiser' => 2.20],
            // Sub-Saharan Africa
            'ZA' => ['publisher' => 2.80,  'advertiser' => 4.00],
            'NG' => ['publisher' => 2.20,  'advertiser' => 3.20],
            'KE' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'GH' => ['publisher' => 2.00,  'advertiser' => 3.00],
            'ET' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'TZ' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'UG' => ['publisher' => 1.70,  'advertiser' => 2.60],
            'AO' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'CM' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'CI' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'SN' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'ZM' => ['publisher' => 1.70,  'advertiser' => 2.60],
            'ZW' => ['publisher' => 1.70,  'advertiser' => 2.60],
            'BW' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'NA' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'MW' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'MZ' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'MG' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'RW' => ['publisher' => 1.70,  'advertiser' => 2.60],
            'BI' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'SO' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'ML' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'BF' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'NE' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'TD' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'MR' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'SL' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'LR' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'GN' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'GW' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'GM' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'TG' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'BJ' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'CD' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'CG' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'GA' => ['publisher' => 1.70,  'advertiser' => 2.60],
            'GQ' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'LS' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'SZ' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'MU' => ['publisher' => 1.80,  'advertiser' => 2.80],
            'SC' => ['publisher' => 1.70,  'advertiser' => 2.60],
            'KM' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'DJ' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'ER' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'RE' => ['publisher' => 1.70,  'advertiser' => 2.60],
            'CV' => ['publisher' => 1.60,  'advertiser' => 2.50],
            'ST' => ['publisher' => 1.50,  'advertiser' => 2.20],
            'CF' => ['publisher' => 1.50,  'advertiser' => 2.20],
        ];

        $applied = 0;
        foreach ($this->countries as $country) {
            $iso = $country->iso_code;
            if (isset($marketRates[$iso])) {
                $this->data['country_rates'][$country->id]['publisher_rate']  = $marketRates[$iso]['publisher'];
                $this->data['country_rates'][$country->id]['advertiser_rate'] = $marketRates[$iso]['advertiser'];
                $applied++;
            }
        }

        Notification::make()
            ->title("Market rates applied for {$applied} countries")
            ->body('Review the rates below and click Save to persist them to the database.')
            ->info()
            ->send();
    }
}
