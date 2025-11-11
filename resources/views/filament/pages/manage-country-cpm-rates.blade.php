    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($this->countries as $country)
                <div class="p-4 border border-gray-200 rounded-lg shadow-sm bg-white">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">{{ $country->name }}</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="country_rates.{{ $country->id }}.publisher_rate" class="block text-sm font-medium text-gray-700">Publisher Rate</label>
                            <input type="number" step="0.0001" wire:model.defer="data.country_rates.{{ $country->id }}.publisher_rate" id="country_rates.{{ $country->id }}.publisher_rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error("country_rates.{$country->id}.publisher_rate") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="country_rates.{{ $country->id }}.advertiser_rate" class="block text-sm font-medium text-gray-700">Advertiser Rate</label>
                            <input type="number" step="0.0001" wire:model.defer="data.country_rates.{{ $country->id }}.advertiser_rate" id="country_rates.{{ $country->id }}.advertiser_rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error("country_rates.{$country->id}.advertiser_rate") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <x-filament::button type="submit" class="mt-4">
            CPM Oranlarını Kaydet
        </x-filament::button>
    </form>
