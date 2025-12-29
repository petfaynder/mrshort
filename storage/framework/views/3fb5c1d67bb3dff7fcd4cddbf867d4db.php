<div>
    <select wire:model.live="selectedMonth" class="bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent text-text-light dark:text-text-dark">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($month['value']); ?>"><?php echo e($month['label']); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </select>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/stats-date-filter.blade.php ENDPATH**/ ?>