<div>
    <!--[if BLOCK]><![endif]--><?php if($announcements->count() > 0): ?>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <h4 class="font-bold mb-2">Duyurular</h4>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-4 last:mb-0">
                    <p class="font-semibold"><?php echo e($announcement->title); ?></p>
                    <p class="text-sm"><?php echo e($announcement->content); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/announcements.blade.php ENDPATH**/ ?>