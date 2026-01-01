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
            ->title('CPM Oranları başarıyla güncellendi.')
            ->success()
            ->send();
    }
}
