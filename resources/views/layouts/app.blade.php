<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>LinkShortener - Dashboard</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com" rel="preconnect"/>
        <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
        <script>
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "primary": "#0f66bd",
                            "background-light": "#f6f7f8",
                            "background-dark": "#101922",
                        },
                        fontFamily: {
                            "display": ["Inter", "sans-serif"]
                        },
                        borderRadius: {
                            "DEFAULT": "0.25rem",
                            "lg": "0.5rem",
                            "xl": "0.75rem",
                            "full": "9999px"
                        },
                    },
                },
            }
        </script>
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
        <div class="relative flex h-auto min-h-screen w-full flex-col">
            <header class="sticky top-0 z-10 flex items-center justify-between whitespace-nowrap border-b border-gray-200 bg-background-light/80 px-4 py-3 backdrop-blur-sm dark:border-gray-800 dark:bg-background-dark/80 sm:px-6 md:px-8">
                <div class="flex items-center gap-4">
                    <div class="h-6 w-6 text-primary">
                        <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold leading-tight tracking-[-0.015em] text-gray-900 dark:text-white">LinkShortener</h2>
                </div>
                <div class="flex flex-1 items-center justify-end gap-4 sm:gap-6">
                    <nav class="hidden items-center gap-6 md:flex">
                        <a class="text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary" href="#">Dashboard</a>
                        <a class="text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary" href="#">Analytics</a>
                        <a class="text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary" href="#">Settings</a>
                    </nav>
                    <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
                        <span class="truncate">Admin Panel</span>
                    </button>
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="User avatar with an abstract colorful background" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDhMGZSUfVTis0d6mVvSpe0WXV9eL-4YceMbiU_Vx6NB7VUXEIWfZ73eOyhp14wW6T1yATsSLKZmKvxaXlofPAddDt9mLIsInA1WIrZQPSTj0K8Na7AQesHEO9alTwf_RJa5OVMwwWVNlabUkNb29hRMN4F3oGOETTOV0rzwuZdct7zO-RDQTL9xhxqTPyfLpc7yuOCbOJJ2PYlVpvwulG3C47CWDSQrhdwlQfUMwYRbxsP84JZJImLDifHstlYcAEWZglU40RfivqY");'></div>
                </div>
            </header>
            <main class="flex h-full grow flex-col">
                {{ $slot }}
            </main>
        </div>


        @livewireScripts
    </body>
</html>
