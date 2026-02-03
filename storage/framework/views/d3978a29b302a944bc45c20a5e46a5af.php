<div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md mb-8">
    <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Link Manager</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-border-light dark:border-border-dark">
                <tr>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Short Link</th>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Original Link</th>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Clicks</th>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Status</th>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Date</th>
                    <th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="p-3 text-primary font-semibold">
                            <a href="<?php echo e(url($link->code)); ?>" target="_blank" class="hover:underline"><?php echo e(request()->getHost()); ?>/<?php echo e($link->code); ?></a>
                        </td>
                        <td class="p-3 text-heading-light dark:text-heading-dark truncate max-w-xs" title="<?php echo e($link->original_url); ?>">
                            <?php echo e(Str::limit($link->original_url, 40)); ?>

                        </td>
                        <td class="p-3 text-heading-light dark:text-heading-dark"><?php echo e($link->clicks); ?></td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs font-semibold <?php echo e($link->is_hidden ? 'text-red-800 bg-red-100 dark:bg-red-900/50 dark:text-red-300' : 'text-green-800 bg-green-100 dark:bg-green-900/50 dark:text-green-300'); ?> rounded-full">
                                <?php echo e($link->is_hidden ? 'Hidden' : 'Active'); ?>

                            </span>
                        </td>
                        <td class="p-3 text-heading-light dark:text-heading-dark"><?php echo e($link->created_at->format('M d, Y')); ?></td>
                        <td class="p-3">
                            <div class="flex justify-end items-center gap-2">
                                <a href="<?php echo e(route('user.links.index')); ?>" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700" title="Manage">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </a>
                                <a href="<?php echo e(route('stats', $link->code)); ?>" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700" title="Stats">
                                    <span class="material-symbols-outlined text-sm">bar_chart</span>
                                </a>
                                <button 
                                    x-data
                                    @click="$dispatch('open-confirm-modal', { 
                                        id: 'delete-recent-link-<?php echo e($link->id); ?>', 
                                        onConfirm: () => $wire.deleteLink(<?php echo e($link->id); ?>) 
                                    })"
                                    wire:loading.attr="disabled" 
                                    class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed" 
                                    title="Delete"
                                >
                                    <span wire:loading.remove wire:target="deleteLink(<?php echo e($link->id); ?>)" class="material-symbols-outlined text-sm text-red-500">delete</span>
                                    <span wire:loading wire:target="deleteLink(<?php echo e($link->id); ?>)" class="material-symbols-outlined text-sm text-red-500 animate-spin">progress_activity</span>
                                </button>
                                <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'delete-recent-link-'.e($link->id).'','title' => 'Delete Link','message' => 'Are you sure you want to delete this link?','confirmText' => 'Delete','cancelText' => 'Cancel','confirmColor' => 'red','icon' => 'delete']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'delete-recent-link-'.e($link->id).'','title' => 'Delete Link','message' => 'Are you sure you want to delete this link?','confirmText' => 'Delete','cancelText' => 'Cancel','confirmColor' => 'red','icon' => 'delete']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10)): ?>
<?php $attributes = $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10; ?>
<?php unset($__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10)): ?>
<?php $component = $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10; ?>
<?php unset($__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10); ?>
<?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No links created yet.
                        </td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/recent-links.blade.php ENDPATH**/ ?>