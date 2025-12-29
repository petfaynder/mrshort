<div>
    @if($showModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop - No click to close -->
        <div class="fixed inset-0 bg-gray-900/90 transition-opacity"></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-2xl transition-all w-full max-w-lg">
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-3xl">block</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white" id="modal-title">
                                Account Deactivated
                            </h3>
                            <p class="text-red-100 text-sm">Your account has been temporarily suspended</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-6">
                    <div class="mb-5 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <span class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-wider">Reason for Deactivation</span>
                        <p class="text-red-800 dark:text-red-200 font-medium mt-1">{{ $reason }}</p>
                    </div>
                    
                    @if($deactivatedAt)
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span class="font-medium">Deactivated on:</span> {{ $deactivatedAt }}
                    </p>
                    @endif

                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-5">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 flex-shrink-0">info</span>
                            <div>
                                <p class="text-amber-800 dark:text-amber-200 text-sm font-medium mb-1">You can appeal this decision</p>
                                <p class="text-amber-700 dark:text-amber-300 text-sm">If you believe this is a mistake, please contact our support team. You have 30 days to appeal before your account is permanently banned.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            While your account is deactivated, you can only access the support page to submit an appeal or get help.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex gap-3 justify-center">
                    @if($isImpersonating)
                    <button 
                        wire:click="dismiss" 
                        type="button" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors"
                    >
                        Dismiss (Admin)
                    </button>
                    @endif
                    <button 
                        wire:click="goToSupport" 
                        type="button" 
                        class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
                    >
                        <span class="material-symbols-outlined text-lg">support_agent</span>
                        Contact Support to Appeal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
