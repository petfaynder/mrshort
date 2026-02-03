<div>
    <!--[if BLOCK]><![endif]--><?php if($showModal && $ticket): ?>
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/75 transition-opacity"></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-2xl transition-all w-full max-w-lg">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-2xl">mail</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white" id="modal-title">
                                Message from Admin
                            </h3>
                            <p class="text-blue-100 text-sm">You have a new message from the administration</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-5">
                    <div class="mb-4">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</span>
                        <p class="text-gray-900 dark:text-white font-medium mt-1"><?php echo e($ticket->subject); ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Message</span>
                        <div class="mt-1 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-gray-700 dark:text-gray-200 whitespace-pre-wrap"><?php echo e($ticket->message); ?></p>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <?php echo e($ticket->created_at->format('d M Y, H:i')); ?>

                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex gap-3 justify-end">
                    <button 
                        wire:click="dismiss" 
                        type="button" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors"
                    >
                        Dismiss
                    </button>
                    <button 
                        wire:click="viewTicket" 
                        type="button" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
                    >
                        <span class="material-symbols-outlined text-lg">forum</span>
                        View Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/admin-message-modal.blade.php ENDPATH**/ ?>