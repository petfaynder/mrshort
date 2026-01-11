<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="<?php echo e(auth()->user()->theme_preference ?? 'dark'); ?>">
<head>
    <meta charset="utf-8">
    <script>
        // Theme handling same as dashboard
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
        <?php if(auth()->check() && auth()->user()->theme_preference): ?>
            if ("<?php echo e(auth()->user()->theme_preference); ?>" === 'dark') {
                 document.documentElement.classList.add('dark');
                 localStorage.theme = 'dark';
            } else {
                 document.documentElement.classList.remove('dark');
                 localStorage.theme = 'light';
            }
        <?php endif; ?>
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>MRShort - Feedback</title>

    <!-- Fonts & Icons - Same as Dashboard -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


    <style>
        [x-cloak] { display: none !important; }
        /* Button hover spin effect */
        .btn-spin:hover .material-symbols-outlined,
        .btn-spin:hover .btn-icon {
            animation: spin-once 0.5s ease-in-out;
        }
        @keyframes spin-once {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* Vote button pulse */
        .vote-btn:hover {
            transform: scale(1.05);
        }
        .vote-btn:active {
            transform: scale(0.95);
        }
        .vote-btn {
            transition: all 0.2s ease;
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark overflow-hidden">
    <div class="h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-card-light dark:bg-card-dark border-b border-border-light dark:border-border-dark shrink-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-14">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="<?php echo e(route('feedback.index')); ?>" class="flex items-center gap-2 group">
                                <span class="material-symbols-outlined text-primary text-2xl group-hover:animate-spin" style="animation-duration: 0.5s;">link</span>
                                <span class="font-bold text-lg text-heading-light dark:text-heading-dark">MRShort <span class="text-primary font-normal">Feedback</span></span>
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="https://help.mrshort.io" target="_blank" class="btn-spin text-sm text-text-light hover:text-heading-light dark:text-text-dark dark:hover:text-heading-dark flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">menu_book</span>
                            Help
                        </a>
                        <a href="<?php echo e(route('dashboard')); ?>" class="btn-spin text-sm text-text-light hover:text-heading-light dark:text-text-dark dark:hover:text-heading-dark flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">dashboard</span>
                            Dashboard
                        </a>
                        
                        <?php if(auth()->guard()->check()): ?>
                            <div class="flex items-center gap-2 ml-2">
                                <img alt="User avatar" class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(auth()->user()->name)); ?>&size=32&background=0D8ABC&color=fff"/>
                                <span class="text-sm font-medium text-heading-light dark:text-heading-dark"><?php echo e(auth()->user()->name); ?></span>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-sm font-medium text-primary hover:text-blue-700">Log in</a>
                            <a href="<?php echo e(route('register')); ?>" class="ml-2 text-sm font-medium text-text-light hover:text-heading-light dark:text-text-dark">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content - Scrollable -->
        <main class="flex-1 overflow-auto">
            <?php echo e($slot); ?>

        </main>

        <!-- Footer -->
        <footer class="bg-card-light dark:bg-card-dark border-t border-border-light dark:border-border-dark py-4 shrink-0">
            <div class="max-w-7xl mx-auto px-4 text-center text-sm text-text-light dark:text-text-dark">
                &copy; <?php echo e(date('Y')); ?> MRShort. All rights reserved.
            </div>
        </footer>
    </div>
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/layouts/feedback.blade.php ENDPATH**/ ?>