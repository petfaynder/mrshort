<div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md mb-8">
    <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Link Manager</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-border-light dark:border-border-dark">
                <tr>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Short Link</th>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Original Link</th>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Clicks</th>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Status</th>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Date</th>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($links as $link)
                    <tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="p-3 text-primary font-semibold">
                            <a href="{{ url($link->code) }}" target="_blank" class="hover:underline">{{ request()->getHost() }}/{{ $link->code }}</a>
                        </td>
                        <td class="p-3 text-heading-light dark:text-heading-dark truncate max-w-xs" title="{{ $link->original_url }}">
                            {{ Str::limit($link->original_url, 40) }}
                        </td>
                        <td class="p-3 text-heading-light dark:text-heading-dark">{{ $link->clicks }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs font-semibold {{ $link->is_hidden ? 'text-red-800 bg-red-100 dark:bg-red-900/50 dark:text-red-300' : 'text-green-800 bg-green-100 dark:bg-green-900/50 dark:text-green-300' }} rounded-full">
                                {{ $link->is_hidden ? 'Hidden' : 'Active' }}
                            </span>
                        </td>
                        <td class="p-3 text-heading-light dark:text-heading-dark">{{ $link->created_at->format('M d, Y') }}</td>
                        <td class="p-3">
                            <div class="flex justify-end items-center gap-2">
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
                                    class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed" 
                                    title="Delete"
                                >
                                    <span wire:loading.remove wire:target="deleteLink({{ $link->id }})" class="material-symbols-outlined text-sm text-red-500">delete</span>
                                    <span wire:loading wire:target="deleteLink({{ $link->id }})" class="material-symbols-outlined text-sm text-red-500 animate-spin">progress_activity</span>
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
                        <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No links created yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
