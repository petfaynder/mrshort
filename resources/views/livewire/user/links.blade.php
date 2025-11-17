<div>
    <div class="mx-auto w-full px-4 py-8 sm:px-6 md:px-8">
        <div class="flex flex-col gap-8">
            <div class="flex flex-col flex-wrap justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex min-w-72 flex-col gap-2">
                <p class="text-3xl font-black leading-tight tracking-[-0.033em] text-gray-900 dark:text-white">Welcome back, Admin User</p>
                <p class="text-base font-normal leading-normal text-gray-500 dark:text-gray-400">Here's your link management dashboard.</p>
            </div>
            <div class="flex flex-wrap gap-4">
                <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900/50">
                    <p class="text-base font-medium leading-normal text-gray-600 dark:text-gray-300">Current Balance</p>
                    <p class="tracking-light text-2xl font-bold leading-tight text-gray-900 dark:text-white">$1,234.56</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900/50 sm:p-6">
            <h2 class="pb-3 text-lg font-bold leading-tight tracking-[-0.015em] text-gray-900 dark:text-white">Shorten a New Link</h2>
            <form wire:submit.prevent.stop="shortenLink" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <label class="flex flex-col flex-1">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Destination URL</span>
                    <input type="url" wire:model="original_url" id="original_url" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg border border-gray-300 bg-gray-50 text-gray-900 placeholder:text-gray-400 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-background-dark dark:text-white dark:placeholder:text-gray-500 h-14 p-[15px] text-base font-normal leading-normal @error('original_url') border-red-500 @enderror" placeholder="https://enter-a-long-url-to-shorten.com/..." value=""/>
                </label>
                <button type="submit" class="flex h-14 min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg bg-primary px-6 text-base font-bold text-white transition-colors hover:bg-primary/90">
                    <span class="truncate">Shorten</span>
                </button>
            </form>
            @error('original_url')
                <div class="text-red-500 mt-2 text-sm">
                    {{ $message }}
                </div>
            @enderror
            @if (session()->has('message'))
                <div class="text-green-500 mt-2 text-sm">
                    {{ session('message') }}
                </div>
            @endif
        </div>
        <div class="flex flex-col gap-4">
            <div class="flex flex-col items-start justify-between gap-4">
                <h2 class="text-xl font-bold leading-tight tracking-[-0.015em] text-gray-900 dark:text-white">Your Links</h2>
                <div class="flex w-full flex-col gap-3 lg:flex-row lg:items-center">
                    <div class="relative w-full flex-1">
                        <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input class="form-input w-full rounded-lg border border-gray-300 bg-white py-2 pl-10 pr-4 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-background-dark dark:text-white" placeholder="Search links by URL or alias..." type="text"/>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 sm:flex-nowrap">
                        <select class="form-select w-full rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-background-dark dark:text-white sm:w-auto">
                            <option>All Tags</option>
                            <option>Social Media</option>
                            <option>Campaign</option>
                            <option>Personal</option>
                        </select>
                        <input class="form-input w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-500 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-background-dark dark:text-gray-400 sm:w-auto" type="date"/>
                        <select class="form-select w-full rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-background-dark dark:text-white sm:w-auto">
                            <option>Sort by: Newest</option>
                            <option>Sort by: Oldest</option>
                            <option>Sort by: Clicks (High to Low)</option>
                            <option>Sort by: Clicks (Low to High)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">3 links selected</span>
                <div class="flex items-center gap-2">
                    <button class="flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800">
                        <span class="material-symbols-outlined text-sm">label</span> Tag
                    </button>
                    <button class="flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800">
                        <span class="material-symbols-outlined text-sm">toggle_on</span> Activate
                    </button>
                    <button class="flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 dark:text-red-500 dark:hover:bg-red-500/10">
                        <span class="material-symbols-outlined text-sm">delete</span> Delete
                    </button>
                </div>
            </div>
            <div class="w-full overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1200px] text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                            <tr>
                                <th class="p-4" scope="col">
                                    <div class="flex items-center">
                                        <input class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary focus:ring-2 focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800" id="checkbox-all" type="checkbox"/>
                                        <label class="sr-only" for="checkbox-all">select all links</label>
                                    </div>
                                </th>
                                <th class="px-6 py-3 font-medium" scope="col">Short Link</th>
                                <th class="px-6 py-3 font-medium" scope="col">Original URL</th>
                                <th class="px-6 py-3 font-medium" scope="col">Tags</th>
                                <th class="px-6 py-3 font-medium text-center" scope="col">Clicks</th>
                                <th class="px-6 py-3 font-medium text-center" scope="col">Performance (7d)</th>
                                <th class="px-6 py-3 font-medium" scope="col">Created At</th>
                                <th class="px-6 py-3 font-medium text-right" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-900/50">
                            @if ($links->count())
                                @foreach ($links as $link)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900">
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                <input class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary focus:ring-2 focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800" id="checkbox-{{ $link->id }}" type="checkbox"/>
                                                <label class="sr-only" for="checkbox-{{ $link->id }}">select link {{ $link->id }}</label>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="group relative flex items-center gap-2">
                                                <a class="text-primary hover:underline" href="{{ $link->shortLink() }}" target="_blank">{{ $link->shortLink() }}</a>
                                                <button onclick="copyToClipboard('{{ $link->shortLink() }}')" class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary" data-alt="Copy link button">
                                                    <span class="material-symbols-outlined text-base">content_copy</span>
                                                </button>
                                                <div class="pointer-events-none absolute -top-1 left-0 z-10 w-max -translate-y-full rounded-md bg-gray-900 px-3 py-1.5 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100 dark:bg-gray-700">
                                                    Preview: {{ Str::limit($link->original_url, 30) }}
                                                    <div class="absolute left-4 top-full h-2 w-2 rotate-45 bg-gray-900 dark:bg-gray-700"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="max-w-xs truncate px-6 py-4 font-medium text-gray-900 dark:text-white" title="{{ $link->original_url }}"><a class="hover:underline" href="{{ $link->original_url }}" target="_blank">{{ Str::limit($link->original_url, 50) }}</a></td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                {{-- Tags will be implemented later if needed --}}
                                                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">Campaign</span>
                                                <span class="rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-300">Social</span>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center">{{ $link->clicks }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex h-8 w-24 items-end gap-0.5" x-data="{
                                                stats: {{ json_encode($performanceData[$link->id] ?? array_fill(0, 7, 0)) }},
                                                get maxClicks() {
                                                    return Math.max(...this.stats, 1);
                                                },
                                                height(clicks) {
                                                    return (clicks / this.maxClicks) * 100 + '%';
                                                }
                                            }">
                                                <template x-for="(clickCount, index) in stats" :key="index">
                                                    <div class="w-full rounded-t-sm bg-primary/40" :style="{ height: height(clickCount) }" :title="clickCount + ' clicks'"></div>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-gray-500 dark:text-gray-400">{{ $link->created_at->format('M d, Y') }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <button wire:click="toggleStats({{ $link->id }})" class="flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                                                    <span class="material-symbols-outlined text-sm">bar_chart</span> Stats
                                                </button>
                                                <button wire:click="editLink({{ $link->id }})" class="flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                                                    <span class="material-symbols-outlined text-sm">settings</span> Details
                                                </button>
                                                <button wire:click="deleteLink({{ $link->id }})" onclick="confirm('Are you sure you want to delete this link?') || event.stopImmediatePropagation()" class="flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 dark:text-red-500 dark:hover:bg-red-500/10">
                                                    <span class="material-symbols-outlined text-sm">delete</span> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @if ($showingStats === $link->id)
                                        <tr>
                                            <td colspan="8" class="px-6 py-4">
                                                @if (!empty($statsData))
                                                    <h5 class="text-lg font-semibold text-gray-700 mb-2 dark:text-gray-200">Günlük İstatistikler</h5>
                                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                                                        <thead>
                                                            <tr>
                                                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:bg-gray-900 dark:text-gray-400">
                                                                    Tarih
                                                                </th>
                                                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:bg-gray-900 dark:text-gray-400">
                                                                    Tıklama Sayısı
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-800 dark:bg-gray-900/50">
                                                            @foreach ($statsData as $stat)
                                                                <tr>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                                        {{ $stat->click_date }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                                        {{ $stat->total_clicks }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <p class="text-gray-600 dark:text-gray-400">İstatistikler yükleniyor veya mevcut değil...</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                        Henüz kısaltılmış bir bağlantınız yok.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Pagination --}}
            <nav aria-label="Table navigation" class="flex flex-col items-center justify-between gap-4 py-3 sm:flex-row">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span class="font-semibold text-gray-900 dark:text-white">1-10</span> of <span class="font-semibold text-gray-900 dark:text-white">1000</span></span>
                <div class="inline-flex h-8 -space-x-px text-sm">
                    <a class="flex items-center justify-center rounded-l-lg border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" href="#">Previous</a>
                    <a aria-current="page" class="z-10 flex items-center justify-center border border-primary bg-primary/20 px-3 leading-tight text-primary hover:bg-primary/30 hover:text-primary dark:border-gray-700 dark:bg-gray-700 dark:text-white" href="#">1</a>
                    <a class="flex items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" href="#">2</a>
                    <a class="flex items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" href="#">3</a>
                    <a class="flex items-center justify-center rounded-r-lg border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" href="#">Next</a>
                </div>
            </nav>
        </div>
    </div>
</div>

{{-- Link Details & Settings Modal --}}
@if ($editingLink)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-2xl rounded-xl border border-gray-200 bg-background-light dark:border-gray-800 dark:bg-background-dark">
            <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                <h3 class="text-lg font-bold leading-tight tracking-[-0.015em] text-gray-900 dark:text-white">Link Details & Settings</h3>
                <button wire:click="cancelEdit" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form wire:submit.prevent="updateLink">
                <div class="max-h-[70vh] space-y-6 overflow-y-auto p-6">
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">Basic Information</h4>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300" for="original-url">Original URL</label>
                            <input wire:model="newOriginalUrl" class="form-input w-full rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('newOriginalUrl') border-red-500 @enderror" id="original-url" type="text"/>
                            @error('newOriginalUrl') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300" for="short-link">Short Link</label>
                            <div class="relative">
                                <input wire:model="newShortLink" class="form-input w-full rounded-lg border border-gray-300 bg-gray-50 pl-20 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('newShortLink') border-red-500 @enderror" id="short-link" type="text"/>
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-500 dark:text-gray-400">short.ly/</span>
                            </div>
                            @error('newShortLink') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300" for="link-title">Title <span class="text-gray-400 dark:text-gray-500">(Optional)</span></label>
                            <input wire:model="newTitle" class="form-input w-full rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('newTitle') border-red-500 @enderror" id="link-title" placeholder="e.g. My Awesome Campaign" type="text"/>
                            @error('newTitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-300" for="expires-at">Expires At <span class="text-gray-400 dark:text-gray-500">(Optional)</span></label>
                            <input wire:model="newExpiresAt" class="form-input w-full rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('newExpiresAt') border-red-500 @enderror" id="expires-at" type="datetime-local"/>
                            @error('newExpiresAt') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">Tags</h4>
                        <div class="flex flex-wrap items-center gap-2 rounded-lg border border-gray-300 p-2 dark:border-gray-700">
                            {{-- Tags will be implemented later if needed --}}
                            <div class="flex items-center gap-1 rounded bg-blue-100 px-2 py-1 text-sm text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                <span>Campaign</span>
                                <button class="text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100">
                                    <span class="material-symbols-outlined !text-sm">close</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-1 rounded bg-purple-100 px-2 py-1 text-sm text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                <span>Social</span>
                                <button class="text-purple-600 hover:text-purple-800 dark:text-purple-300 dark:hover:text-purple-100">
                                    <span class="material-symbols-outlined !text-sm">close</span>
                                </button>
                            </div>
                            <input class="min-w-0 flex-1 bg-transparent p-1 text-sm text-gray-900 placeholder-gray-500 focus:outline-none dark:text-white" placeholder="Add a tag..." type="text"/>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-900/50">
                    <button type="button" wire:click="cancelEdit" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">Cancel</button>
                    <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary/90">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endif

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            console.log('Async: Copying to clipboard was successful!');
        }, function(err) {
            console.error('Async: Could not copy text: ', err);
        });
    }
</script>
</div>
