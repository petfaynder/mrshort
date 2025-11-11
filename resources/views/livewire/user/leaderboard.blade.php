<div class="p-6 md:p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-center mb-8">
            <div class="bg-surface-light dark:bg-surface-dark p-1.5 rounded-lg shadow-sm flex items-center gap-2">
                <button wire:click="$set('filter', 'all_time')" class="px-4 py-2 text-sm font-semibold rounded-md {{ $filter === 'all_time' ? 'bg-primary text-white' : 'text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors">Tüm Zamanlar</button>
                <button wire:click="$set('filter', 'monthly')" class="px-4 py-2 text-sm font-semibold rounded-md {{ $filter === 'monthly' ? 'bg-primary text-white' : 'text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors">Aylık</button>
                <button wire:click="$set('filter', 'weekly')" class="px-4 py-2 text-sm font-semibold rounded-md {{ $filter === 'weekly' ? 'bg-primary text-white' : 'text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors">Haftalık</button>
            </div>
        </div>

        @if($topThree && $topThree->count() > 0)
            <div class="grid grid-cols-3 gap-4 md:gap-8 items-end mb-12">
                {{-- 2nd Place --}}
                @if(isset($topThree[1]))
                    <div class="bg-surface-light dark:bg-surface-dark p-4 rounded-xl border border-border-light dark:border-border-dark shadow-md text-center flex flex-col items-center transform hover:scale-105 transition-transform duration-300">
                        <span class="text-4xl font-extrabold text-slate-300 dark:text-slate-600 mb-2">2.</span>
                        <img alt="Avatar for {{ $topThree[1]->name }}" class="w-20 h-20 rounded-full border-4 border-slate-300 dark:border-slate-600 mb-3" src="{{ $topThree[1]->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($topThree[1]->name) }}"/>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $topThree[1]->name }}</h3>
                        <p class="text-sm text-primary font-semibold">Puan: {{ number_format($topThree[1]->gamification_points) }}</p>
                        <p class="text-xs text-muted-light dark:text-muted-dark">Seviye: {{ $topThree[1]->level ?? 1 }}</p>
                    </div>
                @endif

                {{-- 1st Place --}}
                @if(isset($topThree[0]))
                    <div class="bg-surface-light dark:bg-surface-dark p-4 rounded-xl border-2 border-amber-400 dark:border-amber-400 shadow-lg text-center flex flex-col items-center order-first md:order-none transform scale-110 hover:scale-115 transition-transform duration-300 relative">
                        <div class="absolute -top-4 text-3xl">👑</div>
                        <span class="text-5xl font-extrabold text-amber-400 mb-2">1.</span>
                        <img alt="Avatar for {{ $topThree[0]->name }}" class="w-24 h-24 rounded-full border-4 border-amber-400 mb-3" src="{{ $topThree[0]->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($topThree[0]->name) }}"/>
                        <h3 class="font-bold text-xl text-slate-800 dark:text-white">{{ $topThree[0]->name }}</h3>
                        <p class="text-md text-primary font-semibold">Puan: {{ number_format($topThree[0]->gamification_points) }}</p>
                        <p class="text-sm text-muted-light dark:text-muted-dark">Seviye: {{ $topThree[0]->level ?? 1 }}</p>
                    </div>
                @endif

                {{-- 3rd Place --}}
                @if(isset($topThree[2]))
                    <div class="bg-surface-light dark:bg-surface-dark p-4 rounded-xl border border-border-light dark:border-border-dark shadow-md text-center flex flex-col items-center transform hover:scale-105 transition-transform duration-300">
                        <span class="text-4xl font-extrabold text-orange-400/70 dark:text-orange-700/80 mb-2">3.</span>
                        <img alt="Avatar for {{ $topThree[2]->name }}" class="w-20 h-20 rounded-full border-4 border-orange-400/50 dark:border-orange-700/60 mb-3" src="{{ $topThree[2]->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($topThree[2]->name) }}"/>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $topThree[2]->name }}</h3>
                        <p class="text-sm text-primary font-semibold">Puan: {{ number_format($topThree[2]->gamification_points) }}</p>
                        <p class="text-xs text-muted-light dark:text-muted-dark">Seviye: {{ $topThree[2]->level ?? 1 }}</p>
                    </div>
                @endif
            </div>

            @if($leaderboard->count() > 0)
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm">
                    <div class="space-y-2 p-4">
                        @foreach($leaderboard as $index => $user)
                            @php $rank = $topThree->count() + $leaderboard->firstItem() + $index; @endphp
                            @if(Auth::id() == $user->id)
                                {{-- Current User's Rank --}}
                                <div class="flex items-center p-3 rounded-lg bg-primary/10 border border-primary/20">
                                    <span class="w-8 text-center font-bold text-primary">{{ $rank }}</span>
                                    <img alt="Your avatar" class="w-10 h-10 rounded-full mx-4 border-2 border-primary" src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"/>
                                    <div class="flex-1">
                                        <p class="font-semibold text-primary">{{ $user->name }} (You)</p>
                                        <p class="text-xs text-primary/80">Seviye: {{ $user->level ?? 1 }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-primary">{{ number_format($user->gamification_points) }}</p>
                                        <p class="text-xs text-primary/80">Puan</p>
                                    </div>
                                </div>
                            @else
                                {{-- Other Users --}}
                                <div class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <span class="w-8 text-center font-bold text-muted-light dark:text-muted-dark">{{ $rank }}</span>
                                    <img alt="Avatar for {{ $user->name }}" class="w-10 h-10 rounded-full mx-4" src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"/>
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-800 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-muted-light dark:text-muted-dark">Seviye: {{ $user->level ?? 1 }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-primary">{{ number_format($user->gamification_points) }}</p>
                                        <p class="text-xs text-muted-light dark:text-muted-dark">Puan</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="p-4 border-t border-border-light dark:border-border-dark">
                        {{ $leaderboard->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <p class="text-muted-light dark:text-muted-dark">Liderlik tablosunda gösterilecek veri yok.</p>
            </div>
        @endif
    </div>
</div>