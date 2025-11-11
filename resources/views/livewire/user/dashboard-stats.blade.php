<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md flex items-center gap-4">
        <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
            <span class="material-symbols-outlined text-primary text-3xl">visibility</span>
        </div>
        <div>
            <h3 class="text-lg font-medium text-text-light dark:text-text-dark">Total Views</h3>
            <p class="text-2xl font-bold text-heading-light dark:text-heading-dark">{{ $totalViews }}</p>
        </div>
    </div>
    <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md flex items-center gap-4">
        <div class="p-3 bg-green-100 dark:bg-green-900/50 rounded-lg">
            <span class="material-symbols-outlined text-green-500 text-3xl">paid</span>
        </div>
        <div>
            <h3 class="text-lg font-medium text-text-light dark:text-text-dark">Total Earnings</h3>
            <p class="text-2xl font-bold text-heading-light dark:text-heading-dark">${{ number_format($publisherEarnings, 2) }}</p>
        </div>
    </div>
    <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md flex items-center gap-4">
        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg">
            <span class="material-symbols-outlined text-yellow-500 text-3xl">group</span>
        </div>
        <div>
            <h3 class="text-lg font-medium text-text-light dark:text-text-dark">Referral Earnings</h3>
            <p class="text-2xl font-bold text-heading-light dark:text-heading-dark">${{ number_format($referralEarnings, 2) }}</p>
        </div>
    </div>
    <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md flex items-center gap-4">
        <div class="p-3 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
            <span class="material-symbols-outlined text-purple-500 text-3xl">monitoring</span>
        </div>
        <div>
            <h3 class="text-lg font-medium text-text-light dark:text-text-dark">Average CPM</h3>
            <p class="text-2xl font-bold text-heading-light dark:text-heading-dark">${{ number_format($averageCpm, 2) }}</p>
        </div>
    </div>
</div>
