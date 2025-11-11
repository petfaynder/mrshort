<div>
    <h3 class="text-xl font-semibold text-gray-700 mb-4">Para Çekme</h3>

    {{-- Para Çekme Talebi Oluştur Formu --}}
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">Yeni Para Çekme Talebi Oluştur</h4>
        <form wire:submit.prevent="createWithdrawalRequest">
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Miktar ($):</label>
                <input wire:model="amount" type="number" step="0.01" id="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('amount') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="paymentMethod" class="block text-gray-700 text-sm font-bold mb-2">Ödeme Yöntemi:</label>
                <select wire:model="paymentMethod" id="paymentMethod" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Seçiniz</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank_transfer">Banka Transferi</option>
                    {{-- Diğer ödeme yöntemleri buraya eklenebilir --}}
                </select>
                @error('paymentMethod') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Talep Oluştur
                </button>
            </div>
        </form>
    </div>

    {{-- Mevcut Talepler Listesi --}}
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">Mevcut Para Çekme Talepleriniz</h4>
        @if ($withdrawalRequests->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Miktar ($)
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ödeme Yöntemi
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durum
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tarih
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($withdrawalRequests as $request)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($request->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $request->payment_method }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $request->status }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $request->created_at->format('Y-m-d H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-600">Henüz para çekme talebiniz bulunmuyor.</p>
        @endif
    </div>

    {{-- Genel Flash Mesajları --}}
    @if (session()->has('message'))
        <div class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif
     @if (session()->has('error'))
        <div class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
</div>
