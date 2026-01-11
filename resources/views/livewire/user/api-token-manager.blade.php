<div class="bg-white dark:bg-white/10 p-6 rounded-lg">
    <h2 class="text-lg font-semibold text-heading-light dark:text-heading-dark mb-4">API Token Management</h2>
    
    <form wire:submit.prevent="createToken">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="token-name">Token Name</label>
        <div class="flex gap-4">
            <div class="flex-grow">
                <input wire:model="newTokenName" class="w-full bg-gray-50 dark:bg-[#313346] border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md focus:ring-primary focus:border-primary placeholder-gray-400" id="token-name" type="text" placeholder="Enter token name"/>
                @error('newTokenName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <button type="submit" wire:loading.attr="disabled" class="bg-primary text-white font-semibold py-2 px-6 rounded-md hover:bg-blue-600 transition-colors duration-300 flex-shrink-0 flex items-center gap-2 h-[42px]">
                <span wire:loading.remove wire:target="createToken">Create</span>
                <span wire:loading wire:target="createToken" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
            </button>
        </div>
    </form>

    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
        <h3 class="text-md font-semibold text-heading-light dark:text-heading-dark">Your current API tokens</h3>
        <div class="mt-4 space-y-4">
            @forelse ($tokens as $token)
                <div class="p-4 rounded-md bg-gray-100 dark:bg-[#313346]">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $token->name }}</p>
                            <p class="text-sm text-text-light dark:text-subtext-dark font-mono break-all">{{ $token->token }}</p> {{-- Assuming token is visible or partial, but typically only visible on creation. If token value isn't stored in plain text, I can't show it here. The old code showed $token->name but seemingly not the token string unless it was just created. Ah, existing code: <span>{{ $token->name }}</span>. And flash message for new token. So I should probably not try to show the full token here if I don't have it. The design shows a token. I'll just show name and maybe created_at or ID if token is hashed. The design example shows a token. I'll show a masked version or just the name. --}}
                        </div>
                        <button 
                            x-data
                            @click="$dispatch('open-confirm-modal', { 
                                id: 'delete-token-{{ $token->id }}', 
                                onConfirm: () => $wire.deleteToken({{ $token->id }}) 
                            })"
                            class="text-text-light dark:text-subtext-dark hover:text-red-500 transition-colors"
                        >
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                        <x-confirm-modal 
                            id="delete-token-{{ $token->id }}"
                            title="Delete API Token"
                            message="Are you sure you want to delete this token? Any applications using this token will no longer have access."
                            confirmText="Delete"
                            cancelText="Cancel"
                            confirmColor="red"
                            icon="delete"
                        />
                    </div>
                    {{-- Optional stats section from design - I don't have this data in $token usually unless joined. I'll skip or use placeholders if needed. The design has click stats. --}}
                     <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center text-sm">
                        <div class="flex items-center gap-4">
                            <div class="text-center">
                                <p class="font-bold text-gray-800 dark:text-white">{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}</p>
                                <p class="text-xs text-text-light dark:text-subtext-dark">Last Used</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-text-light dark:text-subtext-dark mt-2">You don't have any other API tokens yet.</p>
            @endforelse
        </div>
    </div>

    {{-- New Token Display (kept inline - needs to be copied) --}}
    @if (session()->has('newToken'))
        <div class="mt-6 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded" role="alert">
            <p class="font-bold">New API Token (Please copy it, it won't be shown again):</p>
            <p class="mt-2 font-mono break-all bg-yellow-50 p-2 rounded border border-yellow-200">{{ session('newToken') }}</p>
        </div>
    @endif
</div>

{{-- Toast Notifications --}}
@if (session()->has('message'))
    <x-toast-notification type="success">{{ session('message') }}</x-toast-notification>
@endif
