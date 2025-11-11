<div>
    <h3 class="text-xl font-semibold text-gray-700 mb-4">API Token Yönetimi</h3>

    {{-- Yeni Token Oluştur Formu --}}
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">Yeni API Token Oluştur</h4>
        <form wire:submit.prevent="createToken">
            <div class="mb-4">
                <label for="newTokenName" class="block text-gray-700 text-sm font-bold mb-2">Token Adı:</label>
                <input wire:model="newTokenName" type="text" id="newTokenName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('newTokenName') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Oluştur
                </button>
            </div>
        </form>
    </div>

    {{-- Mevcut Tokenlar Listesi --}}
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">Mevcut API Tokenlarınız</h4>
        @if ($tokens->count() > 0)
            <ul>
                @foreach ($tokens as $token)
                    <li class="flex items-center justify-between mb-2">
                        <span>{{ $token->name }}</span>
                        <button wire:click="deleteToken({{ $token->id }})" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded focus:outline-none focus:shadow-outline text-sm">
                            Sil
                        </button>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-600">Henüz API tokenınız bulunmuyor.</p>
        @endif
    </div>

    {{-- Yeni Oluşturulan Token Değeri (Flash Mesajı ile Gösterilecek) --}}
    @if (session()->has('newToken'))
        <div class="mt-6 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p class="font-bold">Yeni API Tokenınız (Lütfen kopyalayın, bir daha gösterilmeyecek):</p>
            <p class="mt-2 break-all">{{ session('newToken') }}</p>
        </div>
    @endif

    {{-- Genel Flash Mesajları --}}
    @if (session()->has('message'))
        <div class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif
</div>
