<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-border-light dark:border-border-dark">
        <p class="text-base font-medium leading-normal text-text-light dark:text-text-dark">Total Referrals</p>
        <p class="tracking-light text-2xl font-bold leading-tight text-heading-light dark:text-heading-dark">{{ $totalReferrals }}</p>
    </div>
    <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-border-light dark:border-border-dark">
        <p class="text-base font-medium leading-normal text-text-light dark:text-text-dark">Active Referrals</p>
        <p class="tracking-light text-2xl font-bold leading-tight text-heading-light dark:text-heading-dark">{{ $activeReferrals }}</p>
    </div>
    <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-border-light dark:border-border-dark">
        <p class="text-base font-medium leading-normal text-text-light dark:text-text-dark">Total Commission Earned</p>
        <p class="tracking-light text-2xl font-bold leading-tight text-heading-light dark:text-heading-dark">${{ number_format($totalCommissionEarned, 2) }}</p>
    </div>
    <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-border-light dark:border-border-dark">
        <p class="text-base font-medium leading-normal text-text-light dark:text-text-dark">Commission Rate</p>
        <p class="tracking-light text-2xl font-bold leading-tight text-heading-light dark:text-heading-dark">{{ $commissionRate }}%</p>
    </div>
</div>
