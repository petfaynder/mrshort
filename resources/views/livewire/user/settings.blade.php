<div class="flex flex-col gap-8">
    {{-- Header --}}
    <div class="flex flex-wrap justify-between gap-3">
        <h1 class="text-4xl font-black leading-tight tracking-[-0.033em] min-w-72 text-heading-light dark:text-heading-dark">Settings Page</h1>
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
                
                @if (session()->has('message'))
                    <div class="mt-3 text-green-500 text-sm">
                        {{ session('message') }}
                    </div>
                @endif
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

                    @if (session('success') && !session('password_success'))
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400"
                        >{{ __('Saved.') }}</p>
                    @endif
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

                    @if (session('success') && session('password_success'))
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400"
                        >{{ __('Saved.') }}</p>
                    @endif
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
