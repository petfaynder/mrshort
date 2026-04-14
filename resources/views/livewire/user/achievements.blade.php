{{--
    Note: The sections on this page are now dynamically fetched from the backend.
--}}
<div>
    <main class="w-full">
        <div class="layout-content-container flex flex-col w-full max-w-[1200px] flex-1 mx-auto">
            <div class="flex flex-wrap justify-between gap-3 p-4">
                <p class="text-white text-2xl sm:text-4xl font-black leading-tight tracking-[-0.033em]">Achievements Collection</p>
            </div>
            
            @if($featuredGoal)
            <div class="p-4 mb-6">
                <div class="relative rounded-xl border-2 border-primary/50 bg-gradient-to-br from-primary/20 via-[#101922] to-[#101922] p-6 shadow-2xl shadow-primary/20 overflow-hidden">
                    <div class="absolute inset-0 featured-shine animate-shine opacity-50"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                        <div class="flex flex-col items-center text-center">
                            <div class="relative mb-4">
                                <span class="material-symbols-outlined text-primary" style="font-size: 80px;">military_tech</span>
                                <span class="material-symbols-outlined absolute -top-1 -right-1 text-yellow-400 animate-pulse" style="font-size: 32px;">star</span>
                            </div>
                            <div class="bg-primary/20 text-primary px-3 py-1 rounded-full text-sm font-bold">DAILY ACHIEVEMENT</div>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-white text-3xl font-bold leading-tight tracking-tight">{{ $featuredGoal->title }}</h3>
                            <p class="text-slate-300 mt-2">{{ $featuredGoal->description ?? 'Complete this goal to earn special rewards!' }}</p>
                            <div class="mt-4 flex flex-wrap justify-center md:justify-start items-center gap-x-6 gap-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-yellow-400" style="font-size: 20px;">stars</span>
                                    <span class="text-white text-lg font-semibold">{{ $featuredGoal->points ?? 500 }}</span>
                                </div>
                                @if($featuredGoal->coins)
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-400" style="font-size: 20px;">toll</span>
                                    <span class="text-white text-lg font-semibold">{{ $featuredGoal->coins }}</span>
                                </div>
                                @endif
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-400" style="font-size: 20px;">shield</span>
                                    <span class="text-white text-lg font-semibold">{{ ucfirst($featuredGoal->difficulty_level ?? 'Normal') }}</span>
                                </div>
                            </div>
                            {{-- Progress bar --}}
                            <div class="mt-4 flex items-center gap-3">
                                <div class="h-2 flex-1 rounded-full bg-slate-700">
                                    <div class="h-2 rounded-full bg-primary transition-all" style="width: {{ $featuredGoalProgress }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-300">{{ $featuredGoalProgress }}%</span>
                            </div>
                        </div>
                        @if($featuredGoalTimeRemaining)
                        <div class="flex flex-col items-center gap-3 text-center self-center md:self-auto" x-data="{
                            endTime: new Date('{{ $featuredGoalTimeRemaining->toIso8601String() }}'),
                            days: 0, hours: 0, minutes: 0,
                            updateTimer() {
                                const diff = this.endTime - new Date();
                                if (diff > 0) {
                                    this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                    this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                }
                            },
                            init() {
                                this.updateTimer();
                                setInterval(() => this.updateTimer(), 60000);
                            }
                        }">
                            <p class="text-slate-300 text-sm font-medium">Time Remaining</p>
                            <div class="flex items-center gap-2">
                                <div class="flex flex-col items-center">
                                    <div class="bg-slate-900/50 border border-slate-700 backdrop-blur-sm rounded-lg w-16 h-16 flex items-center justify-center">
                                        <span class="text-white text-3xl font-bold tracking-wider" x-text="String(days).padStart(2, '0')">00</span>
                                    </div>
                                    <span class="text-slate-400 text-xs mt-1">DAYS</span>
                                </div>
                                <div class="text-white text-3xl font-bold pb-4">:</div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-slate-900/50 border border-slate-700 backdrop-blur-sm rounded-lg w-16 h-16 flex items-center justify-center">
                                        <span class="text-white text-3xl font-bold tracking-wider" x-text="String(hours).padStart(2, '0')">00</span>
                                    </div>
                                    <span class="text-slate-400 text-xs mt-1">HRS</span>
                                </div>
                                <div class="text-white text-3xl font-bold pb-4">:</div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-slate-900/50 border border-slate-700 backdrop-blur-sm rounded-lg w-16 h-16 flex items-center justify-center">
                                        <span class="text-white text-3xl font-bold tracking-wider" x-text="String(minutes).padStart(2, '0')">00</span>
                                    </div>
                                    <span class="text-slate-400 text-xs mt-1">MIN</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            {{-- Streak Milestones Section --}}
            @if(count($streakMilestones) > 0)
            <div class="p-4 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-orange-400" style="font-size: 28px;">local_fire_department</span>
                        <h3 class="text-white text-xl font-bold">Streak Rewards</h3>
                    </div>
                    <div class="flex items-center gap-2 bg-orange-500/20 px-4 py-2 rounded-full">
                        <span class="material-symbols-outlined text-orange-400">whatshot</span>
                        <span class="text-orange-400 font-bold">{{ $currentStreak }} Day Streak</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    @foreach($streakMilestones as $milestone)
                        <div class="relative rounded-xl p-4 transition-all duration-300 hover:scale-105
                            {{ $milestone['claimed'] 
                                ? 'bg-green-900/30 border border-green-500/40' 
                                : ($milestone['reachable'] 
                                    ? 'bg-orange-900/30 border-2 border-orange-500/60 shadow-lg shadow-orange-500/20' 
                                    : 'bg-slate-800/50 border border-slate-700') }}">
                            
                            {{-- Days Badge --}}
                            <div class="text-center mb-3">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full mb-2
                                    {{ $milestone['claimed'] 
                                        ? 'bg-green-500/20 text-green-400' 
                                        : ($milestone['reachable'] 
                                            ? 'bg-orange-500/20 text-orange-400 animate-pulse' 
                                            : 'bg-slate-700/50 text-slate-500') }}">
                                    <span class="text-2xl">🔥</span>
                                </div>
                                <div class="text-2xl font-black {{ $milestone['claimed'] ? 'text-green-400' : ($milestone['reachable'] ? 'text-orange-400' : 'text-slate-400') }}">
                                    {{ $milestone['days'] }}
                                </div>
                                <div class="text-xs text-slate-500 font-medium">DAYS</div>
                            </div>
                            
                            {{-- Reward Info --}}
                            <div class="text-center text-sm mb-3">
                                <div class="text-white font-semibold">+{{ number_format($milestone['points']) }}</div>
                                <div class="text-slate-400 text-xs">Points</div>
                                @if($milestone['bonus_type'] === 'streak_freeze')
                                    <div class="text-cyan-400 text-xs mt-1">+{{ $milestone['bonus_value'] }} Streak Freeze 🧊</div>
                                @elseif($milestone['bonus_type'] === 'xp_boost')
                                    <div class="text-purple-400 text-xs mt-1">+{{ $milestone['bonus_value'] }}% XP Boost 🚀</div>
                                @endif
                            </div>
                            
                            {{-- Status / Claim Button --}}
                            @if($milestone['claimed'])
                                <div class="flex items-center justify-center gap-1 text-green-400 text-xs font-bold">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">check_circle</span>
                                    <span>Claimed</span>
                                </div>
                            @elseif($milestone['reachable'])
                                <button 
                                    wire:click="claimStreakMilestone({{ $milestone['id'] }})"
                                    wire:loading.attr="disabled"
                                    class="w-full py-2 bg-orange-500 hover:bg-orange-400 text-white text-xs font-bold rounded-lg transition shadow-lg shadow-orange-500/30">
                                    <span wire:loading.remove wire:target="claimStreakMilestone({{ $milestone['id'] }})">Claim Reward</span>
                                    <span wire:loading wire:target="claimStreakMilestone({{ $milestone['id'] }})" class="material-symbols-outlined animate-spin" style="font-size: 14px;">progress_activity</span>
                                </button>
                            @else
                                <div class="w-full">
                                    <div class="h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-slate-500 transition-all duration-500" style="width: {{ $milestone['progress'] }}%"></div>
                                    </div>
                                    <div class="text-center text-xs text-slate-500 mt-1">%{{ $milestone['progress'] }}</div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
                <div class="flex flex-col gap-4 p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-white text-2xl font-bold tracking-tight">Special Achievement Collections</h3>
                    </div>
                    @if(count($goalCollections) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($goalCollections as $collection)
                        @php
                            $colorClasses = match($collection['color']) {
                                'primary' => ['bg' => 'bg-primary/20', 'text' => 'text-primary', 'progress' => 'bg-primary'],
                                'green' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'progress' => 'bg-green-500'],
                                'purple' => ['bg' => 'bg-purple-500/20', 'text' => 'text-purple-400', 'progress' => 'bg-purple-500'],
                                'yellow' => ['bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-400', 'progress' => 'bg-yellow-500'],
                                default => ['bg' => 'bg-slate-500/20', 'text' => 'text-slate-400', 'progress' => 'bg-slate-500'],
                            };
                        @endphp
                        <div class="flex flex-col gap-4 rounded-xl border border-slate-700 bg-[#233648]/50 p-6 hover:bg-[#233648] transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="flex size-12 items-center justify-center rounded-lg {{ $colorClasses['bg'] }} {{ $colorClasses['text'] }}">
                                    <span class="material-symbols-outlined" style="font-size: 32px;">{{ $collection['icon'] }}</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-white">{{ $collection['name'] }}</h4>
                                    <p class="text-sm text-slate-400">{{ $collection['description'] }}</p>
                                </div>
                                @if($collection['completed'] === $collection['total'])
                                <div class="flex items-center gap-2 text-sm font-semibold text-yellow-400">
                                    <span class="material-symbols-outlined">emoji_events</span>
                                    <span>Completed!</span>
                                </div>
                                @else
                                <div class="flex items-center gap-2 text-sm font-semibold text-slate-400 opacity-60">
                                    <span class="material-symbols-outlined">lock</span>
                                    <span>In Progress</span>
                                </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="h-2 flex-1 rounded-full bg-slate-700">
                                    <div class="h-2 rounded-full {{ $colorClasses['progress'] }} transition-all" style="width: {{ $collection['progress'] }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-300">{{ $collection['completed'] }} / {{ $collection['total'] }}</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($collection['goals'] as $goal)
                                <div class="relative size-10 rounded-lg border-2 {{ $goal['completed'] ? 'border-'.$collection['color'].'-400 bg-[#233648]' : 'border-slate-600 bg-[#192532] locked-card' }}" title="{{ $goal['title'] }}">
                                    <div class="flex h-full w-full items-center justify-center">
                                        @if($goal['completed'])
                                        <span class="material-symbols-outlined {{ $colorClasses['text'] }}" style="font-size: 18px;">check_circle</span>
                                        @else
                                        <span class="material-symbols-outlined text-slate-500" style="font-size: 18px;">radio_button_unchecked</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-slate-400">
                        <span class="material-symbols-outlined mb-2" style="font-size: 48px;">collections_bookmark</span>
                        <p>No achievement collections available yet.</p>
                    </div>
                    @endif
                </div>
            <div class="flex gap-2 p-3 overflow-x-auto">
                @foreach(['all' => 'All', 'daily' => 'Daily', 'weekly' => 'Weekly', 'one_time' => 'Career', 'social' => 'Social', 'economic' => 'Economy', 'discovery' => 'Discovery'] as $categoryKey => $categoryName)
                    <div wire:click="$set('filterCategory', '{{ $categoryKey }}')"
                         class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg px-4
                                {{ $filterCategory === $categoryKey ? 'bg-primary/20 border border-primary' : 'bg-[#233648] hover:bg-primary/20' }}">
                        <span wire:loading wire:target="$set('filterCategory', '{{ $categoryKey }}')" class="material-symbols-outlined text-sm animate-spin {{ $filterCategory === $categoryKey ? 'text-primary' : 'text-slate-300' }}">progress_activity</span>
                        <p class="{{ $filterCategory === $categoryKey ? 'text-primary text-sm font-bold' : 'text-slate-300 hover:text-white text-sm font-medium' }} leading-normal">{{ $categoryName }}</p>
                    </div>
                @endforeach
            </div>

            @if($goals->isEmpty())
                <div class="p-4 text-center text-slate-400">
                    <p>No achievements to display in this category yet.</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 p-4">
                    @foreach($goals as $goal)
                        @php
                            $isCompleted = $goal->userAchievement && $goal->userAchievement->completed_at;
                            $currentValue = $goal->userAchievement ? $goal->userAchievement->current_value : 0;
                            $progress = $goal->target_value > 0 ? ($currentValue / $goal->target_value) * 100 : 0;
                            $progress = min(100, $progress);

                            $rarity = 'common';
                            if ($goal->points >= 1000) {
                                $rarity = 'legendary';
                            } elseif ($goal->points >= 250) {
                                $rarity = 'rare';
                            }

                            $cardClasses = 'flex flex-col gap-3 rounded-xl p-4 aspect-[3/4] transition-transform duration-300 hover:scale-105 cursor-pointer ';
                            if ($isCompleted) {
                                switch ($rarity) {
                                    case 'legendary':
                                        $cardClasses .= 'bg-[#233648] border-2 border-amber-400 achieved-glow-legendary animate-pulse-gold';
                                        break;
                                    case 'rare':
                                        $cardClasses .= 'bg-[#233648] border-2 border-slate-300 achieved-glow-rare';
                                        break;
                                    default:
                                        $cardClasses .= 'bg-[#233648] border-2 border-blue-400 achieved-glow-common';
                                }
                            } else {
                                $cardClasses .= 'bg-[#192532] border-2 border-slate-600 locked-card';
                                if ($rarity === 'legendary') {
                                    $cardClasses .= ' animate-pulse-gold';
                                }
                            }

                            $iconColor = 'text-slate-500';
                            if ($isCompleted) {
                                switch ($rarity) {
                                    case 'legendary':
                                        $iconColor = 'text-amber-400';
                                        break;
                                    case 'rare':
                                        $iconColor = 'text-slate-300';
                                        break;
                                    default:
                                        $iconColor = 'text-blue-400';
                                }
                            }

                            $icon = 'emoji_events';
                            switch ($goal->category) {
                                case 'daily': $icon = 'calendar_month'; break;
                                case 'weekly': $icon = 'date_range'; break;
                                case 'one_time': $icon = 'workspace_premium'; break;
                                case 'social': $icon = 'share'; break;
                                case 'economic': $icon = 'trending_up'; break;
                                case 'discovery': $icon = 'travel_explore'; break;
                            }
                        @endphp

                        <div class="{{ $cardClasses }}">
                            <div class="flex-1 flex items-center justify-center relative">
                                <span class="material-symbols-outlined {{ $iconColor }}" style="font-size: 64px;">{{ $icon }}</span>
                                @if(!$isCompleted)
                                    <span class="material-symbols-outlined text-slate-400 absolute" style="font-size: 32px;">lock</span>
                                @endif
                            </div>
                            <div class="text-center">
                                <p class="{{ $isCompleted ? 'text-white' : 'text-slate-300' }} text-lg font-bold leading-tight">{{ $goal->title }}</p>
                                <p class="{{ $isCompleted ? 'text-slate-400' : 'text-slate-500' }} text-xs mt-1">{{ $goal->description }}</p>
                            </div>
                            <div class="flex justify-center items-center gap-4 mt-2">
                                @if($goal->points > 0)
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined {{ $isCompleted ? 'text-yellow-400' : 'text-slate-500' }}" style="font-size: 16px;">stars</span>
                                    <span class="{{ $isCompleted ? 'text-white' : 'text-slate-400' }} text-sm font-semibold">{{ $goal->points }}</span>
                                </div>
                                @endif
                                @if($goal->coins > 0)
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined {{ $isCompleted ? 'text-green-400' : 'text-slate-500' }}" style="font-size: 16px;">toll</span>
                                    <span class="{{ $isCompleted ? 'text-white' : 'text-slate-400' }} text-sm font-semibold">{{ $goal->coins }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="flex flex-col items-center gap-1 mt-auto pt-2">
                                @if($isCompleted)
                                    @if($goal->reward)
                                        <p class="text-xs text-yellow-400 font-bold">Reward: {{ $goal->reward->name }}</p>
                                    @endif
                                    <div class="text-green-400 text-xs font-bold flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">check_circle</span>
                                        <span>{{ $goal->userAchievement->completed_at->format('d/m/Y') }}</span>
                                    </div>
                                @else
                                    <div class="w-full px-2">
                                        <div class="h-1.5 w-full rounded-full bg-slate-700">
                                            <div class="h-1.5 rounded-full bg-slate-500" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <p class="text-slate-400 text-xs mt-1 font-semibold">{{ $currentValue }} / {{ $goal->target_value }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</div>
