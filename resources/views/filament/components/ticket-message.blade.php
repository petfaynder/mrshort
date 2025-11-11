@props(['message', 'user', 'timestamp', 'is_admin'])

<div class="flex items-start mb-4 {{ $is_admin ? 'justify-end' : '' }}">
    <div class="flex flex-col {{ $is_admin ? 'items-end' : 'items-start' }} w-full"> {{-- Genişlik ayarı eklendi --}}
        <div class="p-3 rounded-lg {{ $is_admin ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }} shadow" style="max-width: 70%;">
            <p class="text-sm font-semibold">{{ $is_admin ? 'Admin' : ($user->name ?? 'Kullanıcı') }}:</p>
            <p class="text-gray-800">{{ $message }}</p>
        </div>
        <span class="text-xs text-gray-500 mt-1 {{ $is_admin ? 'text-right' : 'text-left' }}">{{ $timestamp->format('d/m/Y H:i') }}</span>
    </div>
</div>