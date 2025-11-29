<div class="bg-white dark:bg-white/10 p-6 rounded-lg">
    <h2 class="text-lg font-semibold text-heading-light dark:text-heading-dark mb-4">Bulk Link Shortener</h2>
    
    <form wire:submit.prevent="shortenUrls">
        <textarea wire:model="urls" rows="6" class="w-full bg-gray-50 dark:bg-[#313346] border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md focus:ring-primary focus:border-primary placeholder-gray-400 font-mono text-sm p-3" placeholder="URL List (One URL per line)"></textarea>
        @error('urls') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        
        <button type="submit" wire:loading.attr="disabled" class="mt-4 w-full bg-primary text-white font-semibold py-2.5 px-6 rounded-md hover:bg-blue-600 transition-colors duration-300 flex justify-center items-center gap-2">
            <span wire:loading.remove wire:target="shortenUrls">Shorten Links</span>
            <span wire:loading wire:target="shortenUrls" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
            <span wire:loading wire:target="shortenUrls">Shortening...</span>
        </button>
    </form>

    @if (!empty($shortenedLinks))
        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
            <h3 class="text-md font-semibold text-heading-light dark:text-heading-dark mb-3">Shortened Links</h3>
            <div class="bg-gray-100 dark:bg-[#313346] rounded-md overflow-hidden">
                <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($shortenedLinks as $link)
                        <li class="p-3 text-sm flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <span class="text-text-light dark:text-subtext-dark truncate max-w-xs" title="{{ $link['original'] }}">{{ Str::limit($link['original'], 40) }}</span>
                            <div class="flex items-center gap-2">
                                <a href="{{ $link['shortened'] }}" target="_blank" class="text-blue-500 dark:text-blue-400 font-medium hover:underline">{{ $link['shortened'] }}</a>
                                <button onclick="navigator.clipboard.writeText('{{ $link['shortened'] }}')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" title="Copy">
                                    <span class="material-symbols-outlined text-sm">content_copy</span>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
