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
    <style>
        [x-cloak] { display: none !important; }

        /* ─── SIDEBAR ─── */
        /* Desktop: sticky in normal flow */
        #dashboard-aside {
            position: sticky;
            top: 0;
            height: 100vh;
            flex-shrink: 0;
            overflow-y: auto;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
        }

        /* Mobile: fixed overlay, hidden by default */
        @media (max-width: 1023px) {
            #dashboard-aside {
                position: fixed;
                inset: 0 auto 0 0;
                height: 100dvh;
                z-index: 50;
                transform: translateX(-100%);
            }
            #dashboard-aside.sidebar-open {
                transform: translateX(0);
                box-shadow: 8px 0 40px rgba(0,0,0,.35);
            }
        }

        /* Backdrop */
        #sidebar-backdrop {
            display: none;
        }
        @media (max-width: 1023px) {
            #sidebar-backdrop.sidebar-open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.6);
                backdrop-filter: blur(4px);
                z-index: 40;
            }
        }

        /* Body scroll lock */
        body.sidebar-open { overflow: hidden; }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    
    {{-- Custom Member Head Code from Settings --}}
    {!! setting('member_head_code', '') !!}
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">

{{-- Impersonation Banner (Admin logged in as another user) --}}
@if(session()->has('impersonating_from_admin_id'))
<div class="bg-amber-500 text-white py-2 px-4 text-center fixed top-0 left-0 right-0 z-50 shadow-lg" style="z-index:9999">
    <div class="flex items-center justify-center gap-2 flex-wrap text-sm">
        <span class="material-symbols-outlined text-base">visibility</span>
        <span class="font-medium">Logged in as: <strong>{{ Auth::user()->name }} ({{ Auth::user()->email }})</strong></span>
        <a href="{{ route('admin.stop-impersonation') }}" class="bg-white text-amber-600 px-3 py-0.5 rounded-md font-semibold hover:bg-amber-100 transition-colors text-sm">
            ← Return to Admin Panel
        </a>
    </div>
</div>
<style>
    body { padding-top: 44px; }
</style>
@endif

{{-- Admin Message Modal --}}
<livewire:user.admin-message-modal />

{{-- Deactivated Account Modal (Non-closeable) --}}
<livewire:user.deactivated-account-modal />

{{-- Telegram Traffic Bonus Modal (Post-tutorial) --}}
<livewire:user.telegram-bonus-modal />

{{-- ═══════════════════════════════════
     ROOT LAYOUT WRAPPER
═══════════════════════════════════ --}}
<div class="flex min-h-screen"
     x-data="{ sidebarOpen: false }"
     x-init="$watch('sidebarOpen', v => document.body.classList.toggle('sidebar-open', v))"
     @keydown.escape.window="sidebarOpen = false">

    {{-- ── Backdrop (mobile only, CSS controlled) ── --}}
    <div id="sidebar-backdrop"
         :class="{ 'sidebar-open': sidebarOpen }"
         @click="sidebarOpen = false"></div>

    {{-- ═══════ SIDEBAR ═══════ --}}
    <aside
        id="dashboard-aside"
        :class="{ 'sidebar-open': sidebarOpen }"
        class="w-64 bg-card-light dark:bg-card-dark flex flex-col p-4
               border-r border-border-light dark:border-border-dark"
    >
        {{-- Logo + Close button --}}
        <div class="flex items-center justify-between px-2 py-2 mb-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 min-w-0" @click="sidebarOpen = false">
                <span class="material-symbols-outlined text-primary text-3xl flex-shrink-0">link</span>
                <h1 class="text-lg font-bold text-heading-light dark:text-heading-dark truncate">{{ setting('site_name', config('app.name', 'Linkly')) }}</h1>
            </a>
            {{-- Close button - mobile only --}}
            <button
                @click="sidebarOpen = false"
                class="lg:hidden flex-shrink-0 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark"
                aria-label="Close menu"
            >
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-grow">
            <ul class="space-y-0.5">
                <li>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}"
                       href="{{ route('dashboard') }}" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a data-tutorial="nav-links"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('user.links.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}"
                       href="{{ route('user.links.index') }}" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined">link</span>
                        <span>Links</span>
                    </a>
                    <ul class="pl-4 mt-0.5 space-y-0.5">
                        <li>
                            <a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.hidden-links.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}"
                               href="{{ route('user.hidden-links.index') }}" @click="sidebarOpen = false">
                                <span class="material-symbols-outlined text-base">visibility_off</span>
                                <span>Hidden Links</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a data-tutorial="nav-withdrawals"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('user.withdrawals') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}"
                       href="{{ route('user.withdrawals') }}" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined">payments</span>
                        <span>Withdrawals</span>
                    </a>
                </li>
                <li>
                    <a data-tutorial="nav-tools"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('user.tools') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}"
                       href="{{ route('user.tools') }}" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined">construction</span>
                        <span>Tools</span>
                    </a>
                </li>
                <li>
                    <a data-tutorial="nav-campaigns"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('user.ads.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}"
                       href="{{ route('user.ads.index') }}" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined">campaign</span>
                        <span>Campaigns</span>
                    </a>
                </li>
                <li>
                    <a data-tutorial="nav-referrals"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('user.referrals') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}"
                       href="{{ route('user.referrals') }}" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined">group</span>
                        <span>Referrals</span>
                    </a>
                </li>
                <li>
                    <a data-tutorial="nav-reports"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('user.reports') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}"
                       href="{{ route('user.reports') }}" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined">analytics</span>
                        <span>Reports</span>
                    </a>
                </li>

                {{-- Gamification Dropdown --}}
                <li data-tutorial="nav-gamification"
                    x-data="{ open: {{ request()->routeIs('user.daily-spin') || request()->routeIs('user.mystery-boxes') || request()->routeIs('user.competition') || request()->routeIs('user.battle-pass') || request()->routeIs('user.teams') || request()->routeIs('user.vip') || request()->routeIs('user.achievements') || request()->routeIs('user.leaderboard') || request()->routeIs('user.inventory') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark {{ request()->routeIs('user.daily-spin') || request()->routeIs('user.mystery-boxes') || request()->routeIs('user.competition') || request()->routeIs('user.battle-pass') || request()->routeIs('user.teams') || request()->routeIs('user.vip') || request()->routeIs('user.achievements') || request()->routeIs('user.leaderboard') || request()->routeIs('user.inventory') ? 'bg-blue-50 dark:bg-blue-900/30' : '' }}">
                        <span class="flex items-center gap-3">
                            <span class="material-symbols-outlined">emoji_events</span>
                            <span>Gamification</span>
                        </span>
                        <span class="material-symbols-outlined text-base transition-transform duration-200" :class="{ 'rotate-180': open }">expand_more</span>
                    </button>
                    <ul x-cloak x-show="open" x-collapse class="mt-0.5 ml-4 space-y-0.5 border-l-2 border-gray-200 dark:border-gray-700 pl-2">
                        <li><a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.daily-spin') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.daily-spin') }}" @click="sidebarOpen = false"><span class="material-symbols-outlined text-base">casino</span>Daily Spin</a></li>
                        <li><a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.mystery-boxes') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.mystery-boxes') }}" @click="sidebarOpen = false"><span class="material-symbols-outlined text-base">redeem</span>Mystery Boxes</a></li>
                        <li><a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.competition') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.competition') }}" @click="sidebarOpen = false"><span class="material-symbols-outlined text-base">trophy</span>Competition</a></li>
                        <li><a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.battle-pass') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.battle-pass') }}" @click="sidebarOpen = false"><span class="material-symbols-outlined text-base">military_tech</span>Battle Pass</a></li>
                        <li><a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.teams') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.teams') }}" @click="sidebarOpen = false"><span class="material-symbols-outlined text-base">groups</span>Teams</a></li>
                        <li><a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.vip') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.vip') }}" @click="sidebarOpen = false"><span class="material-symbols-outlined text-base">star</span>VIP Status</a></li>
                        <li><a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.achievements') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.achievements') }}" @click="sidebarOpen = false"><span class="material-symbols-outlined text-base">emoji_events</span>Achievements</a></li>
                        <li><a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.leaderboard') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.leaderboard') }}" @click="sidebarOpen = false"><span class="material-symbols-outlined text-base">leaderboard</span>Leaderboard</a></li>
                        <li><a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.inventory') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}" href="{{ route('user.inventory') }}" @click="sidebarOpen = false"><span class="material-symbols-outlined text-base">inventory_2</span>Inventory</a></li>
                    </ul>
                </li>

                {{-- Help & Support Dropdown --}}
                <li x-data="{ open: {{ request()->routeIs('user.contact') || request()->routeIs('feedback.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark {{ request()->routeIs('user.contact') || request()->routeIs('feedback.*') ? 'bg-blue-50 dark:bg-blue-900/30' : '' }}">
                        <span class="flex items-center gap-3">
                            <span class="material-symbols-outlined">help</span>
                            <span>Help & Support</span>
                        </span>
                        <span class="material-symbols-outlined text-base transition-transform duration-200" :class="{ 'rotate-180': open }">expand_more</span>
                    </button>
                    <ul x-cloak x-show="open" x-collapse class="mt-0.5 ml-4 space-y-0.5 border-l-2 border-gray-200 dark:border-gray-700 pl-2">
                        <li>
                            <a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark"
                               href="https://help.mrshort.io" target="_blank" @click="sidebarOpen = false">
                                <span class="material-symbols-outlined text-base">menu_book</span>
                                <span>Knowledge Base</span>
                                <span class="material-symbols-outlined text-xs ml-auto opacity-40">open_in_new</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('feedback.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}"
                               href="{{ route('feedback.index') }}" @click="sidebarOpen = false">
                                <span class="material-symbols-outlined text-base">lightbulb</span>
                                <span>Feature Requests</span>
                            </a>
                        </li>
                        <li>
                            <a data-tutorial="nav-contact"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.contact') ? 'bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark' }}"
                               href="{{ route('user.contact') }}" @click="sidebarOpen = false">
                                <span class="material-symbols-outlined text-base">mail</span>
                                <span>Contact Us</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        {{-- Bottom: Settings + Logout --}}
        <div class="border-t border-border-light dark:border-border-dark pt-3 mt-2 space-y-0.5">
            <a data-tutorial="nav-settings"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark"
               href="{{ route('user.settings') }}" @click="sidebarOpen = false">
                <span class="material-symbols-outlined">settings</span>
                <span>Settings</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark w-full">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ═══════ MAIN AREA ═══════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- ── Top Header Bar ── --}}
        @if (isset($header))
            {{ $header }}
        @else
        <header data-tutorial="header"
                class="sticky top-0 z-20 bg-card-light dark:bg-card-dark border-b border-border-light dark:border-border-dark px-4 lg:px-6 py-3 flex items-center justify-between gap-3 shrink-0 shadow-sm">

            {{-- Left: Hamburger + Page Title --}}
            <div class="flex items-center gap-3 min-w-0">
                {{-- Hamburger - mobile only --}}
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden flex-shrink-0 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark transition-colors"
                    aria-label="Toggle navigation menu"
                >
                    <span class="material-symbols-outlined">menu</span>
                </button>

                {{-- Title --}}
                <div class="min-w-0">
                    <h2 class="text-base lg:text-2xl font-bold text-heading-light dark:text-heading-dark leading-tight truncate">Dashboard Overview</h2>
                    <p class="text-xs text-text-light dark:text-text-dark hidden md:block">Welcome back, let's see your progress!</p>
                </div>
            </div>

            {{-- Right: Notifications + User + Admin link --}}
            <div class="flex items-center gap-2 lg:gap-3 flex-shrink-0">
                <livewire:user.notifications />

                {{-- User Avatar + Info --}}
                <div class="flex items-center gap-2">
                    <img alt="User avatar"
                         class="w-8 h-8 lg:w-10 lg:h-10 rounded-full flex-shrink-0 ring-2 ring-primary/20"
                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=40&background=0D8ABC&color=fff"/>
                    <div class="hidden sm:block leading-tight">
                        <p class="font-semibold text-heading-light dark:text-heading-dark text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-text-light dark:text-text-dark">Balance: ${{ number_format(Auth::user()->balance, 5) }}</p>
                    </div>
                </div>

                {{-- Admin Panel Button --}}
                @if(auth()->user() && auth()->user()->hasRole('admin'))
                    <a href="{{ route('filament.admin.pages.dashboard') }}"
                       class="hidden sm:inline-flex items-center px-3 py-1.5 bg-primary text-white rounded-lg hover:bg-blue-700 text-xs font-bold transition-colors gap-1">
                        <span class="material-symbols-outlined text-sm">admin_panel_settings</span>
                        <span class="hidden md:inline">Admin</span>
                    </a>
                @endif
            </div>
        </header>
        @endif

        {{-- ── Page Content ── --}}
        <main class="flex-1 overflow-auto p-4 md:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- Body scroll lock is handled by x-effect on the root div --}}

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
