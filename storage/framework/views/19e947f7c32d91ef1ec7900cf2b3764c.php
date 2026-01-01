<div class="bg-card-light dark:bg-card-dark p-6 rounded-lg mb-8 shadow-md">
    <div class="flex items-center gap-4">
        <input wire:model="original_url" wire:keydown.enter="shortenLink" class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent text-heading-light dark:text-heading-dark placeholder:text-text-light dark:placeholder:text-text-dark" placeholder="Paste your long URL here" type="text"/>
        <button wire:click="shortenLink" wire:loading.attr="disabled" class="bg-primary text-white font-semibold px-6 py-3 rounded-lg flex items-center gap-2 whitespace-nowrap hover:bg-blue-600 transition-colors disabled:opacity-75 disabled:cursor-not-allowed">
            <span wire:loading.remove wire:target="shortenLink" class="material-symbols-outlined">content_cut</span>
            <span wire:loading wire:target="shortenLink" class="material-symbols-outlined animate-spin">progress_activity</span>
            Shrink Now
        </button>
    </div>
    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['original_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm mt-2 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
    
    
    <!--[if BLOCK]><![endif]--><?php if($shortenedLink): ?>
        <div x-data="{ copied: false }" class="mt-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-lg flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                <a href="<?php echo e($shortenedLink); ?>" target="_blank" class="text-green-700 dark:text-green-400 font-semibold text-lg truncate hover:underline"><?php echo e($shortenedLink); ?></a>
            </div>
            <button 
                x-on:click="navigator.clipboard.writeText('<?php echo e($shortenedLink); ?>'); copied = true; setTimeout(() => copied = false, 2000)" 
                class="p-2 hover:bg-green-200 dark:hover:bg-green-800 rounded-lg transition-colors text-green-700 dark:text-green-400 flex items-center gap-2" 
                title="Copy to Clipboard">
                <span x-show="!copied" class="material-symbols-outlined">content_copy</span>
                <span x-show="copied" class="material-symbols-outlined">check</span>
                <span x-show="copied" class="text-sm font-medium">Copied!</span>
            </button>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>

<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/quick-shortener.blade.php ENDPATH**/ ?>