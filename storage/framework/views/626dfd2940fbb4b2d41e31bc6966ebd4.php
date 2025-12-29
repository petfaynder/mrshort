<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <!-- Total Views Card -->
    <div class="bg-card-light dark:bg-card-dark p-4 rounded-xl shadow-md relative group">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg shrink-0">
                <span class="material-symbols-outlined text-primary text-2xl">visibility</span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1">
                    <h3 class="text-sm font-medium text-text-light dark:text-text-dark truncate">Total Views</h3>
                    <div class="relative shrink-0">
                        <span class="material-symbols-outlined text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-base cursor-help">info</span>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap z-50 shadow-lg">
                            Total clicks on all your links
                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </div>
                </div>
                <p class="text-xl font-bold text-heading-light dark:text-heading-dark"><?php echo e(number_format($totalViews)); ?></p>
            </div>
        </div>
    </div>

    <!-- Paid Views Card -->
    <div class="bg-card-light dark:bg-card-dark p-4 rounded-xl shadow-md relative group">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg shrink-0">
                <span class="material-symbols-outlined text-emerald-500 text-2xl">monetization_on</span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1">
                    <h3 class="text-sm font-medium text-text-light dark:text-text-dark truncate">Paid Views</h3>
                    <div class="relative shrink-0">
                        <span class="material-symbols-outlined text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-base cursor-help">info</span>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap z-50 shadow-lg">
                            Views that generated earnings
                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </div>
                </div>
                <p class="text-xl font-bold text-heading-light dark:text-heading-dark"><?php echo e(number_format($paidViews)); ?></p>
            </div>
        </div>
    </div>

    <!-- Total Earnings Card -->
    <div class="bg-card-light dark:bg-card-dark p-4 rounded-xl shadow-md relative group">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg shrink-0">
                <span class="material-symbols-outlined text-green-500 text-2xl">paid</span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1">
                    <h3 class="text-sm font-medium text-text-light dark:text-text-dark truncate">Total Earnings</h3>
                    <div class="relative shrink-0">
                        <span class="material-symbols-outlined text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-base cursor-help">info</span>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap z-50 shadow-lg">
                            Total earnings this month
                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </div>
                </div>
                <p class="text-xl font-bold text-heading-light dark:text-heading-dark">$<?php echo e(number_format($publisherEarnings, 2)); ?></p>
            </div>
        </div>
    </div>

    <!-- Referral Earnings Card -->
    <div class="bg-card-light dark:bg-card-dark p-4 rounded-xl shadow-md relative group">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg shrink-0">
                <span class="material-symbols-outlined text-yellow-500 text-2xl">group</span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1">
                    <h3 class="text-sm font-medium text-text-light dark:text-text-dark truncate">Referral Earnings</h3>
                    <div class="relative shrink-0">
                        <span class="material-symbols-outlined text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-base cursor-help">info</span>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap z-50 shadow-lg">
                            Commission from referred users
                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </div>
                </div>
                <p class="text-xl font-bold text-heading-light dark:text-heading-dark">$<?php echo e(number_format($referralEarnings, 2)); ?></p>
            </div>
        </div>
    </div>

    <!-- Average CPM Card -->
    <div class="bg-card-light dark:bg-card-dark p-4 rounded-xl shadow-md relative group">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg shrink-0">
                <span class="material-symbols-outlined text-purple-500 text-2xl">monitoring</span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1">
                    <h3 class="text-sm font-medium text-text-light dark:text-text-dark truncate">Average CPM</h3>
                    <div class="relative shrink-0">
                        <span class="material-symbols-outlined text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-base cursor-help">info</span>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap z-50 shadow-lg">
                            Earnings per 1000 paid views
                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </div>
                </div>
                <p class="text-xl font-bold text-heading-light dark:text-heading-dark">$<?php echo e(number_format($averageCpm, 2)); ?></p>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/dashboard-stats.blade.php ENDPATH**/ ?>