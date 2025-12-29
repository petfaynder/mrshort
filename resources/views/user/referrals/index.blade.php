<x-user-dashboard-layout>
    <div class="p-4 sm:p-6 md:p-8 bg-card-light dark:bg-card-dark rounded-xl">
        <div class="flex flex-col gap-6">
            <div class="flex flex-wrap gap-2">
                <a class="text-text-light dark:text-text-dark text-base font-medium leading-normal hover:text-primary" href="{{ route('dashboard') }}">Dashboard</a>
                <span class="text-text-light dark:text-text-dark text-base font-medium leading-normal">/</span>
                <span class="text-heading-light dark:text-heading-dark text-base font-medium leading-normal">Referrals</span>
            </div>
            <div class="flex flex-wrap justify-between gap-3"><p class="text-4xl font-black leading-tight tracking-[-0.033em] text-heading-light dark:text-heading-dark min-w-72">Referrals</p></div>
            <h2 class="text-[22px] font-bold leading-tight tracking-[-0.015em] pt-5 text-heading-light dark:text-heading-dark">Referral System Overview</h2>
            
            {{-- Dynamic Referral Stats from Backend --}}
            <livewire:user.referral-stats />
            
            <div class="flex flex-col gap-4 pt-8">
                <h3 class="text-lg font-bold leading-tight tracking-[-0.015em] text-heading-light dark:text-heading-dark">Invite New Users</h3>
                <div class="flex flex-col sm:flex-row items-center gap-2 p-4 rounded-lg border border-border-light dark:border-border-dark" x-data="{ 
                    referralLink: '{{ route('register', ['referral_code' => auth()->user()->referral_code]) }}',
                    copyToClipboard() {
                        navigator.clipboard.writeText(this.referralLink);
                        $dispatch('notify', { message: 'Referral link copied to clipboard!', type: 'success' });
                    }
                }">
                    <input class="w-full flex-1 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-heading-light dark:text-heading-dark rounded-lg px-3 h-10 focus:outline-none focus:ring-2 focus:ring-primary" readonly type="text" x-model="referralLink"/>
                    <button @click="copyToClipboard()" class="flex items-center justify-center gap-2 h-10 px-4 rounded-lg bg-primary text-white text-sm font-bold w-full sm:w-auto hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined text-base">content_copy</span>
                        <span>Copy Link</span>
                    </button>
                </div>
                <div class="flex items-center gap-4" x-data="{ 
                    referralLink: '{{ route('register', ['referral_code' => auth()->user()->referral_code]) }}',
                    shareText: 'Join MrShort and start earning money from your links! Use my referral link:'
                }">
                    <p class="text-sm font-medium text-text-light dark:text-text-dark">Share via:</p>
                    <div class="flex gap-2">
                        <a :href="'https://twitter.com/intent/tweet?text=' + encodeURIComponent(shareText + ' ' + referralLink)" target="_blank" class="flex items-center justify-center size-9 rounded-full bg-background-light dark:bg-[#233648] hover:bg-gray-200 dark:hover:bg-[#324d67] transition-colors" title="Share on Twitter">
                            <svg aria-hidden="true" class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
                        </a>
                        <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(referralLink)" target="_blank" class="flex items-center justify-center size-9 rounded-full bg-background-light dark:bg-[#233648] hover:bg-gray-200 dark:hover:bg-[#324d67] transition-colors" title="Share on Facebook">
                            <svg aria-hidden="true" class="size-5" fill="currentColor" viewBox="0 0 24 24"><path clip-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" fill-rule="evenodd"></path></svg>
                        </a>
                        <a :href="'mailto:?subject=' + encodeURIComponent('Join MrShort - Link Shortening Service') + '&body=' + encodeURIComponent(shareText + '\n\n' + referralLink)" class="flex items-center justify-center size-9 rounded-full bg-background-light dark:bg-[#233648] hover:bg-gray-200 dark:hover:bg-[#324d67] transition-colors" title="Share via Email">
                            <span class="material-symbols-outlined text-xl">mail</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-4 pt-2">
                <h3 class="text-lg font-bold leading-tight tracking-[-0.015em] text-heading-light dark:text-heading-dark">Performance Tips & Best Practices</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-4 p-4 rounded-lg border border-border-light dark:border-border-dark">
                        <div class="flex-shrink-0 size-8 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-lg">share</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-base text-heading-light dark:text-heading-dark">Share Strategically</h4>
                            <p class="text-sm text-text-light dark:text-text-dark mt-1">Post your referral link on social media bios, forums, and in relevant blog comments to reach a wider audience.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-lg border border-border-light dark:border-border-dark">
                        <div class="flex-shrink-0 size-8 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-lg">lightbulb</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-base text-heading-light dark:text-heading-dark">Explain the Benefits</h4>
                            <p class="text-sm text-text-light dark:text-text-dark mt-1">When sharing, highlight how our service can help others. A personal recommendation is more effective.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-lg border border-border-light dark:border-border-dark">
                        <div class="flex-shrink-0 size-8 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-lg">group</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-base text-heading-light dark:text-heading-dark">Target the Right Audience</h4>
                            <p class="text-sm text-text-light dark:text-text-dark mt-1">Focus on content creators, marketers, and businesses who will benefit most from a powerful link shortener.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-lg border border-border-light dark:border-border-dark">
                        <div class="flex-shrink-0 size-8 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-lg">email</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-base text-heading-light dark:text-heading-dark">Leverage Email Marketing</h4>
                            <p class="text-sm text-text-light dark:text-text-dark mt-1">Include your referral link in your email signature or newsletter to engage your existing contacts.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-4 pt-8">
                <h3 class="text-lg font-bold leading-tight tracking-[-0.015em] text-heading-light dark:text-heading-dark">My Referrals</h3>
                <livewire:user.referral-manager />
            </div>
        </div>
    </div>
</x-user-dashboard-layout>
