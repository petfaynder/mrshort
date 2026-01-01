<x-filament-panels::page>
    {{-- Active Campaign Banner --}}
    @if($this->activeCampaign)
        <div class="mb-6 rounded-lg border-2 border-amber-500 bg-amber-50 dark:bg-amber-950/20 p-4">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🎉</span>
                    <div>
                        <h3 class="text-lg font-bold text-amber-900 dark:text-amber-100">
                            Active Campaign: {{ $this->activeCampaign->name }}
                        </h3>
                        <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                            All CPM rates are currently multiplied by <span class="font-bold">{{ $this->activeCampaign->multiplier }}x</span>
                        </p>
                        <div class="flex gap-4 mt-2 text-xs text-amber-600 dark:text-amber-400">
                            <span>Started: {{ $this->activeCampaign->start_date->format('M d, Y H:i') }}</span>
                            <span>•</span>
                            <span>Ends: {{ $this->activeCampaign->end_date->format('M d, Y H:i') }}</span>
                            <span>•</span>
                            <span class="font-semibold">
                                {{ $this->activeCampaign->end_date->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($this->countries as $country)
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm bg-white dark:bg-gray-800 hover:shadow-md transition-shadow {{ $this->activeCampaign ? 'opacity-75' : '' }}">
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
                                   {{ $this->activeCampaign ? 'disabled' : '' }}
                                   class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            @error("country_rates.{$country->id}.publisher_rate") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="country_rates.{{ $country->id }}.advertiser_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reklamcı Oranı</label>
                            <input type="number" 
                                   step="0.0001" 
                                   wire:model.defer="data.country_rates.{{ $country->id }}.advertiser_rate" 
                                   id="country_rates.{{ $country->id }}.advertiser_rate"
                                   {{ $this->activeCampaign ? 'disabled' : '' }}
                                   class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            @error("country_rates.{$country->id}.advertiser_rate") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-end">
            <x-filament::button 
                type="submit" 
                icon="heroicon-o-check" 
                size="lg"
                :disabled="!!$this->activeCampaign">
                CPM Oranlarını Kaydet
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
