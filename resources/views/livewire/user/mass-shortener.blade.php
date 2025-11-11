<div>
    <h3 class="text-xl font-semibold text-gray-700 mb-4">Toplu Link Kısaltma</h3>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <form wire:submit.prevent="shortenUrls">
            <div class="mb-4">
                <label for="urls" class="block text-gray-700 text-sm font-bold mb-2">URL Listesi (Her satıra bir URL)</label>
                <textarea wire:model="urls" id="urls" rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                @error('urls') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Linkleri Kısalt
                </button>
            </div>
        </form>
    </div>

    @if (!empty($shortenedLinks))
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Kısaltılmış Linkler</h3>
            <ul>
                @foreach ($shortenedLinks as $link)
                    <li class="mb-2">
                        <strong>Orijinal:</strong> {{ $link['original'] }} <br>
                        <strong>Kısaltılmış:</strong> <a href="{{ $link['shortened'] }}" target="_blank" class="text-blue-600 hover:underline">{{ $link['shortened'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
