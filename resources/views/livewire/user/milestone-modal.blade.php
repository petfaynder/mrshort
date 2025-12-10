<div>
    @if($show)
        <!-- Overlay -->
        <div 
            x-data="{ confettiTriggered: false }"
            x-init="
                $wire.on('trigger-confetti', () => {
                    if (typeof confetti === 'function') {
                        confetti({
                            particleCount: 150,
                            spread: 70,
                            origin: { y: 0.6 }
                        });
                        setTimeout(() => {
                            confetti({
                                particleCount: 100,
                                angle: 60,
                                spread: 55,
                                origin: { x: 0 }
                            });
                        }, 250);
                        setTimeout(() => {
                            confetti({
                                particleCount: 100,
                                angle: 120,
                                spread: 55,
                                origin: { x: 1 }
                            });
                        }, 400);
                    }
                });
                $wire.on('open-share-window', (data) => {
                    window.open(data.url, '_blank', 'width=600,height=400');
                });
            "
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-sm"
            wire:click.self="close"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            <!-- Modal -->
            <div 
                class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 max-w-md w-full mx-4 border border-amber-500/30 shadow-2xl text-center"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
            >
                <!-- Icon with glow -->
                <div class="relative inline-block mb-4">
                    <div class="absolute inset-0 bg-amber-500/30 blur-xl rounded-full animate-pulse"></div>
                    <div class="relative w-24 h-24 mx-auto bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center animate-bounce">
                        <span class="text-5xl">{{ $milestoneIcon }}</span>
                    </div>
                </div>

                <!-- Title -->
                <h2 class="text-3xl font-bold text-white mb-2 animate-pulse">
                    {{ $milestoneName }}
                </h2>
                
                <!-- Description -->
                <p class="text-gray-300 mb-4">
                    {{ $milestoneDescription }}
                </p>

                <!-- Rewards -->
                <div class="flex justify-center gap-4 mb-6">
                    @if($pointsEarned > 0)
                        <div class="bg-purple-600/20 border border-purple-500/30 rounded-xl px-4 py-2">
                            <div class="text-2xl font-bold text-purple-400">+{{ number_format($pointsEarned) }}</div>
                            <div class="text-xs text-purple-300">Puan</div>
                        </div>
                    @endif
                    @if($badgeEarned)
                        <div class="bg-amber-600/20 border border-amber-500/30 rounded-xl px-4 py-2">
                            <div class="text-2xl">{{ $badgeEarned['icon'] ?? '🏅' }}</div>
                            <div class="text-xs text-amber-300">{{ $badgeEarned['name'] ?? 'Rozet' }}</div>
                        </div>
                    @endif
                </div>

                <!-- Share buttons -->
                <div class="mb-6">
                    <p class="text-sm text-gray-400 mb-3">Başarını paylaş</p>
                    <div class="flex justify-center gap-3">
                        <button 
                            wire:click="shareOnTwitter"
                            class="p-3 bg-[#1DA1F2]/20 hover:bg-[#1DA1F2]/40 text-[#1DA1F2] rounded-xl transition"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </button>
                        <button 
                            wire:click="shareOnFacebook"
                            class="p-3 bg-[#4267B2]/20 hover:bg-[#4267B2]/40 text-[#4267B2] rounded-xl transition"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </button>
                        <button 
                            onclick="navigator.share ? navigator.share({title: '{{ $milestoneName }}', url: '{{ config('app.url') }}'}) : alert('Paylaşım desteklenmiyor')"
                            class="p-3 bg-green-500/20 hover:bg-green-500/40 text-green-400 rounded-xl transition"
                        >
                            <span class="material-symbols-outlined">share</span>
                        </button>
                    </div>
                </div>

                <!-- Close button -->
                <button 
                    wire:click="close"
                    class="w-full py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl hover:from-amber-600 hover:to-orange-700 transition"
                >
                    Harika! 🎉
                </button>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
@endpush
