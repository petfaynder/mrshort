<div>
    <x-user-dashboard-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Reklam Kampanyasını Düzenle') }}
                </h2>
            </div>
        </x-slot>

        <div class="py-8">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Pop-up Kampanya Bilgileri</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Kampanya Adı *</label>
                                    <input type="text" wire:model="name" id="name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Örn: Yaz İndirimi Pop-up">
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="popup_url" class="block text-sm font-medium text-gray-700 mb-2">Hedef URL *</label>
                                    <input type="url" wire:model="popup_url" id="popup_url" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://example.com/kampanya">
                                    @error('popup_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="desired_clicks" class="block text-sm font-medium text-gray-700 mb-2">İstenen Tıklama Sayısı *</label>
                                    <div class="relative">
                                        <input type="number" wire:model.live="desired_clicks" id="desired_clicks" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="1000" step="1000">
                                        <p class="mt-2 text-sm text-gray-500">Kampanyanızın almasını istediğiniz toplam tıklama sayısını belirtin.</p>
                                    </div>
                                    @error('desired_clicks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="calculated_cost" class="block text-sm font-medium text-gray-700 mb-2">Tahmini Maliyet ($)</label>
                                    <div class="relative">
                                        <input type="text" wire:model="calculated_cost" id="calculated_cost" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100" readonly>
                                        <p class="mt-2 text-sm text-gray-500">Seçtiğiniz hedeflemelere ve tıklama sayısına göre hesaplanan tahmini kampanya maliyeti.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Hedef Ülkeler Seçimi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Hedef Ülkeler *</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach($countries as $country)
                                    <label class="relative">
                                        <input type="checkbox" wire:model.live="selectedCountries" value="{{ $country->iso_code }}" class="sr-only peer">
                                        <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition-colors">
                                            <div class="text-center">
                                                <div class="text-lg font-semibold">{{ $country->iso_code }}</div>
                                                <div class="text-xs text-gray-600">{{ $country->name }}</div>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @error('selectedCountries') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                @if(isset($selectedCountries) && count($selectedCountries) > 0)
                                <p class="mt-2 text-sm text-indigo-600">{{ count($selectedCountries) }} ülke seçildi</p>
                                @endif
                            </div>

                            <!-- Hedef Yaş Grupları Seçimi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Hedef Yaş Grupları *</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach($ageRanges as $range => $label)
                                    <label class="relative">
                                        <input type="checkbox" wire:model.live="selectedAgeRanges" value="{{ $range }}" class="sr-only peer">
                                        <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition-colors text-center">
                                            <div class="font-medium">{{ $label }}</div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @error('selectedAgeRanges') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <p class="mt-2 text-sm text-gray-500">Reklamlarınızın hangi yaş gruplarına gösterileceğini seçin.</p>
                            </div>

                            <!-- Hedef Cihazlar Seçimi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Hedef Cihazlar *</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($deviceOptions as $key => $label)
                                    <label class="relative">
                                        <input type="checkbox" wire:model.live="selectedDevices" value="{{ $key }}" class="sr-only peer">
                                        <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition-colors text-center">
                                            <div class="font-medium">{{ $label }}</div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @error('selectedDevices') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <p class="mt-2 text-sm text-gray-500">Reklamlarınızın hangi cihaz türlerinde (masaüstü bilgisayarlar, mobil telefonlar veya tabletler) gösterileceğini seçin. Örneğin, sadece mobil kullanıcıları hedefleyebilirsiniz.</p>
                            </div>

                            <!-- Hedef İşletim Sistemleri Seçimi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Hedef İşletim Sistemleri *</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($osOptions as $key => $label)
                                    <label class="relative">
                                        <input type="checkbox" wire:model.live="selectedOs" value="{{ $key }}" class="sr-only peer">
                                        <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition-colors text-center">
                                            <div class="font-medium">{{ $label }}</div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @error('selectedOs') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <p class="mt-2 text-sm text-gray-500">Reklamlarınızın hangi işletim sistemlerinde (örn: iOS, Android, Windows) gösterileceğini seçin. Belirli bir işletim sistemine sahip kullanıcıları hedeflemek için kullanışlıdır.</p>
                            </div>

                            <!-- Zamanlama ve Limitler -->
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mt-8">Zamanlama ve Limitler (İsteğe Bağlı)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Toplam Bütçe ($)</label>
                                    <div class="relative">
                                        <input type="number" wire:model="budget" id="budget" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="0">
                                        <p class="mt-2 text-sm text-gray-500">Kampanyanız için belirleyeceğiniz toplam harcama limiti. 0 bırakılırsa limitsiz olur.</p>
                                    </div>
                                    @error('budget') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="daily_budget" class="block text-sm font-medium text-gray-700 mb-2">Günlük Bütçe ($)</label>
                                    <div class="relative">
                                        <input type="number" wire:model="daily_budget" id="daily_budget" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="0">
                                        <p class="mt-2 text-sm text-gray-500">Kampanyanızın bir günde harcayabileceği maksimum miktar. 0 bırakılırsa günlük limit uygulanmaz.</p>
                                    </div>
                                    @error('daily_budget') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex items-center mt-4">
                                <input type="checkbox" wire:model.live="run_until_budget_depleted" id="run_until_budget_depleted" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="run_until_budget_depleted" class="ml-2 block text-sm font-medium text-gray-700">Bakiye Bitene Kadar Devam Et</label>
                                <p class="ml-4 text-sm text-gray-500">Bu seçenek aktifse, kampanya belirlenen toplam bütçe bitene kadar devam eder ve bitiş tarihi dikkate alınmaz.</p>
                            </div>
                            @error('run_until_budget_depleted') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Başlangıç Tarihi</label>
                                    <div class="relative">
                                        <input type="date" wire:model="start_date" id="start_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <p class="mt-2 text-sm text-gray-500">Kampanyanın ne zaman başlayacağını belirleyin.</p>
                                    </div>
                                    @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                @unless($run_until_budget_depleted)
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Bitiş Tarihi</label>
                                    <div class="relative">
                                        <input type="date" wire:model="end_date" id="end_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <p class="mt-2 text-sm text-gray-500">Kampanyanın ne zaman sona ereceğini belirleyin. "Bakiye Bitene Kadar Devam Et" seçeneği aktifse bu alan gizlenir.</p>
                                    </div>
                                    @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                @endunless
                            </div>
                            <div class="mt-4">
                                <label for="daily_click_limit" class="block text-sm font-medium text-gray-700 mb-2">Günlük Tıklama Limiti</label>
                                <div class="relative">
                                    <input type="number" wire:model="daily_click_limit" id="daily_click_limit" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="0">
                                    <p class="mt-2 text-sm text-gray-500">Kampanyanızın bir günde alabileceği maksimum tıklama sayısı. 0 bırakılırsa günlük limit uygulanmaz.</p>
                                </div>
                                @error('daily_click_limit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Trafik Bilgileri -->
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mt-8">Trafik Bilgileri</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="estimated_traffic" class="block text-sm font-medium text-gray-700 mb-2">Tahmini Trafik</label>
                                    <input type="text" wire:model="estimated_traffic" id="estimated_traffic" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100" readonly>
                                </div>
                                <div>
                                    <label for="available_traffic" class="block text-sm font-medium text-gray-700 mb-2">Mevcut Trafik</label>
                                    <input type="text" wire:model="available_traffic" id="available_traffic" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100" readonly>
                                </div>
                            </div>

                            <!-- Önizleme -->
                            <div class="bg-gray-50 p-4 rounded-lg mt-8">
                                <h4 class="font-medium text-gray-900 mb-2">Önizleme</h4>
                                <p class="text-sm text-gray-600">Kampanya Adı: <span class="font-medium">{{ $name ?: 'Henüz girilmedi' }}</span></p>
                                <p class="text-sm text-gray-600">Hedef URL: <span class="font-medium">{{ $popup_url ?: 'Henüz girilmedi' }}</span></p>
                                <p class="text-sm text-gray-600">İstenen Tıklama: <span class="font-medium">{{ number_format($desired_clicks) }}</span></p>
                                <p class="text-sm text-gray-600">Tahmini Maliyet: <span class="font-medium">${{ number_format($calculated_cost, 2) }}</span></p>
                                <p class="text-sm text-gray-600">Hedef Ülkeler: <span class="font-medium">{{ count($selectedCountries) > 0 ? implode(', ', $selectedCountries) : 'Yok' }}</span></p>
                                <p class="text-sm text-gray-600">Hedef Yaş Grupları: <span class="font-medium">{{ count($selectedAgeRanges) > 0 ? implode(', ', $selectedAgeRanges) : 'Yok' }}</span></p>
                                <p class="text-sm text-gray-600">Hedef Cihazlar: <span class="font-medium">{{ count($selectedDevices) > 0 ? implode(', ', array_map(fn($device) => $deviceOptions[$device] ?? $device, $selectedDevices)) : 'Yok' }}</span></p>
                                <p class="text-sm text-gray-600">Hedef İşletim Sistemleri: <span class="font-medium">{{ count($selectedOs) > 0 ? implode(', ', array_map(fn($os) => $osOptions[$os] ?? $os, $selectedOs)) : 'Yok' }}</span></p>
                                <p class="text-sm text-gray-600">Toplam Bütçe: <span class="font-medium">${{ number_format($budget, 2) }}</span></p>
                                <p class="text-sm text-gray-600">Bakiye Bitene Kadar Devam Et: <span class="font-medium">{{ $run_until_budget_depleted ? 'Evet' : 'Hayır' }}</span></p>
                                <p class="text-sm text-gray-600">Başlangıç Tarihi: <span class="font-medium">{{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d.m.Y') : 'Yok' }}</span></p>
                                @unless($run_until_budget_depleted)
                                <p class="text-sm text-gray-600">Bitiş Tarihi: <span class="font-medium">{{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d.m.Y') : 'Yok' }}</span></p>
                                @endunless
                                <p class="text-sm text-gray-600">Günlük Tıklama Limiti: <span class="font-medium">{{ $daily_click_limit ?: 'Yok' }}</span></p>
                                <p class="text-sm text-gray-600">Tahmini Trafik: <span class="font-medium">{{ number_format($estimated_traffic) }}</span></p>
                                <p class="text-sm text-gray-600">Mevcut Trafik: <span class="font-medium">{{ number_format($available_traffic) }}</span></p>
                                <p class="text-sm text-gray-600">Durum: <span class="font-medium text-yellow-600">Admin Onayı Bekleniyor</span></p>
                            </div>
                        </div>

                        <!-- Kampanyayı Güncelle Butonu -->
                        <div class="flex justify-end items-center pt-6 border-t mt-6">
                            <button type="submit" wire:click="updateCampaign" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Kampanyayı Güncelle ✓
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-user-dashboard-layout>
</div>
