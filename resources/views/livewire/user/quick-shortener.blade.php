<div class="bg-card-light dark:bg-card-dark p-6 rounded-lg mb-8 shadow-md">
    <div class="flex items-center gap-4">
        <input wire:model="original_url" wire:keydown.enter="shortenLink" class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent text-heading-light dark:text-heading-dark placeholder:text-text-light dark:placeholder:text-text-dark" placeholder="Paste your long URL here" type="text"/>
        <button wire:click="shortenLink" wire:loading.attr="disabled" class="bg-primary text-white font-semibold px-6 py-3 rounded-lg flex items-center gap-2 whitespace-nowrap hover:bg-blue-600 transition-colors disabled:opacity-75 disabled:cursor-not-allowed">
            <span wire:loading.remove wire:target="shortenLink" class="material-symbols-outlined">content_cut</span>
            <span wire:loading wire:target="shortenLink" class="material-symbols-outlined animate-spin">progress_activity</span>
            Shrink Now
        </button>
    </div>
    @error('original_url') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
    @if (session()->has('status'))
        <div class="text-green-500 text-sm mt-2 block">
            {{ session('status') }}
        </div>
    @endif
</div>
