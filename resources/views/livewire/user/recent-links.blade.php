<div class="bg-card-light dark:bg-card-dark p-4 sm:p-6 rounded-xl shadow-md mb-6">
    <h3 class="text-lg sm:text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Link Manager</h3>

    {{-- Desktop Table (hidden on mobile) --}}
    <div class="hidden md:block overflow-x-auto -mx-1">
        <table class="w-full text-left min-w-[600px]">
            <thead class="border-b border-border-light dark:border-border-dark">
                <tr>
                    <th class="p-3 text-xs font-semibold uppercase text-text-light dark:text-text-dark">Short Link</th>
                    <th class="p-3 text-xs font-semibold uppercase text-text-light dark:text-text-dark">Original Link</th>
                    <th class="p-3 text-xs font-semibold uppercase text-text-light dark:text-text-dark">Clicks</th>
                    <th class="p-3 text-xs font-semibold uppercase text-text-light dark:text-text-dark">Status</th>
                    <th class="p-3 text-xs font-semibold uppercase text-text-light dark:text-text-dark">Date</th>
                    <th class="p-3 text-xs font-semibold uppercase text-text-light dark:text-text-dark text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($links as $link)
                    <tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="p-3 text-primary font-semibold text-sm">
                            <a href="{{ url($link->code) }}" target="_blank" class="hover:underline">{{ request()->getHost() }}/{{ $link->code }}</a>
                        </td>
                        <td class="p-3 text-heading-light dark:text-heading-dark truncate max-w-xs text-sm" title="{{ $link->original_url }}">
                            {{ Str::limit($link->original_url, 40) }}
                        </td>
                        <td class="p-3 text-heading-light dark:text-heading-dark text-sm">{{ $link->clicks }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs font-semibold {{ $link->is_hidden ? 'text-red-800 bg-red-100 dark:bg-red-900/50 dark:text-red-300' : 'text-green-800 bg-green-100 dark:bg-green-900/50 dark:text-green-300' }} rounded-full">
                                {{ $link->is_hidden ? 'Hidden' : 'Active' }}
                            </span>
                        </td>
                        <td class="p-3 text-heading-light dark:text-heading-dark text-sm">{{ $link->created_at->format('M d, Y') }}</td>
                        <td class="p-3">
                            <div class="flex justify-end items-center gap-1">
                                <a href="{{ route('user.links.index') }}" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700" title="Manage">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </a>
                                <a href="{{ route('stats', $link->code) }}" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700" title="Stats">
                                    <span class="material-symbols-outlined text-sm">bar_chart</span>
                                </a>
                                <button
                                    x-data
                                    @click="$dispatch('open-confirm-modal', {
                                        id: 'delete-recent-link-{{ $link->id }}',
                                        onConfirm: () => $wire.deleteLink({{ $link->id }})
                                    })"
                                    wire:loading.attr="disabled"
                                    class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50"
                                    title="Delete"
                                >
                                    <span wire:loading.remove wire:target="deleteLink({{ $link->id }})" class="material-symbols-outlined text-sm text-red-500">delete</span>
                                    <span wire:loading wire:target="deleteLink({{ $link->id }})" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                                </button>
                                <x-confirm-modal
                                    id="delete-recent-link-{{ $link->id }}"
                                    title="Delete Link"
                                    message="Are you sure you want to delete this link?"
                                    confirmText="Delete"
                                    cancelText="Cancel"
                                    confirmColor="red"
                                    icon="delete"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500 dark:text-gray-400">
                            No links created yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card Layout (shown only on mobile) --}}
    <div class="md:hidden space-y-3">
        @forelse ($links as $link)
            <div class="p-3 bg-background-light dark:bg-background-dark rounded-lg border border-border-light dark:border-border-dark">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <a href="{{ url($link->code) }}" target="_blank"
                       class="text-primary font-semibold text-sm hover:underline truncate min-w-0">
                        {{ request()->getHost() }}/{{ $link->code }}
                    </a>
                    <span class="flex-shrink-0 px-2 py-0.5 text-xs font-semibold {{ $link->is_hidden ? 'text-red-800 bg-red-100 dark:bg-red-900/50 dark:text-red-300' : 'text-green-800 bg-green-100 dark:bg-green-900/50 dark:text-green-300' }} rounded-full">
                        {{ $link->is_hidden ? 'Hidden' : 'Active' }}
                    </span>
                </div>
                <p class="text-xs text-text-light dark:text-text-dark truncate mb-2" title="{{ $link->original_url }}">
                    {{ Str::limit($link->original_url, 50) }}
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 text-xs text-text-light dark:text-text-dark">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">touch_app</span>
                            {{ $link->clicks }} clicks
                        </span>
                        <span>{{ $link->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('user.links.index') }}" class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700" title="Manage">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </a>
                        <a href="{{ route('stats', $link->code) }}" class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700" title="Stats">
                            <span class="material-symbols-outlined text-sm">bar_chart</span>
                        </a>
                        <button
                            x-data
                            @click="$dispatch('open-confirm-modal', {
                                id: 'delete-recent-link-mobile-{{ $link->id }}',
                                onConfirm: () => $wire.deleteLink({{ $link->id }})
                            })"
                            class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"
                            title="Delete"
                        >
                            <span class="material-symbols-outlined text-sm text-red-500">delete</span>
                        </button>
                        <x-confirm-modal
                            id="delete-recent-link-mobile-{{ $link->id }}"
                            title="Delete Link"
                            message="Are you sure you want to delete this link?"
                            confirmText="Delete"
                            cancelText="Cancel"
                            confirmColor="red"
                            icon="delete"
                        />
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                No links created yet.
            </div>
        @endforelse
    </div>
</div>
