<x-user-dashboard-layout>
    <h2 class="text-2xl font-bold mb-6">Tools</h2>
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <livewire:user.mass-shortener />
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Full Page Script</h3>
        <p>Bu scripti web sitenize ekleyerek tüm linkleri otomatik olarak kısaltabilirsiniz.</p>
        <textarea rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mt-4" readonly>
            {{-- Full Page Script kodu buraya gelecek --}}
        </textarea>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <livewire:user.api-token-manager />
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Quick Link / Bookmarklet</h3>
        <p>Bu yer işaretini tarayıcınızın yer işaretleri çubuğuna sürükleyip bırakarak aktif sayfayı hızlıca kısaltabilirsiniz.</p>
        <p class="mt-4">
            <a href="javascript:void(0);" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Link Kısalt (Bunu Sürükleyin)
            </a>
        </p>
        {{-- Bookmarklet kodu bu linkin href özelliğine dinamik olarak eklenecek --}}
    </div>

    {{-- Diğer araçlar içeriği buraya gelecek --}}
</x-user-dashboard-layout>