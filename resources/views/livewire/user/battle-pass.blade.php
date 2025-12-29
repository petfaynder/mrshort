<div class="battle-pass-container">
    @if($season)
        <div class="bg-gradient-to-br from-purple-900/40 to-indigo-900/40 rounded-2xl p-6 border border-purple-500/20">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div class="flex items-center gap-4">
                    @if($season->image_path)
                        <img src="{{ Storage::url($season->image_path) }}" alt="{{ $season->name }}" class="w-16 h-16 rounded-xl object-cover">
                    @else
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center">
                            <span class="text-3xl">🏆</span>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $season->name }}</h2>
                        @if($season->theme)
                            <p class="text-purple-300 text-sm">{{ $season->theme }}</p>
                        @endif
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <div class="text-sm text-gray-400">Time Remaining</div>
                    <div class="text-xl font-bold text-purple-400">{{ $season->days_remaining }} days</div>
                    <div class="h-1.5 w-24 bg-gray-700 rounded-full mt-1">
                        <div class="h-full bg-purple-500 rounded-full" style="width: {{ $season->progress_percent }}%"></div>
                    </div>
                </div>
            </div>

            @if($progress)
                <!-- User Progress -->
                <div class="bg-gray-800/50 rounded-xl p-4 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-gradient-to-br {{ $progress->has_premium ? 'from-amber-500 to-orange-600' : 'from-gray-600 to-gray-700' }} flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">{{ $progress->current_level }}</span>
                            </div>
                            <div>
                                <div class="text-white font-semibold">Level {{ $progress->current_level }}</div>
                                <div class="text-sm text-gray-400">{{ number_format($progress->xp) }} XP</div>
                            </div>
                        </div>
                        @if(!$progress->has_premium)
                            <button 
                                wire:click="openUpgradeModal"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-700 transition flex items-center gap-2 disabled:opacity-50"
                            >
                                <span wire:loading.remove wire:target="openUpgradeModal" class="material-symbols-outlined text-sm">star</span>
                                <span wire:loading wire:target="openUpgradeModal" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                                Get Premium
                            </button>
                        @else
                            <div class="flex items-center gap-2 text-amber-400">
                                <span class="material-symbols-outlined">verified</span>
                                <span class="font-semibold">Premium Active</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- XP Progress Bar -->
                    <div>
                        <div class="h-3 bg-gray-700 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-gradient-to-r from-purple-500 to-pink-500 transition-all duration-500"
                                style="width: {{ $progress->level_progress }}%"
                            ></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Level {{ $progress->current_level }}</span>
                            <span>{{ $progress->xp_to_next_level }} XP remaining</span>
                            <span>Level {{ $progress->current_level + 1 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Rewards Track -->
                <div class="overflow-x-auto pb-4 -mx-2 px-2">
                    <div class="flex gap-4" style="min-width: max-content;">
                        @for($level = 1; $level <= min($season->max_level, 30); $level++)
                            @php
                                $levelRewards = $season->rewards->where('level', $level);
                                $freeReward = $levelRewards->where('is_premium', false)->first();
                                $premiumReward = $levelRewards->where('is_premium', true)->first();
                                $isUnlocked = $progress->current_level >= $level;
                                $canClaimFree = $isUnlocked && $freeReward && !$progress->hasClaimedReward($freeReward->id);
                                $canClaimPremium = $isUnlocked && $premiumReward && $progress->has_premium && !$progress->hasClaimedReward($premiumReward->id);
                            @endphp
                            
                            <div class="flex flex-col items-center" style="width: 72px;">
                                <!-- Premium Reward -->
                                <div class="relative mb-2">
                                    @if($premiumReward)
                                        <div 
                                            class="w-14 h-14 rounded-xl flex items-center justify-center transition-all relative
                                                {{ $isUnlocked && $progress->has_premium 
                                                    ? ($progress->hasClaimedReward($premiumReward->id) 
                                                        ? 'bg-amber-600/30 border-2 border-amber-500/50' 
                                                        : 'bg-gradient-to-br from-amber-500 to-orange-600 hover:scale-110 cursor-pointer') 
                                                    : 'bg-gray-700/50 border border-gray-600' }}"
                                            @if($canClaimPremium) 
                                                wire:click="claimReward({{ $premiumReward->id }})"
                                                wire:loading.attr="disabled"
                                            @endif
                                            title="{{ $premiumReward->display_text }}"
                                        >
                                            <span class="text-xl">{{ $premiumReward->reward_icon ?? '🎁' }}</span>
                                            @if(!$progress->has_premium)
                                                <div class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-xl">
                                                    <span class="material-symbols-outlined text-gray-400 text-sm">lock</span>
                                                </div>
                                            @endif
                                            @if($progress->hasClaimedReward($premiumReward->id))
                                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-white" style="font-size: 10px;">check</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="absolute -top-1 -left-1 w-4 h-4 bg-amber-500 rounded flex items-center justify-center">
                                            <span style="font-size: 8px;">⭐</span>
                                        </div>
                                    @else
                                        <div class="w-14 h-14"></div>
                                    @endif
                                </div>
                                
                                <!-- Level Number -->
                                <div class="w-7 h-7 rounded-full {{ $isUnlocked ? 'bg-purple-600' : 'bg-gray-700' }} flex items-center justify-center text-white text-xs font-bold mb-2">
                                    {{ $level }}
                                </div>
                                
                                <!-- Free Reward -->
                                <div class="relative">
                                    @if($freeReward)
                                        <div 
                                            class="w-14 h-14 rounded-xl flex items-center justify-center transition-all relative
                                                {{ $isUnlocked 
                                                    ? ($progress->hasClaimedReward($freeReward->id) 
                                                        ? 'bg-purple-600/30 border-2 border-purple-500/50' 
                                                        : 'bg-gradient-to-br from-purple-500 to-indigo-600 hover:scale-110 cursor-pointer') 
                                                    : 'bg-gray-700/50 border border-gray-600' }}"
                                            @if($canClaimFree) 
                                                wire:click="claimReward({{ $freeReward->id }})"
                                                wire:loading.attr="disabled"
                                            @endif
                                            title="{{ $freeReward->display_text }}"
                                        >
                                            <span class="text-xl">{{ $freeReward->reward_icon ?? '🎁' }}</span>
                                            @if(!$isUnlocked)
                                                <div class="absolute inset-0 flex items-center justify-center bg-black/30 rounded-xl">
                                                    <span class="material-symbols-outlined text-gray-400 text-sm">lock</span>
                                                </div>
                                            @endif
                                            @if($progress->hasClaimedReward($freeReward->id))
                                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-white" style="font-size: 10px;">check</span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="w-14 h-14"></div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Legend -->
                <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-gray-700">
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <div class="w-4 h-4 bg-amber-500 rounded"></div>
                        <span>Premium Reward</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <div class="w-4 h-4 bg-purple-600 rounded"></div>
                        <span>Free Reward</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <div class="w-4 h-4 bg-gray-700 rounded border border-gray-600"></div>
                        <span>Locked</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-white" style="font-size: 10px;">check</span>
                        </div>
                        <span>Claimed</span>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-400">Please log in to track the season</p>
                </div>
            @endif
        </div>

        <!-- Premium Upgrade Modal -->
        @if($showUpgradeModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" wire:click.self="closeUpgradeModal">
                <div class="bg-gray-800 rounded-2xl p-6 max-w-md w-full mx-4 border border-purple-500/30">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center mb-4">
                            <span class="text-4xl">⭐</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Premium Battle Pass</h3>
                        <p class="text-gray-400 mt-2">Unlock all premium rewards!</p>
                    </div>
                    
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center gap-3 text-gray-300">
                            <span class="material-symbols-outlined text-amber-400">check_circle</span>
                            2x rewards at every level
                        </li>
                        <li class="flex items-center gap-3 text-gray-300">
                            <span class="material-symbols-outlined text-amber-400">check_circle</span>
                            Exclusive badges and avatars
                        </li>
                        <li class="flex items-center gap-3 text-gray-300">
                            <span class="material-symbols-outlined text-amber-400">check_circle</span>
                            Premium profile themes
                        </li>
                        <li class="flex items-center gap-3 text-gray-300">
                            <span class="material-symbols-outlined text-amber-400">check_circle</span>
                            Guaranteed Diamond Box at season end
                        </li>
                    </ul>
                    
                    <div class="bg-gray-700/50 rounded-xl p-4 mb-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-amber-400">{{ number_format($season->premium_price_points) }} Points</div>
                            <div class="text-sm text-gray-400">or ${{ number_format($season->premium_price_money, 2) }}</div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button 
                            wire:click="closeUpgradeModal"
                            class="flex-1 px-4 py-3 bg-gray-700 text-gray-300 font-semibold rounded-lg hover:bg-gray-600 transition"
                        >
                            Cancel
                        </button>
                        <button 
                            wire:click="upgradeToPremium"
                            wire:loading.attr="disabled"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-700 transition disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="upgradeToPremium">Purchase</span>
                            <span wire:loading wire:target="upgradeToPremium">Processing...</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 rounded-2xl p-8 border border-gray-700 text-center">
            <span class="text-5xl mb-4 block">🏆</span>
            <h3 class="text-xl font-bold text-white mb-2">No active season at the moment</h3>
            <p class="text-gray-400">A new season will start soon!</p>
        </div>
    @endif
</div>
