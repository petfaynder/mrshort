<div class="p-6 rounded-xl border-2 border-gray-300 max-h-[500px] overflow-y-auto" style="background-color: #f9fafb !important;">
    {{-- Initial Message --}}
    @include('filament.components.ticket-message', [
        'message' => $record->message,
        'user' => $record->user,
        'timestamp' => $record->created_at,
        'is_admin' => false,
    ])

    {{-- Replies --}}
    @foreach($record->replies as $reply)
        @include('filament.components.ticket-message', [
            'message' => $reply->message,
            'user' => $reply->user,
            'timestamp' => $reply->created_at,
            'is_admin' => $reply->user->is_admin ?? false,
        ])
    @endforeach

    @if($record->replies->isEmpty())
        <div class="text-center py-8" style="color: #6b7280;">
            <svg class="w-12 h-12 mx-auto mb-3" style="color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <p class="text-sm">No replies yet</p>
        </div>
    @endif
</div>
