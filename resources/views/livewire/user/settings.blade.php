<div class="flex flex-col gap-8">
    {{-- Header --}}
    <div class="flex flex-wrap justify-between gap-3">
        <h1 class="text-2xl sm:text-4xl font-black leading-tight tracking-[-0.033em] text-heading-light dark:text-heading-dark">Settings Page</h1>
    </div>

    <div class="grid grid-cols-1 gap-8">
        {{-- Payment Settings --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6" x-data="{ show2FA: false }">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Payment Settings</h2>
            
            <form wire:submit.prevent="updatePaymentSettings">
                <div class="space-y-4">
                    <div>
                        <x-input-label for="paymentMethod" :value="__('Payment Method')" />
                        <select wire:model.live="paymentMethod" id="paymentMethod" class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary dark:focus:ring-primary shadow-sm">
                            <option value="">Select Method</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="crypto">Crypto</option>
                            <option value="papara">Papara</option>
                        </select>
                        <x-input-error :messages="$errors->get('paymentMethod')" class="mt-2" />
                    </div>

                    @if ($paymentMethod === 'paypal')
                        <div>
                            <x-input-label for="paypalEmail" :value="__('PayPal Email')" />
                            <x-text-input wire:model="paypalEmail" id="paypalEmail" class="block mt-1 w-full" type="email" placeholder="email@example.com" />
                            <x-input-error :messages="$errors->get('paypalEmail')" class="mt-2" />
                        </div>
                    @endif

                    @if ($paymentMethod === 'bank_transfer')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="iban" :value="__('IBAN')" />
                                <x-text-input wire:model="iban" id="iban" class="block mt-1 w-full" type="text" placeholder="TR..." />
                                <x-input-error :messages="$errors->get('iban')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="accountHolderName" :value="__('Account Holder Name')" />
                                <x-text-input wire:model="accountHolderName" id="accountHolderName" class="block mt-1 w-full" type="text" placeholder="Full Name" />
                                <x-input-error :messages="$errors->get('accountHolderName')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="bankName" :value="__('Bank Name')" />
                                <x-text-input wire:model="bankName" id="bankName" class="block mt-1 w-full" type="text" placeholder="Bank Name" />
                                <x-input-error :messages="$errors->get('bankName')" class="mt-2" />
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" wire:loading.attr="disabled" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] disabled:opacity-75 disabled:cursor-not-allowed gap-2">
                        <span wire:loading.remove wire:target="updatePaymentSettings" class="truncate">Save Payment Settings</span>
                        <span wire:loading wire:target="updatePaymentSettings" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                        <span wire:loading wire:target="updatePaymentSettings">Saving...</span>
                    </button>
                </div>
                

            </form>
        </div>

        {{-- Profile Information --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Profile Information</h2>
            <form wire:submit.prevent="updateProfileInformation" class="mt-6 space-y-6">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" wire:loading.attr="disabled" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] disabled:opacity-75 disabled:cursor-not-allowed gap-2">
                        <span wire:loading.remove wire:target="updateProfileInformation">{{ __('Save Profile') }}</span>
                        <span wire:loading wire:target="updateProfileInformation" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                        <span wire:loading wire:target="updateProfileInformation">{{ __('Saving...') }}</span>
                    </button>


                </div>
            </form>
        </div>

        {{-- Update Password --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Update Password</h2>
            <form wire:submit.prevent="updatePassword" class="mt-6 space-y-6">
                <div>
                    <x-input-label for="current_password" :value="__('Current Password')" />
                    <x-text-input wire:model="currentPassword" id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('currentPassword')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('New Password')" />
                    <x-text-input wire:model="newPassword" id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('newPassword')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input wire:model="newPasswordConfirmation" id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('newPasswordConfirmation')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" wire:loading.attr="disabled" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] disabled:opacity-75 disabled:cursor-not-allowed gap-2">
                        <span wire:loading.remove wire:target="updatePassword">{{ __('Save Password') }}</span>
                        <span wire:loading wire:target="updatePassword" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                        <span wire:loading wire:target="updatePassword">{{ __('Saving...') }}</span>
                    </button>


                </div>
            </form>
        </div>

        {{-- Email Verification Status --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Email Verification Status</h2>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @if (Auth::user()->hasVerifiedEmail())
                        <span class="material-symbols-outlined text-green-500">verified</span>
                        <p class="text-green-400">Your email address is verified.</p>
                    @else
                        <span class="material-symbols-outlined text-yellow-500">mark_email_unread</span>
                        <p class="text-yellow-400">Your email address is unverified.</p>
                    @endif
                </div>
                @if (!Auth::user()->hasVerifiedEmail())
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#233648] text-white text-sm font-bold leading-normal tracking-[0.015em]">
                            <span class="truncate">Resend Verification Email</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Telegram Traffic Bonus --}}
        <div class="rounded-xl border border-solid border-blue-500/30 bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/20 dark:to-gray-800 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em]">Telegram Traffic Bonus</h2>
                    <p class="text-text-light dark:text-text-dark text-sm">Earn +10% CPM for verified Telegram traffic</p>
                </div>
            </div>

            <div class="space-y-4">
                {{-- Status Indicator --}}
                <div class="flex items-center justify-between p-4 rounded-lg bg-white/50 dark:bg-gray-700/30">
                    <div class="flex items-center gap-3">
                        @if($telegramBonusStatus === 'active')
                            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                            <div>
                                <p class="font-semibold text-green-600 dark:text-green-400">Active</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    @if($telegramMatchRate)
                                        Last verification: {{ number_format($telegramMatchRate, 1) }}% Telegram traffic
                                    @else
                                        Awaiting first verification (500 clicks)
                                    @endif
                                </p>
                            </div>
                        @elseif($telegramBonusStatus === 'cooldown')
                            <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                            <div>
                                <p class="font-semibold text-yellow-600 dark:text-yellow-400">Cooldown</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Available on {{ $telegramCooldownEndsAt }}</p>
                            </div>
                        @else
                            <span class="w-3 h-3 bg-gray-400 rounded-full"></span>
                            <div>
                                <p class="font-semibold text-gray-600 dark:text-gray-400">Inactive</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Enable to start earning bonus CPM</p>
                            </div>
                        @endif
                    </div>

                    {{-- Toggle Button --}}
                    @if($telegramBonusStatus === 'active')
                        <button wire:click="disableTelegramBonus" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 rounded-lg transition-colors">
                            <span wire:loading.remove wire:target="disableTelegramBonus">Disable</span>
                            <span wire:loading wire:target="disableTelegramBonus">...</span>
                        </button>
                    @elseif($telegramBonusStatus === 'cooldown')
                        <button disabled class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Cooldown
                        </button>
                    @else
                        <button wire:click="openTelegramBonusModal"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">
                            Enable
                        </button>
                    @endif
                </div>

                {{-- Info Box --}}
                <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1 bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                    <p>✓ <strong>+10% CPM bonus</strong> on all your link earnings</p>
                    <p>⚡ Verification every <strong>500 clicks</strong></p>
                    <p>📊 At least <strong>70% traffic</strong> must come from Telegram</p>
                    <p>⚠️ <strong>7-day cooldown</strong> if verification fails</p>
                </div>
            </div>

            {{-- Enable Modal --}}
            @if($showTelegramBonusModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.75);">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <h3 class="text-xl font-bold">Enable Telegram Traffic Bonus</h3>
                        <p class="text-white/80 text-sm">Confirm to start earning +10% CPM</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            By enabling this bonus, you confirm that at least 70% of your traffic comes from Telegram. 
                            Traffic will be verified every 500 clicks, and the bonus will be revoked if verification fails.
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 flex gap-3">
                        <button wire:click="closeTelegramBonusModal"
                                class="flex-1 px-4 py-2.5 text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors font-medium text-sm">
                            Cancel
                        </button>
                        <button wire:click="enableTelegramBonus"
                                wire:loading.attr="disabled"
                                class="flex-1 px-4 py-2.5 text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-colors font-medium text-sm">
                            <span wire:loading.remove wire:target="enableTelegramBonus">Enable Bonus</span>
                            <span wire:loading wire:target="enableTelegramBonus">Enabling...</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Theme Preference --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Theme Preference</h2>
            <div class="flex items-center gap-4">
                <button wire:click="updateThemePreference('light')" wire:loading.attr="disabled"
                        @click="localStorage.setItem('theme', 'light'); document.documentElement.classList.remove('dark')"
                        class="flex flex-col items-center gap-2 rounded-lg border p-4 w-32 transition-colors {{ $themePreference === 'light' ? 'border-primary bg-primary/10' : 'border-[#324d67]' }} disabled:opacity-75 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="updateThemePreference('light')" class="material-symbols-outlined text-2xl">light_mode</span>
                    <span wire:loading wire:target="updateThemePreference('light')" class="material-symbols-outlined text-2xl animate-spin">progress_activity</span>
                    <span class="text-sm font-medium">Light</span>
                </button>
                <button wire:click="updateThemePreference('dark')" wire:loading.attr="disabled"
                        @click="localStorage.setItem('theme', 'dark'); document.documentElement.classList.add('dark')"
                        class="flex flex-col items-center gap-2 rounded-lg border p-4 w-32 transition-colors {{ $themePreference === 'dark' ? 'border-primary bg-primary/10' : 'border-[#324d67]' }} disabled:opacity-75 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="updateThemePreference('dark')" class="material-symbols-outlined text-2xl">dark_mode</span>
                    <span wire:loading wire:target="updateThemePreference('dark')" class="material-symbols-outlined text-2xl animate-spin">progress_activity</span>
                    <span class="text-sm font-medium">Dark</span>
                </button>
            </div>
        </div>

        {{-- Data Privacy Settings --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Data Privacy Settings</h2>
            <div class="space-y-4">
                <label class="flex items-center justify-between cursor-pointer" for="analytics-toggle">
                    <div>
                        <p class="text-heading-light dark:text-heading-dark font-medium">Usage Analytics</p>
                        <p class="text-text-light dark:text-text-dark text-sm">Allow collection of anonymous usage data to help us improve our services.</p>
                    </div>
                    <div class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-dark {{ $allowAnalytics ? 'bg-primary' : 'bg-[#324d67]' }}" role="switch">
                        <span class="{{ $allowAnalytics ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        <input wire:click="updatePrivacySettings" wire:model="allowAnalytics" class="absolute inset-0 size-full cursor-pointer opacity-0" id="analytics-toggle" type="checkbox"/>
                    </div>
                </label>
                <label class="flex items-center justify-between cursor-pointer" for="ads-toggle">
                    <div>
                        <p class="text-heading-light dark:text-heading-dark font-medium">Personalized Ads</p>
                        <p class="text-text-light dark:text-text-dark text-sm">Allow personalized ads to be shown to make your experience more relevant.</p>
                    </div>
                    <div class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-dark {{ $allowPersonalizedAds ? 'bg-primary' : 'bg-[#324d67]' }}" role="switch">
                        <span class="{{ $allowPersonalizedAds ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        <input wire:click="updatePrivacySettings" wire:model="allowPersonalizedAds" class="absolute inset-0 size-full cursor-pointer opacity-0" id="ads-toggle" type="checkbox"/>
                    </div>
                </label>
            </div>
        </div>

        {{-- Device Management --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Device Management</h2>
            <div class="space-y-4">
                @forelse ($this->sessions as $session)
                    <div class="flex items-center justify-between rounded-lg bg-background-light dark:bg-background-dark p-4">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-2xl text-text-light dark:text-text-dark">
                                {{ $session->is_desktop ? 'desktop_windows' : ($session->is_tablet ? 'tablet_mac' : 'smartphone') }}
                            </span>
                            <div>
                                <p class="text-heading-light dark:text-heading-dark font-medium">
                                    {{ $session->agent_platform }} - {{ $session->agent_browser }}
                                    @if ($session->is_current_device)
                                        <span class="text-green-400 font-normal ml-2">Current Session</span>
                                    @endif
                                </p>
                                <p class="text-sm text-text-light dark:text-text-dark">
                                    {{ $session->ip_address }} - {{ $session->last_active }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No active sessions found (using file driver?).</p>
                @endforelse
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="rounded-xl border border-solid border-red-500/50 bg-card-light dark:bg-card-dark p-6">
             @include('profile.partials.delete-user-form', ['user' => Auth::user()])
        </div>
    </div>
</div>

{{-- Toast Notifications --}}
@if (session()->has('message'))
    <x-toast-notification type="success">{{ session('message') }}</x-toast-notification>
@endif
@if (session()->has('success'))
    <x-toast-notification type="success">{{ session('success') }}</x-toast-notification>
@endif
@if (session()->has('error'))
    <x-toast-notification type="error">{{ session('error') }}</x-toast-notification>
@endif
@if (session()->has('info'))
    <x-toast-notification type="info">{{ session('info') }}</x-toast-notification>
@endif
