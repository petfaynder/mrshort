<div class="bg-card-light dark:bg-card-dark p-4 sm:p-6 rounded-lg mb-6 shadow-md">
    <div class="flex flex-col sm:flex-row gap-3">
        <input wire:model="original_url" wire:keydown.enter="shortenLink"
               class="flex-1 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent text-heading-light dark:text-heading-dark placeholder:text-text-light dark:placeholder:text-text-dark text-base"
               placeholder="Paste your long URL here…" type="text"/>
        <button wire:click="shortenLink" wire:loading.attr="disabled"
                class="flex items-center justify-center gap-2 bg-primary text-white font-semibold px-5 py-3 rounded-lg hover:bg-blue-600 transition-colors disabled:opacity-75 disabled:cursor-not-allowed whitespace-nowrap">
            <span wire:loading.remove wire:target="shortenLink" class="material-symbols-outlined text-xl">content_cut</span>
            <span wire:loading wire:target="shortenLink" class="material-symbols-outlined animate-spin text-xl">progress_activity</span>
            <span>Shrink Now</span>
        </button>
    </div>
    @error('original_url') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror

    {{-- Shortened Link Result Display --}}
    @if ($shortenedLink)
        <div x-data="{ copied: false }" class="mt-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3 overflow-hidden min-w-0">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400 flex-shrink-0">check_circle</span>
                <a href="{{ $shortenedLink }}" target="_blank"
                   class="text-green-700 dark:text-green-400 font-semibold text-base sm:text-lg truncate hover:underline min-w-0">{{ $shortenedLink }}</a>
            </div>
            <button
                x-on:click="navigator.clipboard.writeText('{{ $shortenedLink }}'); copied = true; setTimeout(() => copied = false, 2000)"
                class="flex-shrink-0 flex items-center gap-2 px-3 py-2 bg-green-200 dark:bg-green-800 hover:bg-green-300 dark:hover:bg-green-700 rounded-lg transition-colors text-green-800 dark:text-green-300 text-sm font-medium"
                title="Copy to Clipboard">
                <span x-show="!copied" class="material-symbols-outlined text-base">content_copy</span>
                <span x-show="copied" class="material-symbols-outlined text-base">check</span>
                <span x-show="!copied">Copy</span>
                <span x-show="copied">Copied!</span>
            </button>
        </div>
    @endif
</div>

