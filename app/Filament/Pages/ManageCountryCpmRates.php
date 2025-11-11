<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Country;
use App\Models\CpmRate;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section; // Add Section component
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class ManageCountryCpmRates extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string $view = 'filament.pages.manage-country-cpm-rates';

    protected static ?string $navigationGroup = 'Reklam Yönetimi';

    protected static ?string $navigationLabel = 'Ülke CPM Oranları';

    public Collection $countries;
    public array $data = []; // ADDED: Public property to hold form data

    public function mount(): void
    {
        $this->countries = Country::orderBy('name')->get();
        // Initialize $this->data directly
        foreach ($this->countries as $country) {
            $cpmRate = CpmRate::where('country_id', $country->id)->first();
            $this->data['country_rates'][$country->id]['publisher_rate'] = $cpmRate->rate ?? 0.0000;
            $this->data['country_rates'][$country->id]['advertiser_rate'] = $cpmRate->advertiser_rate ?? 0.0000;
        }

        // REMOVED: $this->form->fill($data);
    }

    protected function getFormSchema(): array
    {
        return [];
    }

    public function save(): void
    {
        // MODIFIED: Use $this->data directly
        $data = $this->data;

        foreach ($this->countries as $country) {
            $publisherRate = $data['country_rates'][$country->id]['publisher_rate'];
            $advertiserRate = $data['country_rates'][$country->id]['advertiser_rate'];

            CpmRate::updateOrCreate(
                ['country_id' => $country->id],
                [
                    'rate' => $publisherRate,
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
