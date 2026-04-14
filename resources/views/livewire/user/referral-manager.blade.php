<div>
    @if(!$referralsEnabled)
    <div class="bg-yellow-900/30 border border-yellow-600 rounded-lg p-6 text-center">
        <span class="material-symbols-outlined text-4xl text-yellow-500 mb-3">info</span>
        <h3 class="text-lg font-semibold text-yellow-400 mb-2">Referral System Disabled</h3>
        <p class="text-gray-400">The referral system is currently disabled by the administrator.</p>
    </div>
    @else
    {{-- Filter Bar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light dark:text-text-dark">search</span>
            <input wire:model.live.debounce.300ms="search" class="w-full h-10 pl-10 pr-4 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Search by username..." type="text"/>
        </div>
        <select wire:model.live="statusFilter" class="w-full sm:w-auto h-10 px-3 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Filter by Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto rounded-lg border border-border-light dark:border-border-dark">
        <table class="min-w-full divide-y divide-border-light dark:divide-border-dark">
            <thead class="bg-background-light dark:bg-background-dark">
            <tr>
                <th wire:click="sortByColumn('name')" class="px-4 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider cursor-pointer hover:text-primary" scope="col">
                    Username
                    @if($sortBy === 'name')
                        <span class="material-symbols-outlined text-xs align-middle">{{ $sortDirection === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                    @endif
                </th>
                <th wire:click="sortByColumn('created_at')" class="px-4 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider cursor-pointer hover:text-primary" scope="col">
                    Joined
                    @if($sortBy === 'created_at')
                        <span class="material-symbols-outlined text-xs align-middle">{{ $sortDirection === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                    @endif
                </th>
                <th class="px-4 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Earnings</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Last Activity</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Status</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-border-light dark:divide-border-dark">
            @forelse($referredUsers as $referredUser)
                @php
                    $earnings = $this->getReferralEarningForUser($referredUser);
                    $lastActivity = $referredUser->last_login_at;
                    $isActive = $referredUser->links()->exists();
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-heading-light dark:text-heading-dark">{{ $referredUser->name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-text-light dark:text-text-dark">{{ $referredUser->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-text-light dark:text-text-dark">${{ number_format($earnings, 2) }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-text-light dark:text-text-dark">
                        @if($lastActivity) {{ $lastActivity->diffForHumans() }} @else - @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        @if($isActive)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">Active</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700/50 text-gray-800 dark:text-gray-300">Inactive</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-text-light dark:text-text-dark">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-4xl text-gray-400">group_off</span>
                            <p class="text-base font-medium">No referrals yet</p>
                            <p class="text-sm">Share your referral link to start earning commissions!</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card Layout --}}
    <div class="md:hidden space-y-3">
        @forelse($referredUsers as $referredUser)
            @php
                $earnings = $this->getReferralEarningForUser($referredUser);
                $lastActivity = $referredUser->last_login_at;
                $isActive = $referredUser->links()->exists();
            @endphp
            <div class="rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-semibold text-sm text-heading-light dark:text-heading-dark">{{ $referredUser->name }}</span>
                    @if($isActive)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">Active</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700/50 text-gray-800 dark:text-gray-300">Inactive</span>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs text-text-light dark:text-text-dark">
                    <div><span class="font-medium">Joined:</span> {{ $referredUser->created_at->format('Y-m-d') }}</div>
                    <div><span class="font-medium">Earnings:</span> ${{ number_format($earnings, 2) }}</div>
                    <div class="col-span-2"><span class="font-medium">Last seen:</span> {{ $lastActivity ? $lastActivity->diffForHumans() : '-' }}</div>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-border-light dark:border-border-dark p-8 text-center">
                <span class="material-symbols-outlined text-4xl text-gray-400">group_off</span>
                <p class="text-sm font-medium mt-2 text-heading-light dark:text-heading-dark">No referrals yet</p>
                <p class="text-xs text-text-light dark:text-text-dark mt-1">Share your referral link to start earning commissions!</p>
            </div>
        @endforelse
    </div>

    @if($referredUsers->count() > 0)
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pt-4">
        <p class="text-sm text-text-light dark:text-text-dark">
            Showing <span class="font-medium">{{ $referredUsers->count() }}</span> referral(s)
        </p>
        <p class="text-sm text-text-light dark:text-text-dark">
            Total Earnings: <span class="font-bold text-green-600 dark:text-green-400">${{ number_format($totalReferralEarnings, 2) }}</span>
        </p>
    </div>
    @endif
    @endif
</div>
