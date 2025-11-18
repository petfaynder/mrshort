<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sign Up - Monetize Your Links</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <style type="text/tailwindcss">
        .password-strength-bar > div {
            transition: width 0.3s ease-in-out;
        }
        @keyframes form-item-fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-form-item {
            animation: form-item-fade-in 0.5s ease-out forwards;
        }
        .error-message {
            display: none;
        }
        @keyframes link-flow {
            from {
                stroke-dashoffset: 200;
            }
            to {
                stroke-dashoffset: 0;
            }
        }
        .animate-link-flow {
            stroke-dasharray: 10 10;
            animation: link-flow 3s linear infinite;
        }
        @keyframes data-pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }
        .animate-data-pulse {
            animation: data-pulse 2s ease-in-out infinite;
        }
        @keyframes earnings-rise {
            0% {
                transform: translateY(100%) scaleY(0);
                opacity: 0.5;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateY(0%) scaleY(1);
                opacity: 0;
            }
        }
        .animate-earnings-rise {
            animation: earnings-rise 4s ease-out infinite;
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
                    borderRadius: {"DEFAULT": "0.5rem", "lg": "1rem", "xl": "1.5rem", "full": "9999px"},
                    keyframes: {
                        'float-up': {
                            '0%': { transform: 'translateY(0) scale(0.5)', opacity: '0' },
                            '50%': { opacity: '1' },
                            '100%': { transform: 'translateY(-150px) scale(1)', opacity: '0' },
                        },
                        'pulse-subtle': {
                            '0%, 100%': { transform: 'scale(1)', opacity: '0.5' },
                            '50%': { transform: 'scale(1.05)', opacity: '0.7' },
                        }
                    },
                    animation: {
                        'float-up': 'float-up 6s ease-in-out infinite',
                        'pulse-subtle': 'pulse-subtle 4s ease-in-out infinite',
                    },
                },
            },
        }
    </script>
</head>
<body class="font-display">
<div class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
    <div class="flex flex-1 w-full">
        <div class="flex flex-col w-full lg:w-1/2 items-center justify-center p-6 sm:p-8 md:p-12">
            <div class="flex flex-col max-w-md w-full gap-6 form-item-container">
                <div class="animate-form-item" style="animation-delay: 0.1s;">
                    <p class="text-black dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Create Account</p>
                    <p class="text-slate-500 dark:text-slate-400 text-base mt-2">Join now and start earning.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full animate-form-item" style="animation-delay: 0.2s;">
                    <button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-300 hover:scale-105 active:scale-95">
                        <span class="material-symbols-outlined mr-2">mail</span>
                        <span class="truncate">Sign up with Google</span>
                    </button>
                </div>
                <div class="flex items-center gap-4 animate-form-item" style="animation-delay: 0.3s;">
                    <hr class="w-full border-slate-200 dark:border-slate-700"/>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal text-center">Or</p>
                    <hr class="w-full border-slate-200 dark:border-slate-700"/>
                </div>

                @if (session('status'))
                    <div class="animate-form-item items-center gap-3 rounded-lg border border-primary/50 bg-primary/10 p-3 text-sm text-primary dark:text-blue-400" style="animation-delay: 0.35s; display: flex;">
                        <span class="material-symbols-outlined">check_circle</span>
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="animate-form-item items-center gap-3 rounded-lg border border-danger/50 bg-danger/10 p-3 text-sm text-danger dark:text-red-400" style="animation-delay: 0.35s; display: flex;">
                        <span class="material-symbols-outlined">error</span>
                        <p>Please fix the errors below to continue.</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-4 w-full animate-form-item" style="animation-delay: 0.4s;">
                        <!-- First Name -->
                        <div class="flex flex-col w-full">
                            <label class="flex flex-col w-full">
                                <p class="text-black dark:text-white text-base font-medium leading-normal pb-2">First Name</p>
                                <div class="relative w-full">
                                    <input name="first_name" value="{{ old('first_name') }}" required autofocus autocomplete="given-name" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-black dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('first_name') ? 'border-danger bg-danger/10' : 'border-slate-200 dark:border-slate-700 bg-transparent dark:bg-transparent' }} h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 text-base font-normal leading-normal transition-all duration-300" placeholder="Enter your first name"/>
                                    @if ($errors->has('first_name'))
                                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-danger">error</span>
                                    @endif
                                </div>
                            </label>
                            @if ($errors->has('first_name'))
                                <p class="error-message flex items-center gap-1.5 mt-1.5 text-sm text-danger dark:text-red-400" style="display: flex;">
                                    <span class="material-symbols-outlined text-base">arrow_right_alt</span>
                                    <span>{{ $errors->first('first_name') }}</span>
                                </p>
                            @endif
                        </div>
                        <!-- Last Name -->
                        <div class="flex flex-col w-full">
                            <label class="flex flex-col w-full">
                                <p class="text-black dark:text-white text-base font-medium leading-normal pb-2">Last Name</p>
                                <div class="relative w-full">
                                    <input name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-black dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('last_name') ? 'border-danger bg-danger/10' : 'border-slate-200 dark:border-slate-700 bg-transparent dark:bg-transparent' }} h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 text-base font-normal leading-normal transition-all duration-300" placeholder="Enter your last name"/>
                                    @if ($errors->has('last_name'))
                                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-danger">error</span>
                                    @endif
                                </div>
                            </label>
                            @if ($errors->has('last_name'))
                                <p class="error-message flex items-center gap-1.5 mt-1.5 text-sm text-danger dark:text-red-400" style="display: flex;">
                                    <span class="material-symbols-outlined text-base">arrow_right_alt</span>
                                    <span>{{ $errors->first('last_name') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col w-full animate-form-item" style="animation-delay: 0.5s;">
                        <label class="flex flex-col w-full">
                            <p class="text-black dark:text-white text-base font-medium leading-normal pb-2">Email</p>
                            <div class="relative w-full">
                                <input name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-black dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('email') ? 'border-danger bg-danger/10' : 'border-slate-200 dark:border-slate-700 bg-transparent dark:bg-transparent' }} h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 text-base font-normal leading-normal transition-all duration-300" placeholder="Enter your email"/>
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
                    </div>

                    <!-- Password -->
                    <div class="flex flex-col w-full animate-form-item" style="animation-delay: 0.6s;">
                        <label class="flex flex-col w-full">
                            <p class="text-black dark:text-white text-base font-medium leading-normal pb-2">Password</p>
                            <div class="relative w-full">
                                <input name="password" type="password" required autocomplete="new-password" onkeyup="checkPasswordStrength(event)" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-black dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('password') ? 'border-danger bg-danger/10' : 'border-slate-200 dark:border-slate-700 bg-transparent dark:bg-transparent' }} h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 text-base font-normal leading-normal transition-all duration-300" placeholder="Enter your password"/>
                                @if ($errors->has('password'))
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-danger">error</span>
                                @endif
                            </div>
                        </label>
                        <div class="mt-2 h-1 w-full bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden password-strength-bar">
                            <div class="h-1 rounded-full" id="strength-bar"></div>
                        </div>
                        @if ($errors->has('password'))
                            <p class="error-message flex items-center gap-1.5 mt-1.5 text-sm text-danger dark:text-red-400" style="display: flex;">
                                <span class="material-symbols-outlined text-base">arrow_right_alt</span>
                                <span>{{ $errors->first('password') }}</span>
                            </p>
                        @endif
                    </div>

                    <!-- Confirm Password -->
                    <div class="flex flex-col w-full animate-form-item" style="animation-delay: 0.7s;">
                        <label class="flex flex-col w-full">
                            <p class="text-black dark:text-white text-base font-medium leading-normal pb-2">Confirm Password</p>
                            <div class="relative w-full">
                                <input name="password_confirmation" type="password" required autocomplete="new-password" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-black dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('password_confirmation') ? 'border-danger bg-danger/10' : 'border-slate-200 dark:border-slate-700 bg-transparent dark:bg-transparent' }} h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 text-base font-normal leading-normal transition-all duration-300" placeholder="Confirm your password"/>
                                @if ($errors->has('password_confirmation'))
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-danger">error</span>
                                @endif
                            </div>
                        </label>
                        @if ($errors->has('password_confirmation'))
                            <p class="error-message flex items-center gap-1.5 mt-1.5 text-sm text-danger dark:text-red-400" style="display: flex;">
                                <span class="material-symbols-outlined text-base">arrow_right_alt</span>
                                <span>{{ $errors->first('password_confirmation') }}</span>
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 animate-form-item" style="animation-delay: 0.8s;">
                        <input class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 text-primary focus:ring-primary/50" id="terms-checkbox" type="checkbox" name="terms" required/>
                        <label class="text-sm text-slate-600 dark:text-slate-400" for="terms-checkbox">
                            I agree to the <a class="text-primary hover:underline font-medium" href="#">Terms of Service</a> and <a class="text-primary hover:underline font-medium" href="#">Privacy Policy</a>.
                        </label>
                    </div>

                    <button type="submit" class="flex min-w-[84px] w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-all duration-300 hover:scale-105 active:scale-95 animate-form-item" style="animation-delay: 0.9s;">
                        <span class="truncate">Sign Up</span>
                    </button>
                </form>

                <p class="text-center text-sm text-slate-600 dark:text-slate-400 animate-form-item" style="animation-delay: 1s;">
                    Already have an account? <a class="text-primary hover:underline font-medium" href="{{ route('login') }}">Log In</a>
                </p>
            </div>
        </div>
        <div class="hidden lg:flex w-1/2 bg-slate-900 items-center justify-center p-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(13,127,242,0.15)_0%,_transparent_50%)]"></div>
            <div class="z-10 flex flex-col items-center text-center w-full max-w-lg">
                <div class="relative w-full aspect-square max-w-md">
                    <svg class="w-full h-full" fill="none" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <radialGradient cx="50%" cy="50%" fx="50%" fy="50%" id="glow" r="50%">
                                <stop offset="0%" stop-color="var(--tw-color-primary)" stop-opacity="0.3"></stop>
                                <stop offset="70%" stop-color="var(--tw-color-primary)" stop-opacity="0"></stop>
                                <stop offset="100%" stop-color="var(--tw-color-primary)" stop-opacity="0"></stop>
                            </radialGradient>
                        </defs>
                        <circle cx="150" cy="150" fill="url(#glow)" r="150"></circle>
                        <path class="stroke-primary/20" d="M150 50 v 200 M50 150 h 200" stroke-width="1"></path>
                        <path class="animate-link-flow stroke-primary/50" d="M 50 150 C 100 100, 200 100, 250 150" stroke-width="2"></path>
                        <path class="animate-link-flow stroke-primary/50 [animation-delay:-1.5s]" d="M 150 50 C 200 100, 200 200, 150 250" stroke-width="2"></path>
                        <path class="animate-link-flow stroke-primary/50 [animation-delay:-0.5s]" d="M 250 150 C 200 200, 100 200, 50 150" stroke-width="2"></path>
                        <path class="animate-link-flow stroke-primary/50 [animation-delay:-2s]" d="M 150 250 C 100 200, 100 100, 150 50" stroke-width="2"></path>
                        <circle class="fill-primary/20" cx="50" cy="150" r="4"></circle>
                        <circle class="fill-primary/20" cx="250" cy="150" r="4"></circle>
                        <circle class="fill-primary/20" cx="150" cy="50" r="4"></circle>
                        <circle class="fill-primary/20" cx="150" cy="250" r="4"></circle>
                        <circle class="animate-data-pulse fill-primary" cx="150" cy="150" r="30"></circle>
                        <foreignObject height="60" width="60" x="120" y="120">
                            <div class="flex items-center justify-center h-full w-full text-white">
                                <span class="material-symbols-outlined text-4xl">link</span>
                            </div>
                        </foreignObject>
                    </svg>
                    <div class="absolute bottom-0 left-0 w-full h-1/3 bg-slate-900 [mask-image:linear-gradient(to_top,black,transparent)] flex items-end justify-center pb-2">
                        <div class="h-full w-20 bg-green-500/20 rounded-t-lg relative overflow-hidden">
                            <div class="absolute bottom-0 left-0 w-full h-1/3 bg-green-400 animate-earnings-rise [animation-delay:-2s]"></div>
                            <div class="absolute bottom-0 left-0 w-full h-2/3 bg-green-400 animate-earnings-rise"></div>
                            <div class="absolute bottom-0 left-0 w-full h-1/2 bg-green-400 animate-earnings-rise [animation-delay:-1s]"></div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4 mt-8">
                    <h1 class="text-4xl font-bold text-white tracking-tight">Turn Clicks Into Cash.</h1>
                    <p class="text-slate-300 max-w-md text-lg">Our platform transforms your links into a steady stream of income. Visualize your data translating directly into earnings.</p>
                </div>
            </div>
            <span class="absolute top-8 right-8 text-white font-bold text-xl">LOGO</span>
        </div>
    </div>
</div>
<script>
    const strength = {
        0: "w-0",
        1: "w-1/4",
        2: "w-2/4",
        3: "w-3/4",
        4: "w-full",
    }
    const colors = {
        0: "bg-slate-500",
        1: "bg-red-500",
        2: "bg-yellow-500",
        3: "bg-blue-500",
        4: "bg-green-500",
    }
    function checkPasswordStrength(event) {
        const password = event.target.value;
        let score = 0;
        if (password.length >= 8) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        const bar = document.getElementById('strength-bar');
        if (bar) {
            const classList = Array.from(bar.classList);
            classList.forEach(cls => {
                if (cls.startsWith('w-') || cls.startsWith('bg-')) {
                    bar.classList.remove(cls);
                }
            });
            bar.classList.add(strength[score], colors[score]);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Animate form items on load
        const formItems = document.querySelectorAll('.animate-form-item');
        formItems.forEach((item, index) => {
            item.style.animationDelay = `${0.1 * index}s`;
            // item.style.opacity = 0; // Removed this line
            item.classList.add('animate-form-item');
        });

        // Handle password strength check on initial load if old input exists
        const passwordInput = document.querySelector('input[name="password"]');
        if (passwordInput && passwordInput.value) {
            checkPasswordStrength({ target: passwordInput });
        }

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
</body>
</html>
