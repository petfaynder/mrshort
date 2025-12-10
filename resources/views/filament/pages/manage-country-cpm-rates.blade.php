<x-filament-panels::page>
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($this->countries as $country)
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm bg-white dark:bg-gray-800 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="fi fi-{{ strtolower($country->iso_code) }} text-xl"></span>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $country->name }}</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="country_rates.{{ $country->id }}.publisher_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Yayıncı Oranı</label>
                            <input type="number" 
                                   step="0.0001" 
                                   wire:model.defer="data.country_rates.{{ $country->id }}.publisher_rate" 
                                   id="country_rates.{{ $country->id }}.publisher_rate" 
                                   class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error("country_rates.{$country->id}.publisher_rate") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="country_rates.{{ $country->id }}.advertiser_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reklamcı Oranı</label>
                            <input type="number" 
                                   step="0.0001" 
                                   wire:model.defer="data.country_rates.{{ $country->id }}.advertiser_rate" 
                                   id="country_rates.{{ $country->id }}.advertiser_rate" 
                                   class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error("country_rates.{$country->id}.advertiser_rate") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-end">
            <x-filament::button type="submit" icon="heroicon-o-check" size="lg">
                CPM Oranlarını Kaydet
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
