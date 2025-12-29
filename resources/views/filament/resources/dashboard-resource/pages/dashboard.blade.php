<x-filament-panels::page>
    {{-- Welcome Section with Gradient --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-sky-600 via-cyan-600 to-teal-500 p-8 mb-8">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
        
        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    Welcome back, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-white/80 text-lg">
                    {{ now()->locale('en')->translatedFormat('l, F d, Y') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->user())
                    <x-filament::button 
                        color="gray"
                        icon="heroicon-o-arrow-right-on-rectangle"
                        tag="a"
                        href="{{ route('dashboard') }}"
                        class="!bg-white/20 !border-white/30 hover:!bg-white/30 !text-white backdrop-blur-sm"
                    >
                        User Dashboard
                    </x-filament::button>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Quick Stats Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Users --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-sky-500/10">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-sky-500/20 blur-2xl group-hover:bg-sky-500/30 transition-colors"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="rounded-xl bg-gradient-to-br from-sky-500/30 to-cyan-500/30 p-3">
                        <x-heroicon-o-users class="h-6 w-6 text-sky-400"/>
                    </div>
                    <span class="text-xs font-medium text-emerald-400 bg-emerald-500/20 px-2 py-1 rounded-full">
                        +{{ \App\Models\User::whereDate('created_at', today())->count() }} today
                    </span>
                </div>
                <h3 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-cyan-400 mb-1">
                    {{ number_format(\App\Models\User::count()) }}
                </h3>
                <p class="text-gray-400 text-sm font-medium">Total Users</p>
            </div>
        </div>

        {{-- Total Links --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-cyan-500/10">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-cyan-500/20 blur-2xl group-hover:bg-cyan-500/30 transition-colors"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="rounded-xl bg-gradient-to-br from-cyan-500/30 to-teal-500/30 p-3">
                        <x-heroicon-o-link class="h-6 w-6 text-cyan-400"/>
                    </div>
                    <span class="text-xs font-medium text-emerald-400 bg-emerald-500/20 px-2 py-1 rounded-full">
                        +{{ \App\Models\Link::whereDate('created_at', today())->count() }} today
                    </span>
                </div>
                <h3 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-teal-400 mb-1">
                    {{ number_format(\App\Models\Link::count()) }}
                </h3>
                <p class="text-gray-400 text-sm font-medium">Total Links</p>
            </div>
        </div>

        {{-- Total Clicks --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-emerald-500/10">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/20 blur-2xl group-hover:bg-emerald-500/30 transition-colors"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="rounded-xl bg-gradient-to-br from-emerald-500/30 to-teal-500/30 p-3">
                        <x-heroicon-o-cursor-arrow-rays class="h-6 w-6 text-emerald-400"/>
                    </div>
                    <span class="text-xs font-medium text-emerald-400 bg-emerald-500/20 px-2 py-1 rounded-full">
                        +{{ number_format(\App\Models\LinkClick::whereDate('created_at', today())->count()) }} today
                    </span>
                </div>
                <h3 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400 mb-1">
                    {{ number_format(\App\Models\LinkClick::count()) }}
                </h3>
                <p class="text-gray-400 text-sm font-medium">Total Clicks</p>
            </div>
        </div>

        {{-- Pending Withdrawals --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-amber-500/10">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-500/20 blur-2xl group-hover:bg-amber-500/30 transition-colors"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 p-3">
                        <x-heroicon-o-banknotes class="h-6 w-6 text-amber-400"/>
                    </div>
                    @php
                        $pendingCount = \App\Models\WithdrawalRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="text-xs font-medium text-amber-400 bg-amber-500/20 px-2 py-1 rounded-full animate-pulse">
                            {{ $pendingCount }} pending
                        </span>
                    @endif
                </div>
                <h3 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-400 mb-1">
                    ${{ number_format(\App\Models\WithdrawalRequest::where('status', 'pending')->sum('amount'), 2) }}
                </h3>
                <p class="text-gray-400 text-sm font-medium">Pending Withdrawals</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Quick Actions Card --}}
        <div class="lg:col-span-1 rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <x-heroicon-o-bolt class="h-5 w-5 text-sky-400"/>
                Quick Actions
            </h2>
            <div class="space-y-3">
                <a href="{{ route('filament.admin.resources.users.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-sky-500/30 transition-all group">
                    <div class="rounded-lg bg-sky-500/20 p-2 group-hover:bg-sky-500/30 transition-colors">
                        <x-heroicon-o-users class="h-5 w-5 text-sky-400"/>
                    </div>
                    <span class="text-gray-300 group-hover:text-white transition-colors">Manage Users</span>
                    <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-500 ml-auto group-hover:text-sky-400 transition-colors"/>
                </a>
                <a href="{{ route('filament.admin.resources.withdrawal-requests.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-amber-500/30 transition-all group">
                    <div class="rounded-lg bg-amber-500/20 p-2 group-hover:bg-amber-500/30 transition-colors">
                        <x-heroicon-o-banknotes class="h-5 w-5 text-amber-400"/>
                    </div>
                    <span class="text-gray-300 group-hover:text-white transition-colors">Withdrawal Requests</span>
                    <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-500 ml-auto group-hover:text-amber-400 transition-colors"/>
                </a>
                <a href="{{ route('filament.admin.resources.tickets.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-cyan-500/30 transition-all group">
                    <div class="rounded-lg bg-cyan-500/20 p-2 group-hover:bg-cyan-500/30 transition-colors">
                        <x-heroicon-o-ticket class="h-5 w-5 text-cyan-400"/>
                    </div>
                    <span class="text-gray-300 group-hover:text-white transition-colors">Support Tickets</span>
                    <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-500 ml-auto group-hover:text-cyan-400 transition-colors"/>
                </a>
                <a href="{{ route('filament.admin.resources.campaign-templates.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-rose-500/30 transition-all group">
                    <div class="rounded-lg bg-rose-500/20 p-2 group-hover:bg-rose-500/30 transition-colors">
                        <x-heroicon-o-megaphone class="h-5 w-5 text-rose-400"/>
                    </div>
                    <span class="text-gray-300 group-hover:text-white transition-colors">Ad Campaigns</span>
                    <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-500 ml-auto group-hover:text-rose-400 transition-colors"/>
                </a>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="lg:col-span-2 rounded-2xl bg-gradient-to-br from-[#142337]/80 to-[#1e3a4f]/80 backdrop-blur-xl border border-white/10 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <x-heroicon-o-clock class="h-5 w-5 text-sky-400"/>
                Recent Activity
            </h2>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse(\App\Models\User::latest()->take(5)->get() as $user)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5">
                        <div class="rounded-full bg-gradient-to-br from-sky-500 to-cyan-500 p-2">
                            <x-heroicon-o-user class="h-4 w-4 text-white"/>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">Joined {{ $user->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }}</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>
    
    {{-- Existing Widgets --}}
    @livewire('admin-dashboard-stats')
</x-filament-panels::page>
