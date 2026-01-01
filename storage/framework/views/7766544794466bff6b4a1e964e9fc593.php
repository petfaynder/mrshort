<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <!--[if BLOCK]><![endif]--><?php if($this->activeCampaign): ?>
        <div class="mb-6 rounded-lg border-2 border-amber-500 bg-amber-50 dark:bg-amber-950/20 p-4">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🎉</span>
                    <div>
                        <h3 class="text-lg font-bold text-amber-900 dark:text-amber-100">
                            Active Campaign: <?php echo e($this->activeCampaign->name); ?>

                        </h3>
                        <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                            All CPM rates are currently multiplied by <span class="font-bold"><?php echo e($this->activeCampaign->multiplier); ?>x</span>
                        </p>
                        <div class="flex gap-4 mt-2 text-xs text-amber-600 dark:text-amber-400">
                            <span>Started: <?php echo e($this->activeCampaign->start_date->format('M d, Y H:i')); ?></span>
                            <span>•</span>
                            <span>Ends: <?php echo e($this->activeCampaign->end_date->format('M d, Y H:i')); ?></span>
                            <span>•</span>
                            <span class="font-semibold">
                                <?php echo e($this->activeCampaign->end_date->diffForHumans()); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm bg-white dark:bg-gray-800 hover:shadow-md transition-shadow <?php echo e($this->activeCampaign ? 'opacity-75' : ''); ?>">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="fi fi-<?php echo e(strtolower($country->iso_code)); ?> text-xl"></span>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white"><?php echo e($country->name); ?></h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="country_rates.<?php echo e($country->id); ?>.publisher_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Yayıncı Oranı</label>
                            <input type="number" 
                                   step="0.0001" 
                                   wire:model.defer="data.country_rates.<?php echo e($country->id); ?>.publisher_rate" 
                                   id="country_rates.<?php echo e($country->id); ?>.publisher_rate"
                                   <?php echo e($this->activeCampaign ? 'disabled' : ''); ?>

                                   class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["country_rates.{$country->id}.publisher_rate"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <label for="country_rates.<?php echo e($country->id); ?>.advertiser_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reklamcı Oranı</label>
                            <input type="number" 
                                   step="0.0001" 
                                   wire:model.defer="data.country_rates.<?php echo e($country->id); ?>.advertiser_rate" 
                                   id="country_rates.<?php echo e($country->id); ?>.advertiser_rate"
                                   <?php echo e($this->activeCampaign ? 'disabled' : ''); ?>

                                   class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["country_rates.{$country->id}.advertiser_rate"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div class="flex justify-end">
            <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'submit','icon' => 'heroicon-o-check','size' => 'lg','disabled' => !!$this->activeCampaign]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','icon' => 'heroicon-o-check','size' => 'lg','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!!$this->activeCampaign)]); ?>
                CPM Oranlarını Kaydet
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
        </div>
    </form>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/filament/pages/manage-country-cpm-rates.blade.php ENDPATH**/ ?>