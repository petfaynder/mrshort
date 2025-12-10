<div class="team-manager-container">
    @if($myTeam)
        <!-- My Team View -->
        <div class="bg-gradient-to-br from-blue-900/40 to-cyan-900/40 rounded-2xl p-6 border border-blue-500/20">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div class="flex items-center gap-4">
                    @if($myTeam->logo_path)
                        <img src="{{ Storage::url($myTeam->logo_path) }}" alt="{{ $myTeam->name }}" class="w-16 h-16 rounded-xl object-cover">
                    @else
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center">
                            <span class="text-3xl">👥</span>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $myTeam->name }}</h2>
                        <p class="text-blue-300 text-sm">{{ $myMembership->role_label }}</p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <div class="text-2xl font-bold text-cyan-400">#{{ $myTeam->weekly_rank }}</div>
                    <div class="text-sm text-gray-400">Haftalık Sıralama</div>
                </div>
            </div>

            <!-- Team Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-800/50 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-white">{{ $myTeam->member_count }}/20</div>
                    <div class="text-sm text-gray-400">Üyeler</div>
                </div>
                <div class="bg-gray-800/50 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-cyan-400">{{ number_format($myTeam->weekly_points) }}</div>
                    <div class="text-sm text-gray-400">Haftalık Puan</div>
                </div>
                <div class="bg-gray-800/50 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-blue-400">{{ number_format($myTeam->total_points) }}</div>
                    <div class="text-sm text-gray-400">Toplam Puan</div>
                </div>
            </div>

            <!-- Description -->
            @if($myTeam->description)
                <div class="bg-gray-800/30 rounded-xl p-4 mb-6">
                    <p class="text-gray-300">{{ $myTeam->description }}</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex gap-3 mb-6">
                <button 
                    wire:click="toggleChat"
                    class="flex-1 px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2"
                >
                    <span class="material-symbols-outlined">chat</span>
                    {{ $showChat ? 'Sohbeti Kapat' : 'Takım Sohbeti' }}
                </button>
                @if($myMembership->role !== 'leader')
                    <button 
                        wire:click="leaveTeam"
                        wire:confirm="Takımdan ayrılmak istediğinize emin misiniz?"
                        class="px-4 py-3 bg-red-600/20 text-red-400 font-semibold rounded-lg hover:bg-red-600/30 transition"
                    >
                        Ayrıl
                    </button>
                @endif
            </div>

            <!-- Chat Panel -->
            @if($showChat)
                <div 
                    x-data="{ scrollToBottom() { this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight } }"
                    x-init="scrollToBottom()"
                    @message-sent.window="setTimeout(() => scrollToBottom(), 100)"
                    class="bg-gray-800/50 rounded-xl border border-gray-700 overflow-hidden"
                >
                    <!-- Messages -->
                    <div 
                        x-ref="messages"
                        class="h-64 overflow-y-auto p-4 space-y-3"
                    >
                        @forelse($chatMessages as $message)
                            <div class="flex gap-3 {{ $message['user_id'] === auth()->id() ? 'flex-row-reverse' : '' }}">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                    {{ substr($message['user']['name'] ?? 'U', 0, 1) }}
                                </div>
                                <div class="{{ $message['user_id'] === auth()->id() ? 'bg-blue-600/30' : 'bg-gray-700/50' }} rounded-lg px-3 py-2 max-w-[70%]">
                                    <div class="text-xs text-gray-400 mb-1">
                                        {{ $message['user']['name'] ?? 'Kullanıcı' }}
                                    </div>
                                    <div class="text-white text-sm">{{ $message['message'] }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-8">
                                Henüz mesaj yok. İlk mesajı sen gönder!
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Message Input -->
                    <form wire:submit="sendMessage" class="border-t border-gray-700 p-3 flex gap-2">
                        <input 
                            type="text" 
                            wire:model="newMessage"
                            placeholder="Mesajınızı yazın..."
                            maxlength="200"
                            class="flex-1 bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                        >
                            <span class="material-symbols-outlined" style="font-size: 18px;">send</span>
                        </button>
                    </form>
                    @error('newMessage') 
                        <div class="px-3 pb-2 text-red-400 text-xs">{{ $message }}</div>
                    @enderror
                </div>
            @endif
        </div>
    @else
        <!-- No Team View -->
        <div class="space-y-6">
            <!-- Pending Invites -->
            @if(count($pendingInvites) > 0)
                <div class="bg-gradient-to-r from-amber-900/30 to-orange-900/30 rounded-xl p-4 border border-amber-500/20">
                    <h3 class="text-lg font-semibold text-white mb-3">Bekleyen Davetler</h3>
                    <div class="space-y-2">
                        @foreach($pendingInvites as $invite)
                            <div class="flex items-center justify-between bg-gray-800/50 rounded-lg p-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-amber-600/30 flex items-center justify-center">
                                        <span class="text-xl">👥</span>
                                    </div>
                                    <div>
                                        <div class="text-white font-medium">{{ $invite['team']['name'] }}</div>
                                        <div class="text-xs text-gray-400">{{ $invite['invited_by']['name'] ?? 'Bilinmeyen' }} tarafından</div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button wire:click="acceptInvite({{ $invite['id'] }})" class="p-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </button>
                                    <button wire:click="rejectInvite({{ $invite['id'] }})" class="p-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Create/Join Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button 
                    wire:click="$set('showCreateModal', true)"
                    class="bg-gradient-to-br from-blue-600 to-cyan-600 rounded-xl p-6 text-center hover:from-blue-700 hover:to-cyan-700 transition"
                >
                    <span class="text-4xl block mb-2">🏰</span>
                    <div class="text-white font-bold text-lg">Takım Kur</div>
                    <div class="text-blue-200 text-sm">1,000 Puan</div>
                </button>
                <div class="bg-gradient-to-br from-gray-700 to-gray-800 rounded-xl p-6 text-center border border-gray-600">
                    <span class="text-4xl block mb-2">🔍</span>
                    <div class="text-white font-bold text-lg">Takım Bul</div>
                    <div class="text-gray-400 text-sm">Aşağıdan seç</div>
                </div>
            </div>

            <!-- Top Teams -->
            <div class="bg-gray-800/50 rounded-xl p-4">
                <h3 class="text-lg font-semibold text-white mb-4">Açık Takımlar</h3>
                <div class="space-y-2">
                    @forelse($teams as $index => $team)
                        <div class="flex items-center justify-between bg-gray-700/50 rounded-lg p-3 hover:bg-gray-700 transition">
                            <div class="flex items-center gap-3">
                                <span class="w-8 text-center font-bold {{ $index < 3 ? 'text-amber-400' : 'text-gray-500' }}">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <div class="text-white font-medium">{{ $team['name'] }}</div>
                                    <div class="text-xs text-gray-400">{{ $team['member_count'] }}/20 üye</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right hidden sm:block">
                                    <div class="text-cyan-400 font-semibold">{{ number_format($team['weekly_points']) }}</div>
                                    <div class="text-xs text-gray-400">haftalık</div>
                                </div>
                                <button 
                                    wire:click="joinTeam({{ $team['id'] }})"
                                    wire:loading.attr="disabled"
                                    class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                                >
                                    Katıl
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">Henüz açık takım yok</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Create Team Modal -->
        @if($showCreateModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" wire:click.self="$set('showCreateModal', false)">
                <div class="bg-gray-800 rounded-2xl p-6 max-w-md w-full mx-4 border border-blue-500/30">
                    <h3 class="text-xl font-bold text-white mb-4">Takım Kur</h3>
                    
                    <form wire:submit="createTeam" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Takım Adı</label>
                            <input 
                                type="text" 
                                wire:model="newTeamName" 
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Takım adını girin..."
                            >
                            @error('newTeamName') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Açıklama (Opsiyonel)</label>
                            <textarea 
                                wire:model="newTeamDescription" 
                                rows="3"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Takımınızı tanıtın..."
                            ></textarea>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="newTeamIsPublic" id="isPublic" class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500">
                            <label for="isPublic" class="text-gray-300">Herkese açık (herkes katılabilir)</label>
                        </div>
                        
                        <div class="bg-amber-900/30 rounded-lg p-3 text-amber-300 text-sm">
                            ⚠️ Takım kurmak 1,000 puan gerektirir
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" wire:click="$set('showCreateModal', false)" class="flex-1 px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                                İptal
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="flex-1 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition disabled:opacity-50">
                                <span wire:loading.remove wire:target="createTeam">Kur (1,000 Puan)</span>
                                <span wire:loading wire:target="createTeam">Oluşturuluyor...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
