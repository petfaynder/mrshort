<div class="bg-white dark:bg-white/10 p-6 rounded-lg" wire:poll.2000ms="pollJobResult">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <h2 class="text-lg font-semibold text-heading-light dark:text-heading-dark">Dead Link Checker</h2>
        <div class="flex items-center gap-4 flex-shrink-0">
            <button wire:click="scanAllLinks" wire:loading.attr="disabled" class="bg-blue-100 dark:bg-blue-600/20 text-blue-600 dark:text-blue-400 font-semibold py-2 px-4 text-sm rounded-md hover:bg-blue-200 dark:hover:bg-blue-600/30 transition-colors duration-300 disabled:opacity-50 flex items-center gap-2">
                <span wire:loading.remove wire:target="scanAllLinks">Scan All Links</span>
                <span wire:loading wire:target="scanAllLinks" class="material-symbols-outlined text-sm animate-spin">refresh</span>
                <span wire:loading wire:target="scanAllLinks">Scanning...</span>
            </button>
            
            <button wire:click="startScan" wire:loading.attr="disabled" class="bg-primary text-white font-semibold py-2 px-4 text-sm rounded-md hover:bg-blue-600 transition-colors duration-300 flex items-center gap-2 disabled:opacity-50">
                <span wire:loading.remove wire:target="startScan">Start Scan</span>
                <span wire:loading wire:target="startScan" class="material-symbols-outlined text-sm animate-spin">refresh</span>
                <span wire:loading wire:target="startScan">Scanning...</span>
            </button>
        </div>
    </div>
    <p class="text-sm text-text-light dark:text-subtext-dark mb-4">We check your latest 20 links initially. Click "Start Scan" to re-check them, or use "Scan All Links" to check up to 100 recent links. Scanning runs in the background — the page will update automatically when complete.</p>

    @if(session('error'))
        <div class="mb-4 p-3 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    @if($isScanning)
        <div class="mb-4">
            <div class="flex justify-between text-xs text-text-light dark:text-subtext-dark mb-1">
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm animate-spin text-primary">progress_activity</span>
                    Scanning in background...
                </span>
                <span>{{ $progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 overflow-hidden">
                @if($progress > 0)
                    <div class="bg-primary h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                @else
                    {{-- Indeterminate bar while waiting for the job to start --}}
                    <div class="bg-primary h-2.5 rounded-full w-1/3 animate-pulse"></div>
                @endif
            </div>
        </div>
    @endif

    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
        <h3 class="text-md font-semibold text-heading-light dark:text-heading-dark mb-3">Reporting</h3>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 rounded-md bg-gray-100 dark:bg-[#313346]">
            <div class="flex items-center gap-3">
                 <p class="text-sm text-text-light dark:text-subtext-dark">Download a detailed report of your links and their status.</p>
            </div>
            <div class="flex items-center gap-4">
                <button wire:click="exportCsv" wire:loading.attr="disabled" class="text-sm flex items-center gap-2 bg-gray-200 dark:bg-gray-600/50 hover:bg-gray-300 dark:hover:bg-gray-600/80 text-heading-light dark:text-white font-semibold py-2 px-3 rounded-md transition-colors duration-300 disabled:opacity-50">
                    <span wire:loading.remove wire:target="exportCsv" class="material-symbols-outlined text-base">download</span>
                    <span wire:loading wire:target="exportCsv" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                    Export CSV
                </button>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto mt-6">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-text-light dark:text-subtext-dark uppercase bg-gray-50 dark:bg-transparent">
                <tr>
                    <th class="py-3 pr-6" scope="col">Short Link</th>
                    <th class="py-3 px-6" scope="col">Original URL</th>
                    <th class="py-3 pl-6" scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($links as $link)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="py-3 pr-6 font-medium text-blue-500 dark:text-blue-400 whitespace-nowrap">
                            <a href="{{ route('shortlink.redirect', $link->code) }}" target="_blank" class="hover:underline">
                                {{ $link->code }}
                            </a>
                        </td>
                        <td class="py-3 px-6 text-blue-500 dark:text-blue-400 truncate max-w-xs" title="{{ $link->original_url }}">
                            {{ Str::limit($link->original_url, 50) }}
                        </td>
                        <td class="py-3 pl-6">
                            @if(isset($results[$link->id]))
                                @if($results[$link->id]['status'] === 'alive')
                                    <span class="text-green-500 dark:text-green-400">
                                        Alive ({{ $results[$link->id]['code'] }})
                                    </span>
                                @elseif($results[$link->id]['status'] === 'dead')
                                    <span class="text-red-500 dark:text-red-400">
                                        Dead Link ({{ $results[$link->id]['code'] }})
                                    </span>
                                @else
                                    <span class="text-yellow-500 dark:text-yellow-400">
                                        Warning ({{ $results[$link->id]['code'] }})
                                    </span>
                                @endif
                            @else
                                <span class="text-text-light dark:text-subtext-dark">
                                    @if($isScanning)
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-xs animate-spin">progress_activity</span> Queued...</span>
                                    @else
                                        —
                                    @endif
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td colspan="3" class="py-4 text-center text-text-light dark:text-subtext-dark">No links found yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
