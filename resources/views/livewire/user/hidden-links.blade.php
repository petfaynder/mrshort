<div>
    <div class="mx-auto w-full px-4 py-8 sm:px-6 md:px-8">
        <div class="flex flex-col gap-8">
            <div class="flex flex-col flex-wrap justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex min-w-72 flex-col gap-2">
                    <h1 class="text-3xl font-black leading-tight tracking-[-0.033em] text-gray-900 dark:text-white">Hidden Links</h1>
                    <p class="text-base font-normal leading-normal text-gray-500 dark:text-gray-400">Manage and restore links you've hidden from your main dashboard.</p>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="flex flex-col items-start justify-between gap-4">
                    <div class="flex w-full flex-col gap-3 lg:flex-row lg:items-center">
                        <div class="relative w-full flex-1">
                            <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input wire:model.live.debounce.300ms="search" class="form-input w-full rounded-lg border border-gray-300 bg-white py-2 pl-10 pr-4 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-background-dark dark:text-white" placeholder="Search hidden links..." type="text"/>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 sm:flex-nowrap">
                            <select wire:model.live="sortStr" class="form-select w-full rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-background-dark dark:text-white sm:w-auto">
                                <option value="newest">Sort by: Newest</option>
                                <option value="oldest">Sort by: Oldest</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if(count($selectedLinks) > 0)
                <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ count($selectedLinks) }} links selected</span>
                    <div class="flex items-center gap-2">
                        <button 
                            wire:click="unhideSelected"
                            wire:loading.attr="disabled" 
                            class="flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-semibold text-primary hover:bg-primary/5 dark:hover:bg-primary/10 disabled:opacity-75 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="unhideSelected" class="material-symbols-outlined text-sm">visibility</span>
                            <span wire:loading wire:target="unhideSelected" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                            Make Visible
                        </button>
                    </div>
                </div>
                @endif

                <div class="w-full overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[800px] text-left text-sm">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                <tr>
                                    <th class="p-4" scope="col">
                                        <div class="flex items-center">
                                            <input wire:model.live="selectAll" class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary focus:ring-2 focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800" id="checkbox-all" type="checkbox"/>
                                            <label class="sr-only" for="checkbox-all">select all links</label>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 font-medium" scope="col">Original URL</th>
                                    <th class="px-6 py-3 font-medium" scope="col">Short Link</th>
                                    <th class="px-6 py-3 font-medium" scope="col">Hidden Date</th>
                                    <th class="px-6 py-3 font-medium text-right" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-900/50">
                                @forelse ($hiddenLinks as $link)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900">
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                <input wire:model.live="selectedLinks" value="{{ $link->id }}" class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary focus:ring-2 focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800" id="checkbox-{{ $link->id }}" type="checkbox"/>
                                                <label class="sr-only" for="checkbox-{{ $link->id }}">select link {{ $link->id }}</label>
                                            </div>
                                        </td>
                                        <td class="max-w-xs truncate px-6 py-4 font-medium text-gray-900 dark:text-white" title="{{ $link->original_url }}">
                                            <a class="hover:underline" href="{{ $link->original_url }}" target="_blank">{{ Str::limit($link->original_url, 50) }}</a>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <a class="text-primary hover:underline" href="{{ $link->shortLink() }}" target="_blank">{{ $link->shortLink() }}</a>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-gray-500 dark:text-gray-400">
                                            {{ $link->updated_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right">
                                            <button wire:click="unhideLink({{ $link->id }})" wire:loading.attr="disabled" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-semibold text-green-600 hover:bg-green-50 dark:text-green-500 dark:hover:bg-green-500/10 disabled:opacity-50 disabled:cursor-not-allowed">
                                                <span wire:loading.remove wire:target="unhideLink({{ $link->id }})" class="material-symbols-outlined text-sm">visibility</span>
                                                <span wire:loading wire:target="unhideLink({{ $link->id }})" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                                                <span>Make Visible</span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <span class="material-symbols-outlined text-4xl text-gray-400">visibility_off</span>
                                                <p class="text-lg font-medium text-gray-900 dark:text-white">No hidden links found</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">You don't have any hidden links at the moment.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $hiddenLinks->links() }}
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-4 right-4 z-50 rounded-lg bg-green-600 px-4 py-3 text-white shadow-lg">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif
</div>
