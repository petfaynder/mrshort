
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
<style>
    .font-poppins {
        font-family: 'Poppins', sans-serif;
    }
    .material-icons-outlined {
        font-size: 20px;
    }
</style>


<div class="bg-card-light dark:bg-card-dark p-6 md:p-8 rounded-lg shadow-sm font-poppins">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h2 class="text-2xl font-semibold text-heading-light dark:text-heading-dark mb-4 sm:mb-0">Campaigns</h2>
        <a href="<?php echo e(route('user.ads.create')); ?>" class="bg-primary text-white px-5 py-2.5 rounded-md font-semibold text-sm flex items-center gap-2 hover:bg-blue-600 transition-colors duration-200">
            <span class="material-icons-outlined" style="font-size: 18px;">add</span>
            <?php echo e(__('Create New Campaign')); ?>

        </a>
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="relative flex-grow">
            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light dark:text-text-dark">search</span>
            <input wire:model.live="search" class="w-full pl-10 pr-4 py-2.5 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md text-sm placeholder:text-text-light placeholder:dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary text-heading-light dark:text-heading-dark" placeholder="Search by name..." type="text"/>
        </div>
        <div class="flex gap-2 flex-wrap">
            <select wire:model.live="status" class="bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md text-sm text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary py-2.5">
                <option value="">Status: All</option>
                <option value="active">Active</option>
                <option value="paused">Paused</option>
            </select>
            <select wire:model.live="type" class="bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md text-sm text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary py-2.5">
                <option value="">Type: All</option>
                <option value="link">Link Campaign</option>
                <option value="banner">Banner Campaign</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
                <thead class="border-b border-border-light dark:border-border-dark">
                <tr>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider"><?php echo e(__('Campaign Name')); ?></th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider"><?php echo e(__('Type')); ?></th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider"><?php echo e(__('Approval')); ?></th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider"><?php echo e(__('Active')); ?></th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider"><?php echo e(__('Impressions')); ?></th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider"><?php echo e(__('Clicks')); ?></th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider text-right"><?php echo e(__('Actions')); ?></th>
                </tr>
            </thead>
            <tbody class="text-heading-light dark:text-heading-dark">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $adCampaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-border-light dark:border-border-dark">
                        <td class="p-4 whitespace-nowrap text-sm font-medium">
                            <?php echo e($campaign->name); ?>

                        </td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">
                            <?php echo e($campaign->campaign_type->value); ?>

                        </td>
                        <td class="p-4 text-sm min-w-[120px]">
                            <?php
                                $status = $campaign->approval_status ?? 'pending';
                            ?>
                            <!--[if BLOCK]><![endif]--><?php if($status === 'approved'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <?php echo e(__('Approved')); ?>

                                </span>
                            <?php elseif($status === 'rejected'): ?>
                                <div class="flex items-center gap-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <?php echo e(__('Rejected')); ?>

                                    </span>
                                    <!--[if BLOCK]><![endif]--><?php if($campaign->rejection_reason): ?>
                                        <span class="material-icons-outlined text-red-500 cursor-help" style="font-size: 16px;" title="<?php echo e($campaign->rejection_reason); ?>">info</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <?php echo e(__('Pending')); ?>

                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">
                            <!--[if BLOCK]><![endif]--><?php if($campaign->is_active): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <?php echo e(__('Yes')); ?>

                                </span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <?php echo e(__('No')); ?>

                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">
                            <?php echo e($campaign->total_impressions); ?>

                        </td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">
                            <?php echo e($campaign->total_clicks); ?>

                        </td>
                        <td class="p-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="<?php echo e(route('user.ads.edit', $campaign)); ?>" class="text-primary hover:underline mr-3"><?php echo e(__('Edit')); ?></a>
                            <button 
                                type="button"
                                x-data
                                @click="$dispatch('open-confirm-modal', { 
                                    id: 'delete-campaign-<?php echo e($campaign->id); ?>', 
                                    onConfirm: () => $wire.deleteCampaign(<?php echo e($campaign->id); ?>) 
                                })"
                                wire:loading.attr="disabled" 
                                class="text-red-600 hover:underline disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1 float-right"
                            >
                                <span wire:loading.remove wire:target="deleteCampaign(<?php echo e($campaign->id); ?>)"><?php echo e(__('Delete')); ?></span>
                                <span wire:loading wire:target="deleteCampaign(<?php echo e($campaign->id); ?>)" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                            </button>
                            <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'delete-campaign-'.e($campaign->id).'','title' => 'Delete Campaign','message' => 'Are you sure you want to delete this campaign?','confirmText' => 'Delete','cancelText' => 'Cancel','confirmColor' => 'red','icon' => 'delete']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'delete-campaign-'.e($campaign->id).'','title' => 'Delete Campaign','message' => 'Are you sure you want to delete this campaign?','confirmText' => 'Delete','cancelText' => 'Cancel','confirmColor' => 'red','icon' => 'delete']); ?>
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
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td class="text-center py-20 px-4" colspan="7">
                            <p class="text-text-light dark:text-text-dark"><?php echo e(__("You don't have any active campaigns yet.")); ?></p>
                        </td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
        
        <div class="mt-4">
            <?php echo e($adCampaigns->links()); ?>

        </div>
    </div>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/ad-campaigns.blade.php ENDPATH**/ ?>