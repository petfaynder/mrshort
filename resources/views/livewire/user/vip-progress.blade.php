<div class="vip-progress-container">
    <div class="bg-gradient-to-br from-amber-900/40 to-yellow-900/40 rounded-2xl p-6 border border-amber-500/20">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div class="flex items-center gap-3">
                <span class="text-4xl">{{ $currentLevel?->icon ?? '🥉' }}</span>
                <div>
                    <h3 class="text-xl font-bold text-white">VIP Level</h3>
                    <p class="text-amber-300 text-lg font-semibold">{{ $currentLevel?->name ?? 'Bronze' }}</p>
                </div>
            </div>
            <div class="text-left md:text-right">
                <div class="text-sm text-gray-400">This Month's Earnings</div>
                <div class="text-3xl font-bold text-amber-400">${{ number_format($currentEarnings, 2) }}</div>
            </div>
        </div>

        @if($currentLevel)
            <!-- Current Benefits -->
            <div class="bg-gray-800/50 rounded-xl p-4 mb-6">
                <h4 class="text-sm font-semibold text-gray-400 mb-3">Active Benefits</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($currentLevel->benefits_list as $benefit)
                        <span class="px-3 py-1.5 bg-amber-600/20 text-amber-300 text-sm rounded-full flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            {{ $benefit }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Progress to Next Level -->
        @if($nextLevel)
            <div class="mb-6">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-400">Next level: <span class="text-white font-semibold">{{ $nextLevel->name }}</span></span>
                    <span class="text-amber-400">${{ number_format($nextLevel->min_earnings - $currentEarnings, 2) }} remaining</span>
                </div>
                <div class="h-4 bg-gray-700 rounded-full overflow-hidden">
                    <div 
                        class="h-full bg-gradient-to-r from-amber-500 to-yellow-500 transition-all duration-500"
                        style="width: {{ $progressPercent }}%"
                    ></div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-purple-600/20 to-pink-600/20 rounded-xl p-4 mb-6 text-center">
                <span class="text-3xl">👑</span>
                <p class="text-purple-300 font-semibold mt-2">You've reached the maximum level!</p>
            </div>
        @endif

        <!-- All VIP Levels -->
        <div>
            <h4 class="text-sm font-semibold text-gray-400 mb-3">All VIP Levels</h4>
            <div class="grid gap-2">
                @foreach($allLevels as $level)
                    @php
                        $isCurrentLevel = $currentLevel && $currentLevel->id === $level['id'];
                        $isAchieved = $currentLevel && $level['order'] <= $currentLevel->order;
                    @endphp
                    <div class="flex items-center justify-between p-4 rounded-lg transition {{ $isCurrentLevel ? 'bg-amber-600/20 border border-amber-500/30' : ($isAchieved ? 'bg-gray-700/50' : 'bg-gray-800/30') }}">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl {{ !$isAchieved ? 'opacity-40 grayscale' : '' }}">{{ $level['icon'] ?? '⭐' }}</span>
                            <div>
                                <div class="font-semibold {{ $isCurrentLevel ? 'text-amber-400' : ($isAchieved ? 'text-white' : 'text-gray-500') }}">
                                    {{ $level['name'] }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    @if($level['max_earnings'])
                                        ${{ number_format($level['min_earnings']) }} - ${{ number_format($level['max_earnings']) }}
                                    @else
                                        ${{ number_format($level['min_earnings']) }}+
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            @if($level['cpm_bonus_percent'] > 0)
                                <span class="text-green-400 hidden sm:inline">+{{ $level['cpm_bonus_percent'] }}% CPM</span>
                            @endif
                            @if($level['spin_extra'] > 0)
                                <span class="text-blue-400 hidden sm:inline">+{{ $level['spin_extra'] }} Spin</span>
                            @endif
                            @if($isCurrentLevel)
                                <span class="px-2 py-1 bg-amber-500 text-white text-xs font-bold rounded">ACTIVE</span>
                            @elseif($isAchieved)
                                <span class="material-symbols-outlined text-green-400">check_circle</span>
                            @else
                                <span class="material-symbols-outlined text-gray-600">lock</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Info -->
        <div class="mt-6 p-4 bg-gray-800/30 rounded-xl text-sm text-gray-400">
            <p class="flex items-start gap-2">
                <span class="material-symbols-outlined text-amber-400">info</span>
                <span>Your VIP level resets at the beginning of each month. If you were Diamond the previous month, you start at Silver; if Platinum, you start at Bronze.</span>
            </p>
        </div>
    </div>
</div>
