<div x-data="{ showConfirmModal: @entangle('showConfirmModal'), countrySearch: '' }">
    <x-slot name="header">
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-[#111827] dark:text-[#F9FAFB]">Create New Campaign</h1>
                <p class="text-md text-[#6B7280] dark:text-[#9CA3AF] mt-1">Set up your new campaign to start reaching your audience.</p>
            </div>
            <div class="flex items-center gap-4 mt-4 sm:mt-0">
                <button class="text-[#6B7280] dark:text-[#9CA3AF] hover:text-[#111827] dark:hover:text-[#F9FAFB]">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="flex items-center gap-2">
                    <img alt="User avatar" class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=40&background=0D8ABC&color=fff"/>
                    <div>
                        <p class="text-sm font-semibold text-[#111827] dark:text-[#F9FAFB]">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-[#6B7280] dark:text-[#9CA3AF]">Balance: ${{ number_format(Auth::user()->balance, 2) }}</p>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column: Form -->
        <div class="xl:col-span-2 space-y-8">
            
            <!-- Campaign Details -->
            <div class="bg-[#FFFFFF] dark:bg-[#1F2937] p-6 md:p-8 rounded-lg shadow-sm border border-[#E5E7EB] dark:border-[#374151]">
                <h2 class="text-xl font-semibold text-[#111827] dark:text-[#F9FAFB] mb-6">Campaign Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-2" for="campaign-name">Campaign Name *</label>
                        <input wire:model="name" class="w-full bg-[#FFFFFF] dark:bg-[#374151] border border-[#E5E7EB] dark:border-[#374151] text-[#111827] dark:text-[#F9FAFB] rounded-md focus:ring-[#3B82F6] focus:border-[#3B82F6] placeholder-[#9CA3AF] dark:placeholder-[#6B7280]" id="campaign-name" placeholder="e.g., Summer Sale Pop-up" type="text"/>
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="mt-2 text-xs text-[#6B7280] dark:text-[#9CA3AF]">A unique name for your campaign (e.g., Summer Sale 2025)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-2" for="click-count">Desired Click Count *</label>
                        <input wire:model.live="desired_clicks" class="w-full bg-[#FFFFFF] dark:bg-[#374151] border border-[#E5E7EB] dark:border-[#374151] text-[#111827] dark:text-[#F9FAFB] rounded-md focus:ring-[#3B82F6] focus:border-[#3B82F6] placeholder-[#9CA3AF] dark:placeholder-[#6B7280]" id="click-count" placeholder="1000" type="number"/>
                        @error('desired_clicks') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-2" for="estimated-cost">Estimated Cost ($)</label>
                        <input class="w-full bg-[#F3F4F6] dark:bg-[#374151] border-transparent text-[#6B7280] dark:text-[#9CA3AF] rounded-md cursor-not-allowed" disabled="" id="estimated-cost" type="text" value="${{ number_format($calculated_cost, 2) }}"/>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-2" for="target-url">Target URL *</label>
                        <input wire:model="popup_url" class="w-full bg-[#FFFFFF] dark:bg-[#374151] border border-[#E5E7EB] dark:border-[#374151] text-[#111827] dark:text-[#F9FAFB] rounded-md focus:ring-[#3B82F6] focus:border-[#3B82F6] placeholder-[#9CA3AF] dark:placeholder-[#6B7280]" id="target-url" placeholder="https://example.com/campaign" type="url"/>
                        @error('popup_url') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="mt-2 text-xs text-[#6B7280] dark:text-[#9CA3AF]">The destination link for your campaign (e.g., https://example.com/promo)</p>
                    </div>
                </div>
            </div>

            <!-- Targeting -->
            <div class="bg-[#FFFFFF] dark:bg-[#1F2937] p-6 md:p-8 rounded-lg shadow-sm border border-[#E5E7EB] dark:border-[#374151]">
                <h2 class="text-xl font-semibold text-[#111827] dark:text-[#F9FAFB] mb-6">Targeting</h2>
                <div class="space-y-6">
                    
                    <!-- Target Countries -->
                    <div>
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-2">Target Countries *</label>
                        <div class="relative mb-3">
                            <input x-model="countrySearch" class="w-full bg-[#FFFFFF] dark:bg-[#374151] border border-[#E5E7EB] dark:border-[#374151] text-[#111827] dark:text-[#F9FAFB] rounded-md focus:ring-[#3B82F6] focus:border-[#3B82F6] placeholder-[#9CA3AF] dark:placeholder-[#6B7280] pl-10" placeholder="Search and select countries" type="text"/>
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#6B7280] dark:text-[#9CA3AF]">search</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 max-h-48 overflow-y-auto p-2 border border-[#E5E7EB] dark:border-[#374151] rounded-md">
                            @foreach($countries as $country)
                                <label wire:key="country-{{ $country->id }}" x-show="'{{ strtolower($country->name) }}'.includes(countrySearch.toLowerCase())" class="flex items-center space-x-2 cursor-pointer p-1 hover:bg-[#F3F4F6] dark:hover:bg-[#111827] rounded">
                                    <input type="checkbox" wire:model.live="selectedCountries" value="{{ $country->iso_code }}" class="rounded border-gray-300 text-[#3B82F6] focus:ring-[#3B82F6] bg-gray-100 dark:bg-gray-700">
                                    <span class="fi fi-{{ strtolower($country->iso_code) }} mr-1"></span>
                                    <span class="text-sm text-[#111827] dark:text-[#F9FAFB]">{{ $country->name }}</span>
                                </label>
                            @endforeach
                        </div>
                         @error('selectedCountries') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Target Age Groups -->
                    <div>
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-3">Target Age Groups *</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($ageRanges as $range)
                                <label wire:key="age-{{ $loop->index }}" class="cursor-pointer">
                                    <input type="checkbox" wire:model.live="selectedAgeRanges" value="{{ $range }}" class="sr-only peer">
                                    <div class="px-4 py-2 rounded-full text-sm font-medium bg-[#F3F4F6] dark:bg-[#374151] text-[#111827] dark:text-[#F9FAFB] peer-checked:bg-[#DBEAFE] dark:peer-checked:bg-[#1E40AF] peer-checked:text-blue-800 dark:peer-checked:text-white hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        {{ $range }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedAgeRanges') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Target Devices -->
                    <div>
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-3">Target Devices *</label>
                        <div class="flex flex-wrap gap-3">
                             @foreach($deviceOptions as $key => $label)
                                <label wire:key="device-{{ $key }}" class="cursor-pointer">
                                    <input type="checkbox" wire:model.live="selectedDevices" value="{{ $key }}" class="sr-only peer">
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium bg-[#F3F4F6] dark:bg-[#374151] text-[#111827] dark:text-[#F9FAFB] peer-checked:bg-[#DBEAFE] dark:peer-checked:bg-[#1E40AF] peer-checked:text-blue-800 dark:peer-checked:text-white hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        @if($key == 'desktop') <span class="material-symbols-outlined text-base">desktop_windows</span>
                                        @elseif($key == 'mobile') <span class="material-symbols-outlined text-base">smartphone</span>
                                        @elseif($key == 'tablet') <span class="material-symbols-outlined text-base">tablet_mac</span>
                                        @endif
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                         @error('selectedDevices') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Target Operating Systems -->
                    <div>
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-3">Target Operating Systems *</label>
                        <div class="flex flex-wrap gap-3">
                             @foreach($osOptions as $key => $label)
                                <label wire:key="os-{{ $key }}" class="cursor-pointer">
                                    <input type="checkbox" wire:model.live="selectedOs" value="{{ $key }}" class="sr-only peer">
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium bg-[#F3F4F6] dark:bg-[#374151] text-[#111827] dark:text-[#F9FAFB] peer-checked:bg-[#DBEAFE] dark:peer-checked:bg-[#1E40AF] peer-checked:text-blue-800 dark:peer-checked:text-white hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                         @error('selectedOs') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Budget & Schedule -->
            <div class="bg-[#FFFFFF] dark:bg-[#1F2937] p-6 md:p-8 rounded-lg shadow-sm border border-[#E5E7EB] dark:border-[#374151]">
                <h2 class="text-xl font-semibold text-[#111827] dark:text-[#F9FAFB] mb-6">Budget & Schedule</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-2" for="total-budget">Total Budget ($)</label>
                        <input wire:model="budget" class="w-full bg-[#FFFFFF] dark:bg-[#374151] border border-[#E5E7EB] dark:border-[#374151] text-[#111827] dark:text-[#F9FAFB] rounded-md focus:ring-[#3B82F6] focus:border-[#3B82F6] placeholder-[#9CA3AF] dark:placeholder-[#6B7280]" id="total-budget" placeholder="0.00" type="number"/>
                         @error('budget') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-2" for="daily-budget">Daily Budget ($)</label>
                        <input wire:model="daily_budget" class="w-full bg-[#FFFFFF] dark:bg-[#374151] border border-[#E5E7EB] dark:border-[#374151] text-[#111827] dark:text-[#F9FAFB] rounded-md focus:ring-[#3B82F6] focus:border-[#3B82F6] placeholder-[#9CA3AF] dark:placeholder-[#6B7280]" id="daily-budget" placeholder="e.g., 50" type="number"/>
                        @error('daily_budget') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="mt-2 text-xs text-[#6B7280] dark:text-[#9CA3AF]">Maximum amount to be spent per day</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-2" for="start-date">Start Date</label>
                        <input wire:model="start_date" class="w-full bg-[#FFFFFF] dark:bg-[#374151] border border-[#E5E7EB] dark:border-[#374151] text-[#111827] dark:text-[#F9FAFB] rounded-md focus:ring-[#3B82F6] focus:border-[#3B82F6]" id="start-date" type="date"/>
                         @error('start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="mt-2 text-xs text-[#6B7280] dark:text-[#9CA3AF]">Campaign will start on this date.</p>
                    </div>
                    
                    <div x-show="!$wire.run_until_budget_depleted">
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-2" for="end-date">End Date</label>
                        <input wire:model="end_date" class="w-full bg-[#FFFFFF] dark:bg-[#374151] border border-[#E5E7EB] dark:border-[#374151] text-[#111827] dark:text-[#F9FAFB] rounded-md focus:ring-[#3B82F6] focus:border-[#3B82F6]" id="end-date" type="date"/>
                         @error('end_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="mt-2 text-xs text-[#6B7280] dark:text-[#9CA3AF]">Optional, leave blank for ongoing campaign</p>
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input wire:model.live="run_until_budget_depleted" class="h-4 w-4 text-[#3B82F6] rounded border-gray-300 dark:border-gray-600 focus:ring-[#3B82F6] bg-gray-100 dark:bg-gray-700" id="continue-balance" type="checkbox"/>
                            </div>
                            <div class="ml-3 text-sm">
                                <label class="font-medium text-[#111827] dark:text-[#F9FAFB]" for="continue-balance">Continue Until Balance Runs Out</label>
                                <p class="text-[#6B7280] dark:text-[#9CA3AF]">If checked, the campaign will ignore the end date and run until the total budget is spent.</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[#6B7280] dark:text-[#9CA3AF] mb-2" for="daily-click-limit">Daily Click Limit (Optional)</label>
                        <input wire:model="daily_click_limit" class="w-full bg-[#FFFFFF] dark:bg-[#374151] border border-[#E5E7EB] dark:border-[#374151] text-[#111827] dark:text-[#F9FAFB] rounded-md focus:ring-[#3B82F6] focus:border-[#3B82F6] placeholder-[#9CA3AF] dark:placeholder-[#6B7280]" id="daily-click-limit" placeholder="Leave blank for no limit" type="number"/>
                        @error('daily_click_limit') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-[#FFFFFF] dark:bg-[#1F2937] p-6 md:p-8 rounded-lg shadow-sm border border-[#E5E7EB] dark:border-[#374151]">
                <h2 class="text-xl font-semibold text-[#111827] dark:text-[#F9FAFB] mb-6">Payment Method</h2>
                <div class="space-y-4">
                    <!-- Pay with Balance -->
                    <label class="relative flex items-center p-4 rounded-lg border {{ $payment_method === 'balance' ? 'border-[#3B82F6] bg-blue-50 dark:bg-blue-900/20' : 'border-[#E5E7EB] dark:border-[#374151] hover:bg-gray-50 dark:hover:bg-gray-800' }} cursor-pointer transition-all">
                        <input type="radio" wire:model.live="payment_method" value="balance" class="h-4 w-4 text-[#3B82F6] border-gray-300 focus:ring-[#3B82F6] bg-gray-100 dark:bg-gray-700" 
                            @if(Auth::user()->earnings < 10 || Auth::user()->earnings < $calculated_cost) disabled @endif>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="block text-sm font-medium text-[#111827] dark:text-[#F9FAFB]">
                                    Pay with Balance
                                </span>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded {{ Auth::user()->earnings >= 10 && Auth::user()->earnings >= $calculated_cost ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    Current: ${{ number_format(Auth::user()->earnings, 2) }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-[#6B7280] dark:text-[#9CA3AF]">
                                Use your account earnings. Minimum balance required: $10.00
                            </p>
                             @if(Auth::user()->earnings < 10)
                                <p class="mt-1 text-xs text-red-500">Insufficient balance (Min $10).</p>
                            @elseif(Auth::user()->earnings < $calculated_cost)
                                <p class="mt-1 text-xs text-red-500">Insufficient balance for this campaign.</p>
                            @endif
                        </div>
                    </label>

                    <!-- Pay with Crypto -->
                    <label class="relative flex items-center p-4 rounded-lg border {{ $payment_method === 'crypto' ? 'border-[#3B82F6] bg-blue-50 dark:bg-blue-900/20' : 'border-[#E5E7EB] dark:border-[#374151] hover:bg-gray-50 dark:hover:bg-gray-800' }} cursor-pointer transition-all">
                        <input type="radio" wire:model.live="payment_method" value="crypto" class="h-4 w-4 text-[#3B82F6] border-gray-300 focus:ring-[#3B82F6] bg-gray-100 dark:bg-gray-700">
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="block text-sm font-medium text-[#111827] dark:text-[#F9FAFB]">
                                    Pay with Crypto (Cryptomus)
                                </span>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300">
                                    Instant
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-[#6B7280] dark:text-[#9CA3AF]">
                                Pay securely with USDT, BTC, ETH and more via Cryptomus.
                            </p>
                        </div>
                    </label>
                    @error('payment_method') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Right Column: Preview Sidebar -->
        <div class="xl:col-span-1 space-y-8">
            <div class="bg-[#FFFFFF] dark:bg-[#1F2937] p-6 rounded-lg sticky top-8 border border-[#E5E7EB] dark:border-[#374151]">
                <h2 class="text-xl font-semibold text-[#111827] dark:text-[#F9FAFB] mb-6">Live Campaign Preview</h2>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[#6B7280] dark:text-[#9CA3AF]">Status:</span>
                        <span class="font-medium px-2 py-1 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">Draft</span>
                    </div>
                    <hr class="border-[#E5E7EB] dark:border-[#374151]"/>
                    <div class="space-y-1">
                        <div class="text-[#6B7280] dark:text-[#9CA3AF]">Campaign Name:</div>
                        <div class="font-medium text-[#111827] dark:text-[#F9FAFB] truncate">{{ $name ?: 'Campaign Name' }}</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-[#6B7280] dark:text-[#9CA3AF]">Target URL:</div>
                        <div class="font-medium text-[#3B82F6] truncate">{{ $popup_url ?: 'https://example.com' }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-[#6B7280] dark:text-[#9CA3AF]">Desired Clicks:</div>
                            <div class="font-medium text-[#111827] dark:text-[#F9FAFB]">{{ number_format((float)$desired_clicks) }}</div>
                        </div>
                        <div>
                            <div class="text-[#6B7280] dark:text-[#9CA3AF]">Est. Cost:</div>
                            <div class="font-medium text-[#111827] dark:text-[#F9FAFB]">${{ number_format((float)$calculated_cost, 2) }}</div>
                        </div>
                    </div>
                    <hr class="border-[#E5E7EB] dark:border-[#374151]"/>
                    <div class="space-y-3">
                        <div>
                            <h3 class="text-[#6B7280] dark:text-[#9CA3AF] mb-2">Targeting</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-[#6B7280] dark:text-[#9CA3AF] text-xs block">Countries</span>
                                    <div class="min-h-5 flex flex-wrap gap-1.5 items-center">
                                        @if(count($selectedCountries) > 0)
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 rounded-md">{{ count($selectedCountries) }} selected</span>
                                        @else
                                            <span class="text-xs text-gray-400">None</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[#6B7280] dark:text-[#9CA3AF] text-xs block">Age Groups</span>
                                    <div class="min-h-5 flex flex-wrap gap-1.5 items-center">
                                        @foreach($selectedAgeRanges as $age)
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 rounded-md">{{ $age }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[#6B7280] dark:text-[#9CA3AF] text-xs block">Devices</span>
                                    <div class="min-h-5 flex flex-wrap gap-1.5 items-center">
                                         @foreach($selectedDevices as $device)
                                            <span class="flex items-center gap-1 px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 rounded-md">
                                                {{ $deviceOptions[$device] ?? $device }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[#6B7280] dark:text-[#9CA3AF] text-xs block">Operating Systems</span>
                                    <div class="min-h-5 flex flex-wrap gap-1.5 items-center">
                                        @foreach($selectedOs as $os)
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 rounded-md">
                                                {{ $osOptions[$os] ?? $os }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="border-[#E5E7EB] dark:border-[#374151]"/>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-[#6B7280] dark:text-[#9CA3AF]">Total Budget:</div>
                            <div class="font-medium text-[#111827] dark:text-[#F9FAFB]">${{ number_format((float)$budget, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-[#6B7280] dark:text-[#9CA3AF]">Start Date:</div>
                            <div class="font-medium text-[#111827] dark:text-[#F9FAFB]">{{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d M, Y') : '-' }}</div>
                        </div>
                        <div class="col-span-2">
                            <div class="text-[#6B7280] dark:text-[#9CA3AF]">Duration:</div>
                            <div class="font-medium text-[#111827] dark:text-[#F9FAFB]">
                                @if($run_until_budget_depleted)
                                    Ongoing (no end date)
                                @else
                                    {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d M, Y') : 'Not set' }}
                                @endif
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="text-[#6B7280] dark:text-[#9CA3AF]">Payment:</div>
                            <div class="font-medium text-[#111827] dark:text-[#F9FAFB]">
                                @if($payment_method === 'balance')
                                    Balance (${{ number_format(Auth::user()->earnings, 2) }})
                                @elseif($payment_method === 'crypto')
                                    Cryptomus
                                @else
                                    Not Selected
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Moved Action Button Inside Sticky Sidebar -->
                    <div class="mt-8">
                        <button type="button" wire:click="openConfirmationModal" wire:target="openConfirmationModal" wire:loading.attr="disabled" class="w-full bg-[#3B82F6] text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-600 transition-colors duration-300 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="openConfirmationModal">Create Campaign</span>
                            <span wire:loading wire:target="openConfirmationModal">Validating...</span>
                            <span wire:loading.remove wire:target="openConfirmationModal" class="material-symbols-outlined">arrow_forward</span>
                            <span wire:loading wire:target="openConfirmationModal" class="material-symbols-outlined animate-spin">progress_activity</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-2xl transform rounded-xl bg-[#1F2937] shadow-2xl transition-all border border-[#374151] p-8 relative">
            <button @click="showConfirmModal = false" class="absolute top-4 right-4 text-[#111827] hover:text-white">
                 <span class="material-symbols-outlined">close</span>
            </button>
            
            <div class="flex flex-col items-center text-center">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#3B82F6]/10 text-[#3B82F6]">
                    <span class="material-symbols-outlined text-2xl">campaign</span>
                </div>
                <h3 class="text-2xl font-bold text-[#F9FAFB]">Confirm Your Campaign</h3>
                <p class="mt-2 text-base text-[#9CA3AF]">Please review your campaign details. Are you sure you want to create this campaign?</p>
            </div>

            <div class="mt-8 space-y-5 rounded-lg border border-[#374151] bg-[#111827] p-6">
                <h4 class="text-lg font-semibold text-[#F9FAFB]">Campaign Summary</h4>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 text-sm sm:grid-cols-2">
                    <div>
                        <div class="text-[#9CA3AF]">Campaign Name</div>
                        <div class="font-medium text-[#F9FAFB]">{{ $name }}</div>
                    </div>
                    <div>
                        <div class="text-[#9CA3AF]">Desired Clicks</div>
                        <div class="font-medium text-[#F9FAFB]">{{ number_format((float)$desired_clicks) }}</div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="text-[#9CA3AF]">Target URL</div>
                        <div class="truncate font-medium text-[#F9FAFB]">{{ $popup_url }}</div>
                    </div>
                    <div>
                        <div class="text-[#9CA3AF]">Total Budget</div>
                        <div class="font-medium text-[#F9FAFB]">${{ number_format((float)$budget, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[#9CA3AF]">Daily Budget</div>
                        <div class="font-medium text-[#F9FAFB]">${{ number_format((float)$daily_budget, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[#9CA3AF]">Start Date</div>
                        <div class="font-medium text-[#F9FAFB]">{{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d M, Y') : '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[#9CA3AF]">End Date</div>
                        <div class="font-medium text-[#F9FAFB]">{{ $run_until_budget_depleted ? 'Ongoing' : ($end_date ? \Carbon\Carbon::parse($end_date)->format('d M, Y') : '-') }}</div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="text-[#9CA3AF]">Targeting</div>
                        <div class="font-medium text-[#F9FAFB]">
                            Countries: {{ count($selectedCountries) }} | Ages: {{ count($selectedAgeRanges) }} | Devices: {{ count($selectedDevices) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col-reverse gap-4 sm:flex-row sm:justify-end">
                <button @click="showConfirmModal = false" class="w-full rounded-md bg-gray-600/50 px-6 py-3 text-sm font-semibold text-[#F9FAFB] transition-colors hover:bg-gray-500/50 sm:w-auto">
                    Go Back to Edit
                </button>
                <button wire:click="createCampaign" wire:loading.attr="disabled" class="w-full rounded-md bg-[#3B82F6] px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-600 sm:w-auto flex items-center justify-center gap-2 disabled:opacity-75 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="createCampaign">Confirm & Create Campaign</span>
                    <span wire:loading wire:target="createCampaign" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                    <span wire:loading wire:target="createCampaign">Creating...</span>
                </button>
            </div>
        </div>
    </div>
</div>
