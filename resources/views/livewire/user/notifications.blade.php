<div class="relative" x-data="{ open: false }" @click.away="open = false">
    {{-- Bildirim İkonu ve Sayısı --}}
    <div class="relative cursor-pointer" @click="open = !open">
        <svg class="h-6 w-6 text-gray-600 hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.465 6.364 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"></path></svg>
        @if ($notificationCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ $notificationCount }}</span>
        @endif
    </div>

    {{-- Bildirim Listesi (Dropdown) --}}
    <div x-show="open" class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg z-10" style="display: none;">
        <div class="py-1">
            @if ($notifications->count() > 0)
                @foreach ($notifications as $notification)
                    <div class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <p class="font-semibold">{{ $notification->title }}</p>
                        <p class="text-xs text-gray-600">{{ $notification->content }}</p>
                    </div>
                @endforeach
            @else
                <div class="block px-4 py-2 text-sm text-gray-700">
                    Bildirim bulunmuyor.
                </div>
            @endif
        </div>
    </div>
</div>
