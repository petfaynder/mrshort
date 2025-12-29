<!DOCTYPE html>
<html class="{{ auth()->user()->theme_preference ?? 'dark' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <script>
        // Check for saved theme in localStorage and apply it immediately to avoid flash of wrong theme
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }

        // Also check database preference if available (passed from backend)
        @if(auth()->check() && auth()->user()->theme_preference)
            if ("{{ auth()->user()->theme_preference }}" === 'dark') {
                 document.documentElement.classList.add('dark');
                 localStorage.theme = 'dark';
            } else {
                 document.documentElement.classList.remove('dark');
                 localStorage.theme = 'light';
            }
        @endif
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags from Site Settings --}}
    <title>{{ setting('site_name', config('app.name', 'Linkly')) }} - Dashboard</title>
    <meta name="description" content="{{ setting('seo_description', '') }}">
    
    {{-- Favicon --}}
    @if(setting('favicon_url'))
    <link rel="icon" href="{{ setting('favicon_url') }}" type="image/x-icon">
    @endif

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    
    {{-- Custom Member Head Code from Settings --}}
    {!! setting('member_head_code', '') !!}
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark overflow-hidden">

{{-- Impersonation Banner (Admin logged in as another user) --}}
@if(session()->has('impersonating_from_admin_id'))
<div class="bg-amber-500 text-white py-2 px-4 text-center fixed top-0 left-0 right-0 z-50 shadow-lg">
    <div class="flex items-center justify-center gap-4">
        <span class="material-symbols-outlined">visibility</span>
        <span class="font-medium">Logged in as: <strong>{{ Auth::user()->name }} ({{ Auth::user()->email }})</strong></span>
        <a href="{{ route('admin.stop-impersonation') }}" class="bg-white text-amber-600 px-4 py-1 rounded-md font-semibold hover:bg-amber-100 transition-colors">
            ← Return to Admin Panel
        </a>
    </div>
</div>
<style>
    /* Add padding to body when impersonation banner is active */
    body.has-impersonation-banner .flex.h-screen { margin-top: 44px; height: calc(100vh - 44px); }
</style>
<script>document.body.classList.add('has-impersonation-banner');</script>
@endif

{{-- Admin Message Modal --}}
<livewire:user.admin-message-modal />

{{-- Deactivated Account Modal (Non-closeable) --}}
<livewire:user.deactivated-account-modal />

<div class="flex h-screen overflow-hidden">
    <aside class="w-64 bg-card-light dark:bg-card-dark flex flex-col p-4 border-r border-border-light dark:border-border-dark overflow-y-auto">
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
                    <a data-tutorial="nav-links" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.links.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.links.index') }}">
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
                    <a data-tutorial="nav-withdrawals" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.withdrawals') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.withdrawals') }}">
                        <span class="material-symbols-outlined">payments</span>
                        Withdrawals
                    </a>
                </li>
                <li class="mb-2">
                    <a data-tutorial="nav-tools" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.tools') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.tools') }}">
                        <span class="material-symbols-outlined">construction</span>
                        Tools
                    </a>
                </li>
                <li class="mb-2">
                    <a data-tutorial="nav-campaigns" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.ads.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.ads.index') }}">
                        <span class="material-symbols-outlined">campaign</span>
                        Campaigns
                    </a>
                </li>
                <li class="mb-2">
                    <a data-tutorial="nav-referrals" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.referrals') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.referrals') }}">
                        <span class="material-symbols-outlined">group</span>
                        Referrals
                    </a>
                </li>
                <li class="mb-2">
                    <a data-tutorial="nav-reports" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.reports') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.reports') }}">
                        <span class="material-symbols-outlined">analytics</span>
                        Reports
                    </a>
                </li>
                <li class="mb-2" data-tutorial="nav-gamification" x-data="{ open: {{ request()->routeIs('user.daily-spin') || request()->routeIs('user.mystery-boxes') || request()->routeIs('user.competition') || request()->routeIs('user.battle-pass') || request()->routeIs('user.teams') || request()->routeIs('user.vip') || request()->routeIs('user.achievements') || request()->routeIs('user.leaderboard') || request()->routeIs('user.inventory') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark {{ request()->routeIs('user.daily-spin') || request()->routeIs('user.mystery-boxes') || request()->routeIs('user.competition') || request()->routeIs('user.battle-pass') || request()->routeIs('user.teams') || request()->routeIs('user.vip') || request()->routeIs('user.achievements') || request()->routeIs('user.leaderboard') || request()->routeIs('user.inventory') ? 'bg-blue-50 dark:bg-blue-900/30' : '' }}">
                        <span class="flex items-center gap-3">
                            <span class="material-symbols-outlined">emoji_events</span>
                            Gamification
                        </span>
                        <span class="material-symbols-outlined transition-transform duration-200" :class="{ 'rotate-180': open }">expand_more</span>
                    </button>
                    <ul x-cloak x-show="open" x-collapse class="mt-2 ml-4 space-y-1 border-l-2 border-gray-200 dark:border-gray-700 pl-2">
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('user.daily-spin') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.daily-spin') }}">
                                <span class="material-symbols-outlined text-lg">casino</span>
                                Daily Spin
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('user.mystery-boxes') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.mystery-boxes') }}">
                                <span class="material-symbols-outlined text-lg">redeem</span>
                                Mystery Boxes
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('user.competition') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.competition') }}">
                                <span class="material-symbols-outlined text-lg">trophy</span>
                                Competition
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('user.battle-pass') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.battle-pass') }}">
                                <span class="material-symbols-outlined text-lg">military_tech</span>
                                Battle Pass
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('user.teams') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.teams') }}">
                                <span class="material-symbols-outlined text-lg">groups</span>
                                Teams
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('user.vip') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.vip') }}">
                                <span class="material-symbols-outlined text-lg">star</span>
                                VIP Status
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('user.achievements') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.achievements') }}">
                                <span class="material-symbols-outlined text-lg">emoji_events</span>
                                Achievements
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('user.leaderboard') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.leaderboard') }}">
                                <span class="material-symbols-outlined text-lg">leaderboard</span>
                                Leaderboard
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('user.inventory') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.inventory') }}">
                                <span class="material-symbols-outlined text-lg">inventory_2</span>
                                Inventory
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="mb-2">
                    <a data-tutorial="nav-contact" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('user.contact') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.contact') }}">
                        <span class="material-symbols-outlined">contact_support</span>
                        Contact Us
                    </a>
                </li>
            </ul>
        </nav>
        <div>
            <a data-tutorial="nav-settings" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark" href="{{ route('user.settings') }}">
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
    <main class="flex-1 p-8 overflow-auto">
        @if (isset($header))
            {{ $header }}
        @else
            <header data-tutorial="header" class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-heading-light dark:text-heading-dark">Dashboard Overview</h2>
                    <p class="text-text-light dark:text-text-dark">Welcome back, let's see your progress!</p>
                </div>
                <div class="flex items-center gap-6">
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

{{-- Interactive Tutorial Initialization --}}
@if(auth()->check() && auth()->user()->shouldShowTutorial())
<script>
    window.showTutorial = true;
    window.tutorialCompleteUrl = '{{ route("tutorial.complete") }}';
    window.csrfToken = '{{ csrf_token() }}';
</script>
@endif

{{-- Cookie Consent Banner --}}
<livewire:cookie-consent />

{{-- Custom Footer Code from Settings --}}
{!! setting('footer_code', '') !!}
</body>
</html>
