<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 my-8">
    <div class="xl:col-span-1 bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
        <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Earnings Goal</h3>
        <div class="text-center">
            <div class="relative inline-flex items-center justify-center">
                <svg class="w-32 h-32" viewBox="0 0 120 120">
                    <circle class="stroke-current text-gray-200 dark:text-gray-700" cx="60" cy="60" fill="transparent" r="54" stroke-width="12"></circle>
                    <circle class="stroke-current text-primary -rotate-90 origin-center" cx="60" cy="60" fill="transparent" r="54" stroke-dasharray="339.292" stroke-dashoffset="{{ 339.292 - (339.292 * $goalProgress / 100) }}" stroke-linecap="round" stroke-width="12"></circle>
                </svg>
                <div class="absolute flex flex-col items-center">
                    <span class="text-3xl font-bold text-heading-light dark:text-heading-dark">{{ number_format($goalProgress, 0) }}%</span>
                </div>
            </div>
            <p class="text-lg font-medium text-heading-light dark:text-heading-dark mt-4">${{ number_format($currentEarnings, 2) }} / ${{ number_format($monthlyGoal, 2) }}</p>
            <p class="text-sm text-text-light dark:text-text-dark">of your monthly goal.</p>
            <button wire:click="openGoalModal" wire:loading.attr="disabled" class="mt-4 bg-primary/10 text-primary font-semibold px-4 py-2 rounded-lg hover:bg-primary/20 transition-colors disabled:opacity-75 disabled:cursor-not-allowed flex items-center gap-2 mx-auto">
                <span wire:loading.remove wire:target="openGoalModal">Set New Goal</span>
                <span wire:loading wire:target="openGoalModal" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
            </button>
        </div>
    </div>
    <div class="xl:col-span-2 bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
        <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Top Viewed Countries</h3>
        <div class="space-y-4">
            @forelse ($topCountries as $country)
                <div class="flex items-center gap-4">
                    <div class="flex-grow">
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex items-center gap-2">
                                <span class="fi fi-{{ strtolower($country['iso_code']) }}"></span>
                                <span class="text-heading-light dark:text-heading-dark font-medium">{{ $country['name'] }}</span>
                            </div>
                            <span class="font-bold text-sm text-heading-light dark:text-heading-dark">{{ $country['clicks'] }} Clicks</span>
                        </div>
                        <div class="w-full bg-background-light dark:bg-background-dark rounded-full h-1.5">
                            <div class="bg-primary h-1.5 rounded-full" style="width: {{ $country['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-text-light dark:text-text-dark">No traffic data available yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Goal Setting Modal --}}
    @if($showGoalModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-xl border border-gray-200 bg-background-light dark:border-gray-800 dark:bg-background-dark shadow-xl" @click.away="$wire.closeGoalModal()">
            <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Set Monthly Goal</h3>
                <button wire:click="closeGoalModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Set your monthly earnings target. This will update the progress circle on your dashboard.</p>
                <div class="mb-4">
                    <label for="newGoal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monthly Goal ($)</label>
                    <input type="number" wire:model="newGoal" id="newGoal" class="form-input w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white" step="0.01" min="1">
                    @error('newGoal') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-3">
                    <button wire:click="closeGoalModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button wire:click="saveGoal" wire:loading.attr="disabled" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 disabled:opacity-75 disabled:cursor-not-allowed flex items-center gap-2">
                        <span wire:loading.remove wire:target="saveGoal">Save Goal</span>
                        <span wire:loading wire:target="saveGoal" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                        <span wire:loading wire:target="saveGoal">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
