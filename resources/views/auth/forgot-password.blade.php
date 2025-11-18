<!DOCTYPE html>
<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Forgot Password - LinkShortener</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0d7ff2",
                        "background-light": "#f5f7f8",
                        "background-dark": "#101922",
                        "danger": "#ef4444",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "1rem",
                        "xl": "1.5rem",
                        "full": "9999px"
                    },
                    animation: {
                        'gradient-x': 'gradient-x 10s ease infinite',
                        'float-1': 'float 8s ease-in-out infinite',
                        'float-2': 'float 10s ease-in-out infinite 1s',
                        'float-3': 'float 12s ease-in-out infinite 2s',
                    },
                    keyframes: {
                        'gradient-x': {
                            '0%, 100%': {
                                'background-size': '200% 200%',
                                'background-position': 'left center'
                            },
                            '50%': {
                                'background-size': '200% 200%',
                                'background-position': 'right center'
                            }
                        },
                        'float': {
                            '0%': {
                                transform: 'translatey(0px)'
                            },
                            '50%': {
                                transform: 'translatey(-20px)'
                            },
                            '100%': {
                                transform: 'translatey(0px)'
                            }
                        }
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .error-message {
            display: none;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #fff;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body class="font-display">
<div class="relative flex min-h-screen w-full flex-col bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
<div class="flex flex-1">
<div class="flex w-full flex-col md:flex-row">
<div class="flex w-full flex-col items-center justify-center bg-[#f5f7f8] p-6 dark:bg-[#101922] md:w-1/2">
<div class="flex w-full max-w-sm flex-col justify-center">
<header class="mb-10 flex w-full items-center gap-4 text-[#101922] dark:text-white">
<div class="size-6 text-primary">
<svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z" fill="currentColor"></path>
</svg>
</div>
<h2 class="text-xl font-bold leading-tight tracking-[-0.015em] text-[#101922] dark:text-white">LinkShortener</h2>
</header>
<div class="mb-8 flex flex-col gap-2">
<p class="text-3xl font-bold leading-tight tracking-[-0.033em] text-[#101922] dark:text-white md:text-4xl">Reset Your Password</p>
<p class="text-base font-normal leading-normal text-gray-600 dark:text-[#90adcb]">Enter the email address associated with your account, and we'll send you a link to reset your password.</p>
</div>

@if (session('status'))
    <div class="mb-4 items-center gap-3 rounded-lg border border-primary/50 bg-primary/10 p-3 text-sm text-primary dark:text-blue-400" style="display: flex;">
        <span class="material-symbols-outlined">check_circle</span>
        <p>{{ session('status') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 items-center gap-3 rounded-lg border border-danger/50 bg-danger/10 p-3 text-sm text-danger dark:text-red-400" style="display: flex;">
        <span class="material-symbols-outlined">error</span>
        <p>Please fix the errors below to continue.</p>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
    @csrf
    <label class="flex flex-col">
        <p class="pb-2 text-sm font-medium leading-normal text-[#101922] dark:text-white">Email Address</p>
        <div class="relative w-full">
            <input name="email" class="form-input h-12 w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg border {{ $errors->has('email') ? 'border-danger bg-danger/10' : 'border-gray-300 bg-white dark:border-[#314d68] dark:bg-[#182634]' }} p-3 text-base font-normal leading-normal text-gray-900 placeholder:text-gray-400 focus:border-primary focus:outline-0 focus:ring-2 focus:ring-primary/50 dark:text-white dark:placeholder:text-[#90adcb] dark:focus:border-primary" placeholder="e.g., yourname@email.com" type="email" value="{{ old('email') }}" required autofocus/>
            @if ($errors->has('email'))
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-danger">error</span>
            @endif
        </div>
    </label>
    @if ($errors->has('email'))
        <p class="error-message flex items-center gap-1.5 mt-1.5 text-sm text-danger dark:text-red-400" style="display: flex;">
            <span class="material-symbols-outlined text-base">arrow_right_alt</span>
            <span>{{ $errors->first('email') }}</span>
        </p>
    @endif
    <button class="flex h-12 min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg bg-primary px-5 text-base font-bold leading-normal tracking-[0.015em] text-white transition-colors hover:bg-primary/90" onclick="this.form.submit(); this.disabled = true; this.innerHTML = '<span class="spinner"></span><span class="ml-2">Sending Link...</span>';">
        <span class="truncate">Send Reset Link</span>
    </button>
</form>
<p class="mt-8 text-center text-sm font-normal leading-normal text-gray-600 dark:text-[#90adcb]">Remember your password? <a class="font-medium text-primary underline-offset-2 hover:underline" href="{{ route('login') }}">Log In</a></p>
</div>
</div>
<div class="relative hidden w-1/2 flex-col items-center justify-center overflow-hidden bg-[#101922] p-10 md:flex">
<div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-background-dark to-background-dark"></div>
<div class="absolute inset-0 z-0 opacity-20 [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]">
<svg aria-hidden="true" class="absolute inset-0 h-full w-full">
<defs>
<pattern height="64" id="grid-pattern" patternUnits="userSpaceOnUse" width="64" x="50%" y="50%">
<path d="M64 0H0V64" fill="none" stroke="currentColor" stroke-width="0.5"></path>
</pattern>
</defs>
<rect class="text-primary/30" fill="url(#grid-pattern)" height="100%" width="100%"></rect>
</svg>
</div>
<div class="relative z-10 flex flex-col items-center text-center">
<div class="relative mb-8 flex h-56 w-56 items-center justify-center">
<div class="absolute inset-0 animate-[spin_20s_linear_infinite] rounded-full border border-dashed border-primary/20"></div>
<div class="absolute inset-4 animate-[spin_25s_linear_infinite_reverse] rounded-full border border-dashed border-primary/30"></div>
<div class="absolute inset-8 animate-[spin_30s_linear_infinite] rounded-full border border-dashed border-primary/40"></div>
<div class="animate-float-1 absolute flex h-40 w-40 items-center justify-center rounded-full bg-primary/10 backdrop-blur-sm">
<span class="material-symbols-outlined text-7xl text-primary opacity-80">lock_reset</span>
</div>
</div>
<h3 class="text-3xl font-bold text-white">Secure Password Recovery</h3>
<p class="mt-2 max-w-sm text-base text-[#90adcb]">We're guiding your access back. A secure link is on its way to your inbox.</p>
</div>
<div class="absolute -bottom-10 -left-10 z-10 size-32 rounded-full bg-primary/20 blur-3xl animate-float-2"></div>
<div class="absolute -right-16 top-16 z-10 size-40 rounded-full bg-primary/10 blur-3xl animate-float-3"></div>
</div>
</div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Generic form submission loading animation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner"></span><span class="ml-2">Processing...</span>';
                }
            });
        });
    });
</script>
</body></html>
