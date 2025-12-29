<div>
    @if(!$referralsEnabled)
    <div class="bg-yellow-900/30 border border-yellow-600 rounded-lg p-6 text-center">
        <span class="material-symbols-outlined text-4xl text-yellow-500 mb-3">info</span>
        <h3 class="text-lg font-semibold text-yellow-400 mb-2">Referral System Disabled</h3>
        <p class="text-gray-400">The referral system is currently disabled by the administrator.</p>
    </div>
    @else
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-auto sm:max-w-xs">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light dark:text-text-dark">search</span>
            <input wire:model.live.debounce.300ms="search" class="w-full h-10 pl-10 pr-4 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Search by username..." type="text"/>
        </div>
        <select wire:model.live="statusFilter" class="w-full sm:w-auto h-10 px-3 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Filter by Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <div class="overflow-x-auto rounded-lg border border-border-light dark:border-border-dark mt-4">
        <table class="min-w-full divide-y divide-border-light dark:divide-border-dark">
            <thead class="bg-background-light dark:bg-background-dark">
            <tr>
                <th wire:click="sortByColumn('name')" class="px-6 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider cursor-pointer hover:text-primary" scope="col">
                    Username
                    @if($sortBy === 'name')
                        <span class="material-symbols-outlined text-xs align-middle">{{ $sortDirection === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                    @endif
                </th>
                <th wire:click="sortByColumn('created_at')" class="px-6 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider cursor-pointer hover:text-primary" scope="col">
                    Registration Date
                    @if($sortBy === 'created_at')
                        <span class="material-symbols-outlined text-xs align-middle">{{ $sortDirection === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                    @endif
                </th>
                <th class="px-6 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Your Earnings</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Last Activity</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Status</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-border-light dark:divide-border-dark">
            @forelse($referredUsers as $referredUser)
                @php
                    $earnings = $this->getReferralEarningForUser($referredUser);
                    $lastActivity = $referredUser->last_login_at;
                    $isActive = $referredUser->links()->exists();
                @endphp
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-heading-light dark:text-heading-dark">{{ $referredUser->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">{{ $referredUser->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">${{ number_format($earnings, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">
                        @if($lastActivity)
                            {{ $lastActivity->diffForHumans() }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                            <p class="text-lg font-medium">No referrals yet</p>
                            <p class="text-sm">Share your referral link to start earning commissions!</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($referredUsers->count() > 0)
    <nav aria-label="Pagination" class="flex items-center justify-between pt-4">
        <div class="hidden sm:block">
            <p class="text-sm text-text-light dark:text-text-dark">
                Showing
                <span class="font-medium">{{ $referredUsers->count() }}</span>
                referral(s)
            </p>
        </div>
        <div class="text-sm text-text-light dark:text-text-dark">
            Total Earnings from Referrals: <span class="font-bold text-green-600 dark:text-green-400">${{ number_format($totalReferralEarnings, 2) }}</span>
        </div>
    </nav>
    @endif
    @endif
</div>

