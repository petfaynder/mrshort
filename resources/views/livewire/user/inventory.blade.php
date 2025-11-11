<div>
    <div class="space-y-8">
        <h2 class="text-2xl font-semibold text-slate-800 dark:text-white">Inventory</h2>
        
        {{-- Convert Points --}}
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm">
            <div class="p-6 bg-blue-50 dark:bg-slate-700/50 rounded-t-lg">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Convert Points to Real Money</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Current Points: <span class="font-medium text-slate-700 dark:text-slate-300">{{ number_format(Auth::user()->gamification_points) }}</span></p>
                <p class="text-sm text-slate-500 dark:text-slate-400">Conversion Rate: <span class="font-medium text-slate-700 dark:text-slate-300">1 Point = {{ number_format($conversionRate, 4) }} Unit of Money</span></p>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <input wire:model.defer="pointsToConvert" class="flex-grow w-full px-4 py-2 bg-slate-100 dark:bg-slate-700 border-slate-200 dark:border-slate-600 rounded focus:ring-primary focus:border-primary text-slate-800 dark:text-slate-200" placeholder="0" type="number" min="1" max="{{ Auth::user()->gamification_points }}">
                    <button wire:click="convertPoints" class="px-6 py-2 font-semibold text-white bg-green-500 rounded hover:bg-green-600 transition-colors flex-shrink-0">Convert</button>
                </div>
                @error('pointsToConvert') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-3">Estimated Amount to be Earned: <span class="font-medium text-slate-700 dark:text-slate-300">{{ number_format(($pointsToConvert > 0 ? $pointsToConvert : 0) * $conversionRate, 2) }} Unit of Money</span></p>
            </div>
        </div>

        {{-- Inventory Items --}}
        <div>
            <div class="flex items-center border-b border-slate-200 dark:border-slate-700">
                <button wire:click="$set('filterType', 'all')" class="px-4 py-2 text-sm font-medium {{ $filterType === 'all' ? 'text-primary border-b-2 border-primary' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">All</button>
                <button wire:click="$set('filterType', 'active')" class="px-4 py-2 text-sm font-medium {{ $filterType === 'active' ? 'text-primary border-b-2 border-primary' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">Active</button>
                <button wire:click="$set('filterType', 'inactive')" class="px-4 py-2 text-sm font-medium {{ $filterType === 'inactive' ? 'text-primary border-b-2 border-primary' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">Inactive</button>
                <button wire:click="$set('filterType', 'expired')" class="px-4 py-2 text-sm font-medium {{ $filterType === 'expired' ? 'text-primary border-b-2 border-primary' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">Expired</button>
            </div>
            <div class="py-6">
                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <div class="relative flex-grow">
                        <input wire:model.debounce.300ms="search" class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md focus:ring-primary focus:border-primary text-slate-800 dark:text-slate-200" placeholder="Search by name or description..." type="text"/>
                    </div>
                    <div class="relative">
                        <select wire:model="sortBy" class="appearance-none w-full md:w-auto pl-4 pr-10 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md focus:ring-primary focus:border-primary text-slate-800 dark:text-slate-200">
                            <option value="name_asc">Sort by: Name (A-Z)</option>
                            <option value="name_desc">Sort by: Name (Z-A)</option>
                            <option value="date_acquired">Sort by: Date Acquired</option>
                            <option value="time_remaining">Sort by: Time Remaining</option>
                            <option value="rarity">Sort by: Rarity</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                    </div>
                </div>

                @if($inventoryItems->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-slate-500 dark:text-slate-400">No items found matching your criteria.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($inventoryItems as $item)
                            <div wire:click="selectItem({{ $item->id }})" class="group relative bg-white dark:bg-slate-800 rounded-lg shadow-sm overflow-hidden border border-transparent dark:hover:border-primary hover:shadow-lg transition-all duration-300 cursor-pointer">
                                <div class="p-5 flex flex-col items-start gap-4">
                                    <div class="flex justify-between items-start w-full">
                                        <div class="w-12 h-12 flex items-center justify-center rounded-lg {{ $item->reward->reward_config['bg_color'] ?? 'bg-gray-100 dark:bg-gray-900/50' }}">
                                            <span class="material-symbols-outlined text-2xl {{ $item->reward->reward_config['icon_color'] ?? 'text-gray-500' }}">{{ $item->reward->reward_config['icon'] ?? 'inventory_2' }}</span>
                                        </div>
                                        @if($item->is_active && (!$item->expires_at || $item->expires_at > now()))
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-300">Active</span>
                                        @elseif($item->expires_at && $item->expires_at <= now())
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-300">Expired</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800 dark:bg-slate-600/30 dark:text-slate-300">Inactive</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-base font-semibold text-slate-800 dark:text-white">{{ $item->reward->name }}</h4>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ Str::limit($item->reward->description, 50) }}</p>
                                    </div>
                                    <div class="w-full text-sm text-slate-500 dark:text-slate-400 flex items-center gap-2 @if(!$item->expires_at) opacity-0 @endif">
                                        <span class="material-icons-outlined text-base">timer</span>
                                        @if($item->expires_at)
                                            @if($item->expires_at > now())
                                                <span>{{ $item->expires_at->diffForHumans(null, true) }} remaining</span>
                                            @else
                                                <span>Expired {{ $item->expires_at->diffForHumans() }}</span>
                                            @endif
                                        @else
                                            <span>-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Item Details Modal --}}
    @if($showItemModal && $selectedItem)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" x-data="{ show: @entangle('showItemModal') }" x-show="show" x-transition.opacity.duration.300ms>
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-2xl w-full max-w-lg m-4" @click.away="show = false">
            <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-slate-800 dark:text-white">"{{ $selectedItem->reward->name }}" Details</h3>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 flex items-center justify-center rounded-lg {{ $selectedItem->reward->reward_config['bg_color'] ?? 'bg-gray-100 dark:bg-gray-900/50' }} flex-shrink-0">
                        <span class="material-symbols-outlined text-4xl {{ $selectedItem->reward->reward_config['icon_color'] ?? 'text-gray-500' }}">{{ $selectedItem->reward->reward_config['icon'] ?? 'inventory_2' }}</span>
                    </div>
                    <div>
                        <p class="text-slate-600 dark:text-slate-300">{{ $selectedItem->reward->description }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                    @if($selectedItem->is_active && (!$selectedItem->expires_at || $selectedItem->expires_at > now()))
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-300">Status: Active</span>
                    @elseif($selectedItem->expires_at && $selectedItem->expires_at <= now())
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-300">Status: Expired</span>
                    @else
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800 dark:bg-slate-600/30 dark:text-slate-300">Status: Inactive</span>
                    @endif
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    @if($selectedItem->expires_at)
                        @if($selectedItem->expires_at > now())
                            Expires in: {{ $selectedItem->expires_at->diffForHumans(null, true) }}
                        @else
                            Expired: {{ $selectedItem->expires_at->diffForHumans() }}
                        @endif
                    @else
                        This item is permanent and does not expire.
                    @endif
                </p>
                {{-- Usage History can be added here if tracked --}}
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 rounded-b-lg flex justify-end gap-3">
                <button wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">Close</button>
                @if(!$selectedItem->is_active && (!$selectedItem->expires_at || $selectedItem->expires_at > now()))
                    <button wire:click="useReward({{ $selectedItem->id }})" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded hover:bg-blue-600 transition-colors">Activate Item</button>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Convert Confirmation Modal --}}
    @if($showConvertModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70" x-data="{ show: @entangle('showConvertModal') }" x-show="show" x-transition.opacity.duration.300ms>
        <div class="bg-slate-900 rounded-lg shadow-2xl w-full max-w-md m-4 border border-slate-700">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-500/10 mb-5">
                    <span class="material-symbols-outlined text-3xl text-green-400">currency_exchange</span>
                </div>
                <h3 class="text-lg font-medium text-white">Confirm Conversion</h3>
                <div class="mt-4 text-sm text-slate-400">
                    <p>Are you sure you want to convert <br/> <span class="font-bold text-lg text-primary">{{ number_format($pointsToConvert) }}</span> points to <span class="font-bold text-lg text-green-400">{{ number_format($pointsToConvert * $conversionRate, 2) }}</span> Unit of Money?</p>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-800/50 rounded-b-lg flex justify-center gap-4">
                <button wire:click="cancelConversion" class="px-6 py-2.5 text-sm font-semibold text-slate-300 bg-slate-700 rounded-md hover:bg-slate-600 transition-colors w-full">Cancel</button>
                <button wire:click="confirmConversion" class="px-6 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors w-full">Confirm Convert</button>
            </div>
        </div>
    </div>
    @endif
</div>
