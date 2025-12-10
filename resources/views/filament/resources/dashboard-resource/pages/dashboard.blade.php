<x-filament-panels::page>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Hoşgeldiniz, {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                {{ now()->locale('tr')->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->user())
                <x-filament::button 
                    color="gray"
                    icon="heroicon-o-arrow-right-on-rectangle"
                    tag="a"
                    href="{{ route('dashboard') }}"
                >
                    Kullanıcı Paneli
                </x-filament::button>
            @endif
        </div>
    </div>
    
    @livewire('admin-dashboard-stats')
</x-filament-panels::page>
