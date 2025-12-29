@props(['message', 'user', 'timestamp', 'is_admin'])

<div class="flex items-start mb-4" style="{{ $is_admin ? 'justify-content: flex-end;' : '' }}">
    <div class="flex items-start gap-3" style="{{ $is_admin ? 'flex-direction: row-reverse;' : '' }} max-width: 85%;">
        {{-- Avatar --}}
        <div class="flex-shrink-0">
            <div style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); {{ $is_admin ? 'background-color: #059669; color: white;' : 'background-color: #2563eb; color: white;' }}">
                {{ $is_admin ? 'A' : strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
        </div>
        
        {{-- Message Bubble --}}
        <div class="flex flex-col" style="{{ $is_admin ? 'align-items: flex-end;' : 'align-items: flex-start;' }}">
            <div style="padding: 0.75rem 1rem; border-radius: 0.75rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); {{ $is_admin ? 'background-color: #059669;' : 'background-color: #ffffff; border: 2px solid #d1d5db;' }}">
                <p style="font-size: 0.75rem; font-weight: 700; margin-bottom: 0.25rem; {{ $is_admin ? 'color: #d1fae5;' : 'color: #1d4ed8;' }}">
                    {{ $is_admin ? 'Admin' : ($user->name ?? 'User') }}
                </p>
                <p style="font-size: 0.875rem; line-height: 1.625; white-space: pre-wrap; {{ $is_admin ? 'color: #ffffff;' : 'color: #000000;' }}">{{ $message }}</p>
            </div>
            <span style="font-size: 0.75rem; margin-top: 0.375rem; padding: 0 0.25rem; color: #6b7280;">
                {{ $timestamp->format('d M Y, H:i') }}
            </span>
        </div>
    </div>
</div>