<x-user-dashboard-layout>
    {{-- AlpineJS is already included in the layout --}}

    <div class="flex flex-wrap justify-between gap-3 mb-8">
        <h1 class="text-4xl font-black leading-tight tracking-[-0.033em] min-w-72 text-heading-light dark:text-heading-dark">Ayarlar Sayfası</h1>
    </div>

    <div class="grid grid-cols-1 gap-8">
        {{-- Payment Settings --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6" x-data="{ show2FA: false }">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Ödeme Ayarları</h2>
            
            {{-- Existing Livewire Payment Settings Component --}}
            <div x-show="!show2FA">
                <livewire:user.payment-settings />
                {{-- The button to show 2FA is part of the new design. 
                     I'm adding it here. It will need backend logic to work. --}}
                <div class="mt-6 flex justify-end">
                    <button @click="show2FA = true" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]">
                        <span class="truncate">Kaydet</span>
                    </button>
                </div>
            </div>

            {{-- New 2FA Section --}}
            <div x-cloak x-show="show2FA">
                <h3 class="text-heading-light dark:text-heading-dark text-lg font-semibold leading-tight">İki Faktörlü Doğrulama</h3>
                <p class="text-text-light dark:text-text-dark text-sm mt-2 mb-4">Değişiklikleri onaylamak için lütfen doğrulama kodunuzu girin.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <label class="flex flex-col">
                        <p class="text-heading-light dark:text-heading-dark text-sm font-medium leading-normal pb-2">Doğrulama Kodu</p>
                        <input class="form-input w-full rounded-lg text-white focus:outline-0 focus:ring-0 border border-red-500 bg-[#192633] focus:border-red-500 h-12 px-4 placeholder:text-[#92adc9] text-base font-normal leading-normal" placeholder="123456" type="text"/>
                        <p class="text-red-500 text-xs mt-1">Geçersiz kod. Lütfen tekrar deneyin.</p>
                    </label>
                    <div class="flex items-end h-full gap-4 mt-6 md:mt-0">
                        <button @click="show2FA = false" class="flex min-w-[84px] w-full md:w-auto max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#233648] text-white text-sm font-bold leading-normal tracking-[0.015em]">
                            <span class="truncate">İptal</span>
                        </button>
                        <button class="flex min-w-[84px] w-full md:w-auto max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]">
                            <span class="truncate">Onayla</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profile Information --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Profile Information</h2>
            @include('profile.partials.update-profile-information-form', ['user' => Auth::user()])
        </div>

        {{-- Update Password --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Update Password</h2>
            @include('profile.partials.update-password-form', ['user' => Auth::user()])
        </div>

        {{-- Email Verification Status --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">E-posta Doğrulama Durumu</h2>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @if (Auth::user()->hasVerifiedEmail())
                        <span class="material-symbols-outlined text-green-500">verified</span>
                        <p class="text-green-400">E-posta adresiniz doğrulandı.</p>
                    @else
                        <span class="material-symbols-outlined text-yellow-500">mark_email_unread</span>
                        <p class="text-yellow-400">E-posta adresiniz doğrulanmadı.</p>
                    @endif
                </div>
                @if (!Auth::user()->hasVerifiedEmail())
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#233648] text-white text-sm font-bold leading-normal tracking-[0.015em]">
                            <span class="truncate">Doğrulama E-postasını Yeniden Gönder</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Theme Preference --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Temel Tema Tercihi</h2>
            <div class="flex items-center gap-4" x-data="{ theme: localStorage.getItem('theme') || 'dark' }">
                <button @click="theme = 'light'; localStorage.setItem('theme', 'light'); document.documentElement.classList.remove('dark')" :class="theme === 'light' ? 'border-primary bg-primary/10' : 'border-[#324d67]'" class="flex flex-col items-center gap-2 rounded-lg border p-4 w-32 transition-colors">
                    <span class="material-symbols-outlined text-2xl">light_mode</span>
                    <span class="text-sm font-medium">Light</span>
                </button>
                <button @click="theme = 'dark'; localStorage.setItem('theme', 'dark'); document.documentElement.classList.add('dark')" :class="theme === 'dark' ? 'border-primary bg-primary/10' : 'border-[#324d67]'" class="flex flex-col items-center gap-2 rounded-lg border p-4 w-32 transition-colors">
                    <span class="material-symbols-outlined text-2xl">dark_mode</span>
                    <span class="text-sm font-medium">Dark</span>
                </button>
            </div>
        </div>

        {{-- Data Privacy Settings --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Veri Gizliliği Ayarları</h2>
            <div class="space-y-4">
                <label class="flex items-center justify-between" for="analytics-toggle">
                    <div>
                        <p class="text-heading-light dark:text-heading-dark font-medium">Kullanım Analizi</p>
                        <p class="text-text-light dark:text-text-dark text-sm">Hizmetlerimizi iyileştirmemize yardımcı olmak için anonim kullanım verilerinin toplanmasına izin verin.</p>
                    </div>
                    <div class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent bg-[#324d67] transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-dark" role="switch" x-data="{ on: true }">
                        <span :class="{'translate-x-5': on, 'translate-x-0': !on}" aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        <input @click="on = !on" class="absolute inset-0 size-full cursor-pointer opacity-0" id="analytics-toggle" type="checkbox"/>
                    </div>
                </label>
                <label class="flex items-center justify-between" for="ads-toggle">
                    <div>
                        <p class="text-heading-light dark:text-heading-dark font-medium">Kişiselleştirilmiş Reklamlar</p>
                        <p class="text-text-light dark:text-text-dark text-sm">Deneyiminizi daha alakalı hale getirmek için kişiselleştirilmiş reklamların gösterilmesine izin verin.</p>
                    </div>
                    <div class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent bg-[#324d67] transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-dark" role="switch" x-data="{ on: false }">
                        <span :class="{'translate-x-5': on, 'translate-x-0': !on}" aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        <input @click="on = !on" class="absolute inset-0 size-full cursor-pointer opacity-0" id="ads-toggle" type="checkbox"/>
                    </div>
                </label>
            </div>
        </div>

        {{-- Device Management --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Cihaz Yönetimi</h2>
            <div class="space-y-4">
                {{-- This section will require backend logic to list user sessions --}}
                <div class="flex items-center justify-between rounded-lg bg-background-light dark:bg-background-dark p-4">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-2xl text-text-light dark:text-text-dark">desktop_windows</span>
                        <div>
                            <p class="text-heading-light dark:text-heading-dark font-medium">Windows 10, Chrome - <span class="text-green-400 font-normal">Mevcut oturum</span></p>
                            <p class="text-sm text-text-light dark:text-text-dark">İstanbul, TR - 192.168.1.1</p>
                        </div>
                    </div>
                    <button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#233648] text-white text-sm font-bold leading-normal tracking-[0.015em] opacity-50 cursor-not-allowed" disabled>
                        <span class="truncate">Çıkış Yap</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Account Activity Logs --}}
        <div class="rounded-xl border border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
            <h2 class="text-heading-light dark:text-heading-dark text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Hesap Etkinliği Kayıtları</h2>
            <div class="overflow-x-auto">
                {{-- This section will require backend logic to list activity --}}
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-text-light dark:text-text-dark uppercase">
                        <tr>
                            <th class="px-4 py-3">Eylem</th>
                            <th class="px-4 py-3">Tarih</th>
                            <th class="px-4 py-3">IP Adresi</th>
                            <th class="px-4 py-3">Konum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-border-light dark:border-border-dark">
                            <td class="px-4 py-3 text-heading-light dark:text-heading-dark">Başarılı Giriş</td>
                            <td class="px-4 py-3">2023-10-27 10:00 AM</td>
                            <td class="px-4 py-3">192.168.1.1</td>
                            <td class="px-4 py-3">İstanbul, TR</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="rounded-xl border border-solid border-red-500/50 bg-card-light dark:bg-card-dark p-6">
             @include('profile.partials.delete-user-form', ['user' => Auth::user()])
        </div>
    </div>
</x-user-dashboard-layout>
