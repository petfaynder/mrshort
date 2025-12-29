<div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
    <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Recent Activity Feed</h3>
    <ul class="space-y-4">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li class="flex items-start gap-3">
                <div class="mt-1 p-1.5 <?php echo e($activity['color_class']); ?> rounded-full">
                    <span class="material-symbols-outlined text-base"><?php echo e($activity['icon']); ?></span>
                </div>
                <div>
                    <p class="text-sm text-heading-light dark:text-heading-dark"><?php echo e($activity['description']); ?></p>
                    <p class="text-xs text-text-light dark:text-text-dark"><?php echo e($activity['created_at']->diffForHumans()); ?></p>
                </div>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="text-sm text-text-light dark:text-text-dark">No recent activity.</li>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </ul>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/recent-activity.blade.php ENDPATH**/ ?>