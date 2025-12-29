<div class="daily-spin-container">
    @if(!$spinEnabled)
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-6 text-center">
            <x-heroicon-o-exclamation-triangle class="w-12 h-12 text-yellow-500 mx-auto mb-3"/>
            <p class="text-yellow-500">Daily Spin Wheel is currently disabled.</p>
        </div>
    @else
        <!-- Spin Wheel Card -->
        <div class="bg-gradient-to-br from-purple-900/50 via-indigo-900/50 to-blue-900/50 rounded-2xl p-6 border border-purple-500/30 shadow-2xl">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white mb-2 flex items-center justify-center gap-2">
                    <span class="text-3xl">🎰</span> Daily Spin Wheel
                </h2>
                <p class="text-gray-400">Spin the wheel once a day and win rewards!</p>
            </div>

            <!-- Wheel Container -->
            <div class="relative flex justify-center items-center mb-6">
                <!-- Pointer -->
                <div class="absolute -top-2 z-20">
                    <div class="w-0 h-0 border-l-[20px] border-r-[20px] border-t-[35px] border-l-transparent border-r-transparent border-t-yellow-400 drop-shadow-lg filter drop-shadow-[0_0_10px_rgba(250,204,21,0.5)]"></div>
                </div>

                <!-- Wheel -->
                <div class="relative">
                    <svg 
                        id="spin-wheel" 
                        viewBox="0 0 300 300" 
                        class="w-72 h-72 transition-transform duration-[5000ms] ease-out filter drop-shadow-2xl"
                        style="transform: rotate(0deg);"
                    >
                        <!-- Outer Ring -->
                        <circle cx="150" cy="150" r="148" fill="none" stroke="#fbbf24" stroke-width="4"/>
                        
                        @php
                            $prizeCount = count($prizes);
                            $segmentAngle = 360 / $prizeCount;
                        @endphp

                        @foreach($prizes as $index => $prize)
                            @php
                                $startAngle = $index * $segmentAngle - 90;
                                $endAngle = ($index + 1) * $segmentAngle - 90;
                                $startRad = deg2rad($startAngle);
                                $endRad = deg2rad($endAngle);
                                
                                $x1 = 150 + 140 * cos($startRad);
                                $y1 = 150 + 140 * sin($startRad);
                                $x2 = 150 + 140 * cos($endRad);
                                $y2 = 150 + 140 * sin($endRad);
                                
                                $largeArc = $segmentAngle > 180 ? 1 : 0;
                                
                                $textAngle = $startAngle + $segmentAngle / 2;
                                $textRad = deg2rad($textAngle);
                                $textX = 150 + 85 * cos($textRad);
                                $textY = 150 + 85 * sin($textRad);
                            @endphp
                            
                            <path 
                                d="M 150 150 L {{ $x1 }} {{ $y1 }} A 140 140 0 {{ $largeArc }} 1 {{ $x2 }} {{ $y2 }} Z"
                                fill="{{ $prize['color'] }}"
                                stroke="#1f2937"
                                stroke-width="1"
                            />
                            
                            <text 
                                x="{{ $textX }}" 
                                y="{{ $textY }}" 
                                fill="white"
                                font-size="11"
                                font-weight="bold"
                                text-anchor="middle"
                                dominant-baseline="middle"
                                transform="rotate({{ $textAngle + 90 }}, {{ $textX }}, {{ $textY }})"
                            >
                                {{ $prize['is_jackpot'] ? '🎉 ' : '' }}{{ Str::limit($prize['name'], 12) }}
                            </text>
                        @endforeach

                        <!-- Center Circle -->
                        <circle cx="150" cy="150" r="30" fill="url(#centerGradient)" stroke="white" stroke-width="3"/>
                        <text x="150" y="155" fill="#1f2937" font-size="24" text-anchor="middle">🎯</text>
                        
                        <!-- Gradient Definition -->
                        <defs>
                            <linearGradient id="centerGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#fcd34d;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#f59e0b;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>

            <!-- Spin Button -->
            <div class="text-center">
                @if($canSpin)
                    <button 
                        wire:click="spin"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold text-lg rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ $isSpinning ? 'disabled' : '' }}
                    >
                        <span wire:loading.remove wire:target="spin">
                            🎰 Spin the Wheel!
                        </span>
                        <span wire:loading wire:target="spin" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                            Spinning...
                        </span>
                    </button>
                @else
                    <div class="bg-gray-800/50 rounded-xl p-4 inline-block">
                        <p class="text-gray-400 mb-2">Next spin available in:</p>
                        <div 
                            class="text-2xl font-bold text-yellow-400"
                            x-data="{ seconds: {{ $timeUntilNextSpin }} }"
                            x-init="
                                setInterval(() => {
                                    if (seconds > 0) {
                                        seconds--;
                                    } else {
                                        $wire.$refresh();
                                    }
                                }, 1000)
                            "
                        >
                            <span x-text="Math.floor(seconds / 3600).toString().padStart(2, '0')"></span>:
                            <span x-text="Math.floor((seconds % 3600) / 60).toString().padStart(2, '0')"></span>:
                            <span x-text="(seconds % 60).toString().padStart(2, '0')"></span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Prize Legend -->
        <div class="mt-6 bg-gray-800/30 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-white mb-3">Prizes</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($prizes as $prize)
                    <div class="flex items-center gap-2 bg-gray-800/50 rounded-lg p-2">
                        <div class="w-4 h-4 rounded-full" style="background: {{ $prize['color'] }}"></div>
                        <span class="text-sm text-gray-300">
                            {{ $prize['is_jackpot'] ? '🎉 ' : '' }}{{ $prize['name'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Spin History -->
        @if(count($spinHistory) > 0)
            <div class="mt-6 bg-gray-800/30 rounded-xl p-4">
                <h3 class="text-lg font-semibold text-white mb-3">📜 Spin History</h3>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($spinHistory as $spin)
                        <div class="flex items-center justify-between bg-gray-800/50 rounded-lg p-3">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full" style="background: {{ $spin->prize->color ?? '#6b7280' }}"></div>
                                <span class="text-white">{{ $spin->prize->name ?? 'Reward' }}</span>
                            </div>
                            <div class="text-right">
                                <div class="text-yellow-400 font-semibold">
                                    @if($spin->prize->type === 'points')
                                        +{{ $spin->prize_value }} Points
                                    @else
                                        {{ $spin->prize->name }}
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">{{ $spin->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <!-- Result Modal -->
    @if($showResultModal && $wonPrize)
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
            x-data
            x-init="
                if (typeof confetti !== 'undefined') {
                    confetti({
                        particleCount: {{ $wonPrize['is_jackpot'] ? 200 : 100 }},
                        spread: 70,
                        origin: { y: 0.6 }
                    });
                }
            "
        >
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 max-w-md w-full mx-4 border border-purple-500/30 shadow-2xl animate-bounce-in">
                <div class="text-center">
                    <div class="text-6xl mb-4 animate-pulse">
                        {{ $wonPrize['is_jackpot'] ? '🎉' : '🎊' }}
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">
                        {{ $wonPrize['is_jackpot'] ? 'JACKPOT!' : 'Congratulations!' }}
                    </h2>
                    <div class="bg-gradient-to-r from-yellow-400/20 to-orange-500/20 rounded-xl p-6 mb-6 border border-yellow-500/30">
                        <div class="text-4xl font-bold text-yellow-400 mb-2">
                            @if($wonPrize['type'] === 'points')
                                +{{ $wonPrize['value'] }} Points
                            @elseif($wonPrize['type'] === 'streak_freeze')
                                🛡️ Streak Freeze
                            @else
                                {{ $wonPrize['name'] }}
                            @endif
                        </div>
                        <p class="text-gray-400">You Won!</p>
                    </div>
                    <button 
                        wire:click="closeResultModal"
                        class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold rounded-full hover:opacity-90 transition-opacity"
                    >
                        Awesome! 🎉
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Scripts and Styles -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('spin-wheel', (event) => {
                const wheel = document.getElementById('spin-wheel');
                if (!wheel) return;

                const prizeIndex = event.prizeIndex;
                const prizes = @json($prizes);
                const segmentAngle = 360 / prizes.length;
                
                const rotations = 5;
                const prizeAngle = prizeIndex * segmentAngle + (segmentAngle / 2);
                const finalRotation = (rotations * 360) + (360 - prizeAngle);

                wheel.style.transform = `rotate(${finalRotation}deg)`;

                setTimeout(() => {
                    @this.finishSpin();
                }, 5000);
            });
        });
    </script>

    <style>
        @keyframes bounce-in {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-bounce-in {
            animation: bounce-in 0.5s ease-out;
        }
    </style>
</div>
