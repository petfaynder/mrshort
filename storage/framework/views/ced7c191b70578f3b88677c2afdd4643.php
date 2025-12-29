<div class="lg:col-span-2 bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
    <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Payment Information Summary</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-sm text-text-light dark:text-text-dark mb-1">Current Balance</p>
            <p class="text-3xl font-bold text-green-500">$<?php echo e(number_format($balance, 2)); ?></p>
            <p class="text-sm text-text-light dark:text-text-dark mt-2">Available for withdrawal.</p>
        </div>
        <div>
            <p class="text-sm text-text-light dark:text-text-dark mb-1">Payment Threshold</p>
            <p class="text-2xl font-bold text-heading-light dark:text-heading-dark">$<?php echo e(number_format($threshold, 2)); ?></p>
            <div class="w-full bg-background-light dark:bg-background-dark rounded-full h-2.5 mt-2">
                <div class="bg-primary h-2.5 rounded-full" style="width: <?php echo e($progress); ?>%"></div>
            </div>
        </div>
        <div>
            <p class="text-sm text-text-light dark:text-text-dark mb-1">Last Payment Date</p>
            <p class="text-lg font-semibold text-heading-light dark:text-heading-dark"><?php echo e($lastPaymentDate); ?></p>
        </div>
        <div>
            <p class="text-sm text-text-light dark:text-text-dark mb-1">Next Payment Date</p>
            <p class="text-lg font-semibold text-heading-light dark:text-heading-dark"><?php echo e($nextPaymentDate); ?></p>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/payment-summary.blade.php ENDPATH**/ ?>