<div class="weekly-competition-widget">
    @if($competition)
        <div class="bg-gradient-to-br from-amber-900/30 to-orange-900/30 rounded-xl p-5 border border-amber-500/20">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🏆</span>
                    <div>
                        <h3 class="text-lg font-bold text-white">{{ $competition->title }}</h3>
                        <p class="text-xs text-gray-400">{{ $competition->type_label }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400">Bitiş</div>
                    <div class="text-sm font-semibold text-amber-400">
                        {{ $competition->end_date->format('d.m H:i') }}
                    </div>
                </div>
            </div>

            <!-- Countdown -->
            <div class="mb-4 text-center">
                <div 
                    class="inline-flex items-center gap-2 bg-gray-800/50 rounded-lg px-4 py-2"
                    x-data="{ 
                        endTime: new Date('{{ $competition->end_date->toIso8601String() }}').getTime(),
                        remaining: '',
                        init() {
                            this.updateCountdown();
                            setInterval(() => this.updateCountdown(), 1000);
                        },
                        updateCountdown() {
                            const now = new Date().getTime();
                            const distance = this.endTime - now;
                            if (distance < 0) {
                                this.remaining = 'Bitti!';
                                return;
                            }
                            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            this.remaining = days + 'g ' + hours + 's ' + minutes + 'd ' + seconds + 's';
                        }
                    }"
                >
                    <span class="text-gray-400">⏱️</span>
                    <span class="text-amber-400 font-mono" x-text="remaining"></span>
                </div>
            </div>

            <!-- Your Position -->
            @if($userEntry)
                <div class="mb-4 bg-gradient-to-r from-amber-600/20 to-orange-600/20 rounded-lg p-3 border border-amber-500/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold text-amber-400">#{{ $userRank ?? '-' }}</span>
                            <span class="text-gray-300">Sıralamanız</span>
                        </div>
                        <div class="text-right">
                            <div class="text-xl font-bold text-white">{{ number_format($userEntry->score) }}</div>
                            <div class="text-xs text-gray-400">{{ $competition->type === 'clicks' ? 'tıklama' : 'puan' }}</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="mb-4 bg-gray-800/50 rounded-lg p-3 text-center">
                    <p class="text-gray-400 text-sm">Yarışmaya katılmak için link kısaltın ve tıklama alın!</p>
                </div>
            @endif

            <!-- Leaderboard -->
            <div class="space-y-2">
                <h4 class="text-sm font-semibold text-gray-400 mb-2">Top 10</h4>
                @forelse($leaderboardData ?? [] as $index => $entry)
                    <div class="flex items-center justify-between p-2 rounded-lg {{ $entry->user_id === auth()->id() ? 'bg-amber-600/20 border border-amber-500/30' : 'bg-gray-800/30' }}">
                        <div class="flex items-center gap-3">
                            <span class="w-8 text-center font-bold {{ $index < 3 ? 'text-amber-400' : 'text-gray-500' }}">
                                @if($index === 0) 🥇
                                @elseif($index === 1) 🥈
                                @elseif($index === 2) 🥉
                                @else {{ $index + 1 }}
                                @endif
                            </span>
                            <div class="flex items-center gap-2">
                                @if($entry->user->avatar)
                                    <img src="{{ $entry->user->avatar }}" class="w-6 h-6 rounded-full" alt="">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-xs">
                                        {{ substr($entry->user->name ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-sm {{ $entry->user_id === auth()->id() ? 'text-amber-400 font-semibold' : 'text-gray-300' }}">
                                    {{ Str::limit($entry->user->name ?? 'Kullanıcı', 15) }}
                                </span>
                            </div>
                        </div>
                        <span class="font-semibold text-white">{{ number_format($entry->score) }}</span>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-4">
                        Henüz katılımcı yok
                    </div>
                @endforelse
            </div>

            <!-- Prize Info -->
            <div class="mt-4 pt-4 border-t border-gray-700">
                <button 
                    class="w-full text-center text-sm text-gray-400 hover:text-amber-400 transition"
                    x-data="{ open: false }"
                    @click="open = !open"
                >
                    <span x-show="!open">🎁 Ödülleri Gör</span>
                    <span x-show="open">🎁 Ödülleri Gizle</span>
                    <div x-show="open" class="mt-3 space-y-1 text-left">
                        @foreach($competition->prize_structure ?? [] as $prize)
                            <div class="flex justify-between text-xs">
                                <span>
                                    @if(isset($prize['rank_to']) && $prize['rank_to'])
                                        {{ $prize['rank'] }}-{{ $prize['rank_to'] }}. sıra
                                    @else
                                        {{ $prize['rank'] }}. sıra
                                    @endif
                                </span>
                                <span class="text-amber-400">{{ number_format($prize['points']) }} puan</span>
                            </div>
                        @endforeach
                    </div>
                </button>
            </div>
        </div>
    @else
        <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 rounded-xl p-5 border border-gray-700">
            <div class="text-center text-gray-400">
                <span class="text-4xl mb-2 block">🏆</span>
                <p>Şu anda aktif yarışma yok</p>
                <p class="text-xs mt-1">Yakında yeni yarışmalar başlayacak!</p>
            </div>
        </div>
    @endif
</div>
