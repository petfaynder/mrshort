<div x-data="{ localShow: <?php if ((object) ('showModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'->value()); ?>')<?php echo e('showModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'); ?>')<?php endif; ?>, agreed: <?php if ((object) ('agreedToTerms') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('agreedToTerms'->value()); ?>')<?php echo e('agreedToTerms'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('agreedToTerms'); ?>')<?php endif; ?>, processing: false }">
    <template x-if="localShow">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" 
             style="background-color: rgba(0, 0, 0, 0.75);"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <!-- Header with Telegram gradient -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Telegram Traffic Bonus</h2>
                            <p class="text-white/80 text-sm">Earn +10% CPM for Telegram traffic</p>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-4">
                    <!--[if BLOCK]><![endif]--><?php if(session()->has('telegram_modal_error')): ?>
                        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-sm">
                            <?php echo e(session('telegram_modal_error')); ?>

                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                        If you primarily share your shortened links on Telegram channels, you can get a <strong class="text-blue-600 dark:text-blue-400">+10% CPM bonus</strong> on all your earnings!
                    </p>

                    <!-- Rules -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 space-y-3">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            How it works
                        </h3>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="text-green-500 mt-0.5">✓</span>
                                <span>Earn <strong>+10% CPM</strong> on all your link clicks</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-yellow-500 mt-0.5">⚡</span>
                                <span>Every <strong>500 clicks</strong>, we verify your traffic sources</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500 mt-0.5">📊</span>
                                <span>At least <strong>70%</strong> of your traffic must come from Telegram</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-red-500 mt-0.5">⚠️</span>
                                <span>If verification fails, <strong>7-day cooldown</strong> before re-enabling</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Terms checkbox -->
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" 
                               x-model="agreed"
                               class="mt-1 w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600 dark:text-gray-300 group-hover:text-gray-800 dark:group-hover:text-gray-100">
                            I understand that my traffic will be verified and the bonus will be revoked if verification fails.
                        </span>
                    </label>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 flex gap-3">
                    <button @click="processing = true; $wire.skipTelegramBonus().then(() => { localShow = false; processing = false; })"
                            :disabled="processing"
                            class="flex-1 px-4 py-2.5 text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors font-medium text-sm disabled:opacity-50">
                        <span x-show="!processing">Maybe Later</span>
                        <span x-show="processing" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                    <button @click="if(agreed) { processing = true; $wire.enableTelegramBonus().then(() => { localShow = false; processing = false; }); }"
                            :disabled="!agreed || processing"
                            :class="agreed ? 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700' : 'bg-gray-400 cursor-not-allowed'"
                            class="flex-1 px-4 py-2.5 text-white rounded-xl transition-colors font-medium text-sm disabled:opacity-50">
                        <span x-show="!processing">Enable Bonus</span>
                        <span x-show="processing" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Enabling...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/telegram-bonus-modal.blade.php ENDPATH**/ ?>