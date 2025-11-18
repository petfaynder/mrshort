<!DOCTYPE html>
<html class="dark" lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Achievements Collection</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "Noto Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.5rem", "lg": "1rem", "xl": "1.5rem", "full": "9999px"},
                    keyframes: {
                        pulseGold: {
                            '0%, 100%': {boxShadow: '0 0 15px 5px rgba(255, 215, 0, 0.4), 0 0 5px 1px rgba(255, 215, 0, 0.6)'},
                            '50%': {boxShadow: '0 0 25px 8px rgba(255, 215, 0, 0.6), 0 0 10px 2px rgba(255, 215, 0, 0.8)'},
                        },
                        shine: {
                            '0%': {'background-position': '-200% 0'},
                            '100%': {'background-position': '200% 0'},
                        }
                    },
                    animation: {
                        'pulse-gold': 'pulseGold 4s ease-in-out infinite',
                        'shine': 'shine 3s linear infinite',
                    },
                },
            },
        }
    </script>
    <style>
        .achieved-glow-common {
            box-shadow: 0 0 10px 2px rgba(66, 153, 225, 0.3);
        }

        .achieved-glow-rare {
            box-shadow: 0 0 15px 5px rgba(192, 192, 192, 0.3), 0 0 5px 1px rgba(226, 232, 240, 0.5);
        }

        .achieved-glow-legendary {
            box-shadow: 0 0 15px 5px rgba(255, 215, 0, 0.4), 0 0 5px 1px rgba(255, 215, 0, 0.6);
        }

        .locked-card {
            filter: grayscale(80%) brightness(0.7);
        }

        .featured-shine {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            background-size: 200% 100%;
        }
    </style>
    @livewireStyles
</head>
<body class="bg-background-light dark:bg-background-dark font-display">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#233648] px-10 py-3 sticky top-0 bg-background-dark/80 backdrop-blur-sm z-10">
            <div class="flex items-center gap-4 text-white">
                <div class="size-6 text-primary">
                    <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z" fill="currentColor"></path>
                    </svg>
                </div>
                <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">LinkShortener</h2>
            </div>
            <div class="flex flex-1 justify-center gap-8">
                <div class="flex items-center gap-9">
                    <a class="text-slate-300 hover:text-white text-sm font-medium leading-normal" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="text-slate-300 hover:text-white text-sm font-medium leading-normal" href="{{ route('user.links.index') }}">Links</a>
                    <a class="text-slate-300 hover:text-white text-sm font-medium leading-normal" href="{{ route('user.reports') }}">Analytics</a>
                    <a class="text-primary text-sm font-bold leading-normal" href="{{ route('user.achievements') }}">Achievements</a>
                </div>
            </div>
            <div class="flex gap-2 items-center">
                <button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 bg-[#233648] text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
                    <span class="material-symbols-outlined text-white" style="font-size: 20px;">notifications</span>
                </button>
                <button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 bg-[#233648] text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
                    <span class="material-symbols-outlined text-white" style="font-size: 20px;">settings</span>
                </button>
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="User profile picture" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC5PPxP1YHuqDaTVaNrf_-99GAPCh1-y87d0rlwh9DidI_wnLieeYcKMvNxGTGgKrv9qOJz9ISxt_JHGcIZU7NkFZrl38dyOJtijZs9E0fEJm7RisEkPAG7cAzkP832Dcb4acvraAoe26ll7wn-8zJAEZUXSAUzoXFayE3YksTlvfbhUPzXijslt1wHuuPVSZxPl-Av8Qb0eXOwqrYHcUwndyl93KiQIURYAHyscThy-LRoWw4tZq5lnXR5Hnq-CuXvIzyyz_cP5MKY");'></div>
            </div>
        </header>
        <main class="px-4 sm:px-10 lg:px-20 xl:px-40 flex flex-1 justify-center py-10">
            {{ $slot }}
        </main>
    </div>
</div>
@livewireScripts
</body>
</html>
