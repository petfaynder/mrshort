<x-filament-panels::page>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Admin Dashboard</h2>
        @if(auth()->user()) {{-- Admin panelinde her zaman kullanıcı girişi olduğu varsayılır --}}
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Kullanıcı Paneli</a>
        @endif
    </div>
    @livewire('admin-dashboard-stats')

</x-filament-panels::page>
