@props(['type' => 'success'])

@php
    $types = [
        'success' => ['bg' => 'bg-green-600', 'icon' => 'check_circle'],
        'error' => ['bg' => 'bg-red-600', 'icon' => 'error'],
        'info' => ['bg' => 'bg-blue-600', 'icon' => 'info'],
        'warning' => ['bg' => 'bg-yellow-600', 'icon' => 'warning'],
    ];
    $config = $types[$type] ?? $types['success'];
@endphp

<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 3000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     class="fixed bottom-4 right-4 z-50 rounded-lg {{ $config['bg'] }} px-4 py-3 text-white shadow-lg">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined">{{ $config['icon'] }}</span>
        <span>{{ $slot }}</span>
    </div>
</div>
