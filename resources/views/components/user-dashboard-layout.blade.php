<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Linkly') }} - Dashboard</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @livewireStyles
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
<div class="flex h-screen">
    <aside class="w-64 bg-card-light dark:bg-card-dark flex flex-col p-4 border-r border-border-light dark:border-border-dark">
        <div class="flex items-center gap-2 px-4 py-2 mb-8">
            <span class="material-symbols-outlined text-primary text-3xl">link</span>
            <h1 class="text-2xl font-bold text-heading-light dark:text-heading-dark">Linkly</h1>
        </div>
        <nav class="flex-grow">
            <ul>
                <li class="mb-2">
                    <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('dashboard') }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        Dashboard
                    </a>
                </li>
                <li class="mb-2">
                    <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.links.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.links.index') }}">
                        <span class="material-symbols-outlined">link</span>
                        Links
                    </a>
                    <ul class="pl-4 mt-2">
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('user.hidden-links.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.hidden-links.index') }}">
                                <span class="material-symbols-outlined">visibility_off</span>
                                Hidden Links
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="mb-2">
                    <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.withdrawals') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.withdrawals') }}">
                        <span class="material-symbols-outlined">payments</span>
                        Withdrawals
                    </a>
                </li>
                <li class="mb-2">
                    <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.tools') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.tools') }}">
                        <span class="material-symbols-outlined">construction</span>
                        Tools
                    </a>
                </li>
                <li class="mb-2">
                    <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.ads.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.ads.index') }}">
                        <span class="material-symbols-outlined">campaign</span>
                        Campaigns
                    </a>
                </li>
                <li class="mb-2">
                    <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.referrals') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.referrals') }}">
                        <span class="material-symbols-outlined">group</span>
                        Referrals
                    </a>
                </li>
                <li class="mb-2">
                    <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.reports') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.reports') }}">
                        <span class="material-symbols-outlined">analytics</span>
                        Reports
                    </a>
                </li>
                <li class="mb-2">
                    <span class="px-4 text-xs font-semibold text-gray-500 uppercase">Gamification</span>
                    <ul class="mt-2 space-y-1">
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.achievements') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.achievements') }}">
                                <span class="material-symbols-outlined">emoji_events</span>
                                Achievements
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.leaderboard') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.leaderboard') }}">
                                <span class="material-symbols-outlined">leaderboard</span>
                                Leaderboard
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.inventory') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.inventory') }}">
                                <span class="material-symbols-outlined">inventory_2</span>
                                Inventory
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="mb-2">
                    <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.contact') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.contact') }}">
                        <span class="material-symbols-outlined">contact_support</span>
                        Contact Us
                    </a>
                </li>
            </ul>
        </nav>
        <div>
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark" href="{{ route('user.settings') }}">
                <span class="material-symbols-outlined">settings</span>
                Settings
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark w-full">
                    <span class="material-symbols-outlined">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </aside>
    <main class="flex-1 p-8 overflow-y-auto">
        @if (isset($header))
            {{ $header }}
        @else
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-heading-light dark:text-heading-dark">Dashboard Overview</h2>
                    <p class="text-text-light dark:text-text-dark">Welcome back, let's see your progress!</p>
                </div>
                <div class="flex items-center gap-4">
                    <livewire:user.notifications />
                    <div class="flex items-center gap-3">
                        <img alt="User avatar" class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=40&background=0D8ABC&color=fff"/>
                        <div>
                            <p class="font-semibold text-heading-light dark:text-heading-dark">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-text-light dark:text-text-dark">Balance: ${{ number_format(Auth::user()->balance, 5) }}</p>
                        </div>
                    </div>
                     @if(auth()->user() && auth()->user()->hasRole('admin'))
                        <a href="{{ route('filament.admin.pages.dashboard') }}" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700 text-sm">Admin Panel</a>
                    @endif
                </div>
            </header>
        @endif
        
        {{ $slot }}

    </main>
</div>
@livewireScripts
@stack('scripts')
</body>
</html>
