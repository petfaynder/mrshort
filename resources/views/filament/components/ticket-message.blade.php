@props(['message', 'user', 'timestamp', 'is_admin'])

<div class="flex items-start mb-4 {{ $is_admin ? 'justify-end' : '' }}">
    <div class="flex items-start gap-3 {{ $is_admin ? 'flex-row-reverse' : '' }}" style="max-width: 75%;">
        {{-- Avatar --}}
        <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm
                {{ $is_admin ? 'bg-gradient-to-br from-emerald-500 to-emerald-600' : 'bg-gradient-to-br from-blue-500 to-blue-600' }}">
                {{ $is_admin ? 'A' : strtoupper(substr($user->name ?? 'K', 0, 1)) }}
            </div>
        </div>
        
        {{-- Message Bubble --}}
        <div class="flex flex-col {{ $is_admin ? 'items-end' : 'items-start' }}">
            <div class="px-4 py-3 rounded-2xl shadow-sm
                {{ $is_admin 
                    ? 'bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-tr-sm' 
                    : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700 rounded-tl-sm' 
                }}">
                <p class="text-sm font-medium mb-1 {{ $is_admin ? 'text-emerald-100' : 'text-gray-500 dark:text-gray-400' }}">
                    {{ $is_admin ? 'Admin' : ($user->name ?? 'Kullanıcı') }}
                </p>
                <p class="text-sm leading-relaxed">{{ $message }}</p>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 px-1">
                {{ $timestamp->format('d.m.Y H:i') }}
            </span>
        </div>
    </div>
</div>