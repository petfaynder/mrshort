{{--
    Not: Bu sayfadaki "Günün Başarımı" ve "Özel Başarım Koleksiyonları" bölümleri şu an için statik olarak tasarıma göre eklenmiştir.
    Gelecekte bu bölümlerin dinamik hale getirilmesi için backend entegrasyonu gerekebilir.
--}}
<div>
    <main class="w-full">
        <div class="layout-content-container flex flex-col w-full max-w-[1200px] flex-1 mx-auto">
            <div class="flex flex-wrap justify-between gap-3 p-4">
                <p class="text-white text-4xl font-black leading-tight tracking-[-0.033em] min-w-72">Achievements Collection</p>
            </div>
            <div class="p-4 mb-6">
                <div class="relative rounded-xl border-2 border-primary/50 bg-gradient-to-br from-primary/20 via-[#101922] to-[#101922] p-6 shadow-2xl shadow-primary/20 overflow-hidden">
                    <div class="absolute inset-0 featured-shine animate-shine opacity-50"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                        <div class="flex flex-col items-center text-center">
                            <div class="relative mb-4">
                                <span class="material-symbols-outlined text-primary" style="font-size: 80px;">military_tech</span>
                                <span class="material-symbols-outlined absolute -top-1 -right-1 text-yellow-400 animate-pulse" style="font-size: 32px;">star</span>
                            </div>
                            <div class="bg-primary/20 text-primary px-3 py-1 rounded-full text-sm font-bold">GÜNÜN BAŞARIMI</div>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-white text-3xl font-bold leading-tight tracking-tight">Tıklama Komutanı</h3>
                            <p class="text-slate-300 mt-2">Bu hafta içinde tek bir kısa linkte 500 tıklama alarak özel ödüller kazanın!</p>
                            <div class="mt-4 flex flex-wrap justify-center md:justify-start items-center gap-x-6 gap-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-yellow-400" style="font-size: 20px;">stars</span>
                                    <span class="text-white text-lg font-semibold">1000</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-400" style="font-size: 20px;">toll</span>
                                    <span class="text-white text-lg font-semibold">250</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-400" style="font-size: 20px;">shield</span>
                                    <span class="text-white text-lg font-semibold">Nadir Rozet</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-center gap-3 text-center self-center md:self-auto">
                            <p class="text-slate-300 text-sm font-medium">Kalan Süre</p>
                            <div class="flex items-center gap-2">
                                <div class="flex flex-col items-center">
                                    <div class="bg-slate-900/50 border border-slate-700 backdrop-blur-sm rounded-lg w-16 h-16 flex items-center justify-center">
                                        <span class="text-white text-3xl font-bold tracking-wider">03</span>
                                    </div>
                                    <span class="text-slate-400 text-xs mt-1">GÜN</span>
                                </div>
                                <div class="text-white text-3xl font-bold pb-4">:</div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-slate-900/50 border border-slate-700 backdrop-blur-sm rounded-lg w-16 h-16 flex items-center justify-center">
                                        <span class="text-white text-3xl font-bold tracking-wider">14</span>
                                    </div>
                                    <span class="text-slate-400 text-xs mt-1">SAAT</span>
                                </div>
                                <div class="text-white text-3xl font-bold pb-4">:</div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-slate-900/50 border border-slate-700 backdrop-blur-sm rounded-lg w-16 h-16 flex items-center justify-center">
                                        <span class="text-white text-3xl font-bold tracking-wider">22</span>
                                    </div>
                                    <span class="text-slate-400 text-xs mt-1">DAK</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-8 p-4">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-white text-2xl font-bold tracking-tight">Özel Başarım Koleksiyonları</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-4 rounded-xl border border-slate-700 bg-[#233648]/50 p-6 hover:bg-[#233648] transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="flex size-12 items-center justify-center rounded-lg bg-primary/20 text-primary">
                                    <span class="material-symbols-outlined" style="font-size: 32px;">rocket_launch</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-white">Giriş Seviyesi Kısaltıcı</h4>
                                    <p class="text-sm text-slate-400">Temel kısaltma özelliklerini keşfedin.</p>
                                </div>
                                <div class="flex items-center gap-2 text-sm font-semibold text-yellow-400">
                                    <span class="material-symbols-outlined">emoji_events</span>
                                    <span>Özel Rozet</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="h-2 flex-1 rounded-full bg-slate-700">
                                    <div class="h-2 w-1/3 rounded-full bg-primary"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-300">1 / 3</span>
                            </div>
                            <div class="flex -space-x-4">
                                <div class="relative size-12 rounded-lg border-2 border-blue-400 bg-[#233648]">
                                    <div class="flex h-full w-full items-center justify-center">
                                        <span class="material-symbols-outlined text-blue-400">flag_circle</span>
                                    </div>
                                </div>
                                <div class="relative size-12 rounded-lg border-2 border-slate-600 bg-[#192532] locked-card">
                                    <div class="flex h-full w-full items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-500">share</span>
                                    </div>
                                </div>
                                <div class="relative size-12 rounded-lg border-2 border-slate-600 bg-[#192532] locked-card">
                                    <div class="flex h-full w-full items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-500">bolt</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-4 rounded-xl border border-slate-700 bg-[#233648]/50 p-6 hover:bg-[#233648] transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="flex size-12 items-center justify-center rounded-lg bg-green-500/20 text-green-400">
                                    <span class="material-symbols-outlined" style="font-size: 32px;">groups</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-white">Sosyal Medya Uzmanı</h4>
                                    <p class="text-sm text-slate-400">Paylaşım ve etkileşim gücünüzü gösterin.</p>
                                </div>
                                <div class="flex items-center gap-2 text-sm font-semibold text-slate-400 opacity-60">
                                    <span class="material-symbols-outlined">lock</span>
                                    <span>Özel Bonuslar</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="h-2 flex-1 rounded-full bg-slate-700">
                                    <div class="h-2 w-0 rounded-full bg-green-500"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-300">0 / 4</span>
                            </div>
                            <div class="flex -space-x-4">
                                <div class="relative size-12 rounded-lg border-2 border-slate-600 bg-[#192532] locked-card">
                                    <div class="flex h-full w-full items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-500">share</span>
                                    </div>
                                </div>
                                <div class="relative size-12 rounded-lg border-2 border-slate-600 bg-[#192532] locked-card">
                                    <div class="flex h-full w-full items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-500">trending_up</span>
                                    </div>
                                </div>
                                <div class="relative size-12 rounded-lg border-2 border-slate-600 bg-[#192532] locked-card">
                                    <div class="flex h-full w-full items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-500">verified_user</span>
                                    </div>
                                </div>
                                <div class="relative size-12 rounded-lg border-2 border-slate-600 bg-[#192532] locked-card">
                                    <div class="flex h-full w-full items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-500">campaign</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-2 p-3 overflow-x-auto">
                @foreach(['all' => 'Tümü', 'daily' => 'Günlük', 'weekly' => 'Haftalık', 'one_time' => 'Kariyer', 'social' => 'Sosyal', 'economic' => 'Ekonomi', 'discovery' => 'Keşif'] as $categoryKey => $categoryName)
                    <div wire:click="$set('filterCategory', '{{ $categoryKey }}')"
                         class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg px-4
                                {{ $filterCategory === $categoryKey ? 'bg-primary/20 border border-primary' : 'bg-[#233648] hover:bg-primary/20' }}">
                        <p class="{{ $filterCategory === $categoryKey ? 'text-primary text-sm font-bold' : 'text-slate-300 hover:text-white text-sm font-medium' }} leading-normal">{{ $categoryName }}</p>
                    </div>
                @endforeach
            </div>

            @if($goals->isEmpty())
                <div class="p-4 text-center text-slate-400">
                    <p>Bu kategoride henüz görüntülenecek başarım bulunmamaktadır.</p>
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
                                        <p class="text-xs text-yellow-400 font-bold">Ödül: {{ $goal->reward->name }}</p>
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
