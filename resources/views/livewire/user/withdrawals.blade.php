<div>
    <div class="mx-auto max-w-7xl">
        @if (session()->has('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="flex flex-wrap justify-between gap-3 mb-8">
            <div class="flex min-w-72 flex-col gap-2">
                <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Withdrawals</h1>
                <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">Manage your earnings and request withdrawals.</p>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div class="flex flex-col justify-start rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-white/10">
                <div class="flex w-full flex-col items-start justify-center gap-2">
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-base">account_balance_wallet</span>
                        <p class="text-sm font-normal leading-normal">Available Balance</p>
                    </div>
                    <p class="text-gray-900 dark:text-white text-4xl font-bold leading-tight tracking-[-0.015em]">${{ number_format($available_balance, 2) }}</p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Ready for Withdrawal</p>
                </div>
            </div>
            <div class="flex flex-col justify-start rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-white/10">
                <div class="flex w-full flex-col items-start justify-center gap-2">
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-base">history</span>
                        <p class="text-sm font-normal leading-normal">Total amount withdrawn to date</p>
                    </div>
                    <p class="text-gray-900 dark:text-white text-4xl font-bold leading-tight tracking-[-0.015em]">${{ number_format($total_withdrawn, 2) }}</p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">All time earnings withdrawn</p>
                </div>
            </div>
            <div class="flex flex-col justify-start rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-white/10">
                <div class="flex w-full flex-col items-start justify-center gap-3">
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-base">info</span>
                        <p class="text-sm font-normal leading-normal">Withdrawal Limits & Fees</p>
                    </div>
                    <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1.5">
                        <p>Min. Withdrawal: <span class="font-medium text-gray-900 dark:text-white">$5.00</span></p>
                        <p>PayPal Fee: <span class="font-medium text-gray-900 dark:text-white">2%</span></p>
                        <p>Bank Transfer Fee: <span class="font-medium text-gray-900 dark:text-white">$1.00</span></p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-start rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-white/10">
                <div class="flex w-full flex-col items-start justify-center gap-2">
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-base">autorenew</span>
                        <p class="text-sm font-normal leading-normal">Recurring Withdrawals</p>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-300">Active</span>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Next: 1st of month</p>
                    </div>
                    <a class="text-sm text-primary hover:underline mt-1" href="#">Manage Settings</a>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-gray-800/50 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-white/10">
                <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] pb-5">Create New Withdrawal Request</h2>
                <form wire:submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <label class="flex flex-col">
                            <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Amount ($)</p>
                            <input wire:model.defer="withdrawal_amount" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="Enter amount" type="number" step="0.01"/>
                            @error('withdrawal_amount') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </label>
                        <label class="flex flex-col">
                            <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Payment Method</p>
                            <select wire:model.live="payment_method" class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 p-[15px] text-base font-normal leading-normal">
                                <option value="">Select</option>
                                <option value="PayPal">PayPal</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                            @error('payment_method') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </label>
                    </div>

                    @if ($payment_method === 'PayPal')
                        <div class="mt-6">
                            <label class="flex flex-col">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">PayPal Email Address</p>
                                <input wire:model="paypal_email" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="paypal@example.com" type="email"/>
                                @error('paypal_email') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                            </label>
                        </div>
                    @elseif ($payment_method === 'Bank Transfer')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <label class="flex flex-col">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">IBAN</p>
                                <input wire:model="iban" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="TR00 0000 0000 0000 0000 0000"/>
                                @error('iban') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                            </label>
                            <label class="flex flex-col">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Account Holder Name</p>
                                <input wire:model="account_holder_name" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="Full Name"/>
                                @error('account_holder_name') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                            </label>
                            <label class="flex flex-col mt-6 md:mt-0">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Bank Name</p>
                                <input wire:model="bank_name" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="Bank Name"/>
                                @error('bank_name') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                            </label>
                            <label class="flex flex-col mt-6 md:mt-0">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">SWIFT/BIC Code (Optional)</p>
                                <input wire:model="swift_bic" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="SWIFT/BIC"/>
                            </label>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-end">
                        <button type="submit" wire:loading.attr="disabled" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors disabled:opacity-75 disabled:cursor-not-allowed gap-2">
                            <span wire:loading.remove wire:target="submit" class="truncate">Create Request</span>
                            <span wire:loading wire:target="submit" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                            <span wire:loading wire:target="submit">Creating...</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="flex flex-col justify-start rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-white/10">
                <h3 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] mb-4">Statistics (Last 30 Days)</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Withdrawn</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($stats_total_withdrawn, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Pending Requests</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($stats_pending_amount, 2) }} ({{ $stats_pending_requests }})</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Completed Requests</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stats_completed_requests }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Cancelled Requests</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stats_cancelled_requests }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Average Amount</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($stats_average_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em]">Withdrawal History</h2>
            </div>
            <div class="bg-white dark:bg-gray-800/50 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-white/10 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 !text-xl">search</span>
                        <input wire:model="search" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-10 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-4 text-sm font-normal leading-normal" placeholder="Search by Request ID..." type="text"/>
                    </div>
                    <select wire:model="status" class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-10 px-3 text-sm font-normal leading-normal">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <select wire:model="method" class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-10 px-3 text-sm font-normal leading-normal">
                        <option value="">All Methods</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                    <input wire:model="date_range" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-10 px-3 placeholder:text-gray-400 dark:placeholder:text-gray-500 text-sm font-normal leading-normal" placeholder="Date Range" type="date"/>
                    <button wire:click="$set('search', '') & $set('status', '') & $set('method', '') & $set('date_range', '')" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-200 dark:bg-white/10 text-gray-800 dark:text-white text-sm font-medium leading-normal hover:bg-gray-300 dark:hover:bg-white/20 transition-colors">
                        <span class="truncate">Clear Filter</span>
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-white/10">
                <table class="min-w-full text-sm text-left">
                    <thead class="border-b border-gray-200 dark:border-white/10 text-xs text-gray-500 dark:text-gray-400 uppercase">
                        <tr>
                            <th class="px-6 py-4 font-medium" scope="col">Request ID</th>
                            <th class="px-6 py-4 font-medium" scope="col">Amount ($)</th>
                            <th class="px-6 py-4 font-medium" scope="col">Payment Method</th>
                            <th class="px-6 py-4 font-medium" scope="col">Request Date</th>
                            <th class="px-6 py-4 font-medium" scope="col">Status</th>
                            <th class="px-6 py-4 font-medium text-right" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                        @forelse ($withdrawals as $withdrawal)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $withdrawal->id }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">${{ number_format($withdrawal->amount, 2) }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $withdrawal->method }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $withdrawal->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                        @switch($withdrawal->status)
                                            @case('pending')
                                                bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300
                                                @break
                                            @case('completed')
                                                bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @break
                                            @case('cancelled')
                                                bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                                @break
                                        @endswitch
                                    ">{{ ucfirst($withdrawal->status) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if ($withdrawal->status === 'pending')
                                        <button wire:click="cancelWithdrawal({{ $withdrawal->id }})" wire:loading.attr="disabled" class="text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400 text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1 float-right">
                                            <span wire:loading.remove wire:target="cancelWithdrawal({{ $withdrawal->id }})">Cancel</span>
                                            <span wire:loading wire:target="cancelWithdrawal({{ $withdrawal->id }})" class="material-symbols-outlined text-xs animate-spin">progress_activity</span>
                                        </button>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No withdrawals found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $withdrawals->links() }}
            </div>
        </div>
    </div>
</div>
