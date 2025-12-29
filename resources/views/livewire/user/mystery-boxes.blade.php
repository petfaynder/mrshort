<div class="mystery-boxes-page">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
            <span class="text-3xl">🎁</span> Mystery Boxes
        </h2>
        <p class="text-gray-400 mt-1">Reach milestones to earn mystery boxes!</p>
    </div>

    @if(count($unopenedBoxes) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($unopenedBoxes as $box)
                <div 
                    class="relative bg-gradient-to-br rounded-xl p-6 border-2 transition-all duration-300 hover:scale-105 cursor-pointer group"
                    style="
                        background: linear-gradient(135deg, {{ $box->mysteryBox->tier_color }}20, {{ $box->mysteryBox->tier_color }}05);
                        border-color: {{ $box->mysteryBox->tier_color }}50;
                    "
                    wire:click="openBox({{ $box->id }})"
                >
                    <!-- Glow effect -->
                    <div 
                        class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl"
                        style="box-shadow: 0 0 30px {{ $box->mysteryBox->tier_color }}40;"
                    ></div>

                    <!-- Box content -->
                    <div class="relative text-center">
                        <!-- Icon -->
                        <div class="text-6xl mb-4 animate-bounce">
                            {{ $box->mysteryBox->icon ?? '🎁' }}
                        </div>

                        <!-- Tier badge -->
                        <div 
                            class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-2"
                            style="background: {{ $box->mysteryBox->tier_color }}30; color: {{ $box->mysteryBox->tier_color }};"
                        >
                            {{ $box->mysteryBox->tier_label }}
                        </div>

                        <!-- Name -->
                        <h3 class="text-lg font-bold text-white mb-1">{{ $box->mysteryBox->name }}</h3>

                        <!-- Source -->
                        <p class="text-xs text-gray-500 mb-4">
                            @switch($box->source)
                                @case('links_milestone') 🔗 Link Milestone @break
                                @case('clicks_milestone') 👆 Clicks Milestone @break
                                @case('weekly_challenge') 🎯 Weekly Challenge @break
                                @case('monthly_top10') 🏆 Monthly Top 10 @break
                                @default {{ $box->source }}
                            @endswitch
                        </p>

                        <!-- Open button -->
                        <button 
                            class="w-full py-2 rounded-lg font-semibold transition-all"
                            style="background: {{ $box->mysteryBox->tier_color }}; color: #1a1a2e;"
                        >
                            Open!
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-800/50 rounded-xl p-12 text-center">
            <div class="text-6xl mb-4 opacity-50">📦</div>
            <h3 class="text-xl font-semibold text-gray-400 mb-2">No mystery boxes</h3>
            <p class="text-gray-500">You can earn boxes by reaching milestones, completing weekly challenges,<br>and placing in competitions!</p>
        </div>
    @endif

    <!-- Result Modal -->
    @if($showResultModal && $wonReward)
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm"
            x-data
            x-init="
                if (typeof confetti !== 'undefined') {
                    confetti({
                        particleCount: 150,
                        spread: 70,
                        origin: { y: 0.6 }
                    });
                }
            "
        >
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 max-w-md w-full mx-4 border border-purple-500/30 shadow-2xl text-center animate-bounce-in">
                <div class="text-6xl mb-4">🎉</div>
                <h2 class="text-2xl font-bold text-white mb-4">Box Opened!</h2>

                <div class="bg-gradient-to-r from-yellow-400/20 to-orange-500/20 rounded-xl p-6 mb-6 border border-yellow-500/30">
                    @if($wonReward['type'] === 'points')
                        <div class="text-5xl font-bold text-yellow-400 mb-2">
                            +{{ $wonReward['value'] }}
                        </div>
                        <p class="text-gray-400">You Won Points!</p>
                    @elseif($wonReward['type'] === 'streak_freeze')
                        <div class="text-5xl mb-2">🛡️</div>
                        <div class="text-2xl font-bold text-blue-400 mb-2">Streak Freeze</div>
                        <p class="text-gray-400">x{{ $wonReward['value'] ?? 1 }}</p>
                    @else
                        <div class="text-5xl mb-2">🏅</div>
                        <div class="text-xl font-bold text-purple-400">Special Reward!</div>
                    @endif
                </div>

                <button 
                    wire:click="closeResultModal"
                    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold rounded-full hover:opacity-90 transition-opacity"
                >
                    Awesome! 🎉
                </button>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
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
