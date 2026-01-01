<?php if (isset($component)) { $__componentOriginal333b9e857c198bd0078774586fa40930 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal333b9e857c198bd0078774586fa40930 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.user-dashboard-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('user-dashboard-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php if(session('status')): ?>
        <div class="flex items-center gap-3 rounded-lg border border-primary/50 bg-primary/10 p-3 text-sm text-primary dark:text-blue-400 mb-6">
            <span class="material-symbols-outlined">check_circle</span>
            <p><?php echo e(session('status')); ?></p>
        </div>
    <?php endif; ?>

    
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
        <p>It is very recommended to enable 2 Factor Authentication on your security settings to ensure the security of your account</p>
    </div>
    <div class="bg-gray-200 p-4 mb-6 text-center dark:bg-gray-800 dark:text-gray-300">
        <p>Reklam Alanı</p>
    </div>

    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.announcements', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    
    <div data-tutorial="gamification-widgets" class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.streak-display', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        
        
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.daily-challenges', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

        
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.weekly-competition', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>

    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.milestone-modal', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    
    <div data-tutorial="shortener">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.quick-shortener', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-5', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>

    <div data-tutorial="date-filter" class="flex justify-end mb-4">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.stats-date-filter', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-6', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>

    
    <div data-tutorial="stats">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.dashboard-stats', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-7', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>
    
    
    <div data-tutorial="chart">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.earnings-chart', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-8', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>

    
    <div data-tutorial="performance">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.performance-overview', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-9', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>

    
    <div data-tutorial="recent-links">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.recent-links', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-10', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>

    
    <div data-tutorial="suggestions" class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md mb-8">
        <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Optimized Link Suggestions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-start gap-4 p-4 bg-background-light dark:bg-background-dark rounded-lg">
                <div class="mt-1 p-2 bg-green-100 dark:bg-green-900/50 rounded-full"><span class="material-symbols-outlined text-green-500 text-base">trending_up</span></div>
                <div>
                    <h4 class="font-semibold text-heading-light dark:text-heading-dark">High Traffic Potential</h4>
                    <p class="text-sm text-text-light dark:text-text-dark">Your link for "Tech Gadgets 2024" is performing well. Consider creating more content around this topic.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-4 bg-background-light dark:bg-background-dark rounded-lg">
                <div class="mt-1 p-2 bg-blue-100 dark:bg-blue-900/50 rounded-full"><span class="material-symbols-outlined text-primary text-base">public</span></div>
                <div>
                    <h4 class="font-semibold text-heading-light dark:text-heading-dark">Geo-Targeting Tip</h4>
                    <p class="text-sm text-text-light dark:text-text-dark">High CPM in Germany. Try sharing your links in German-speaking forums for higher earnings.</p>
                </div>
            </div>
        </div>
    </div>

    
    <div data-tutorial="payment-activity" class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.payment-summary', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-11', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.recent-activity', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3978033609-12', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal333b9e857c198bd0078774586fa40930)): ?>
<?php $attributes = $__attributesOriginal333b9e857c198bd0078774586fa40930; ?>
<?php unset($__attributesOriginal333b9e857c198bd0078774586fa40930); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal333b9e857c198bd0078774586fa40930)): ?>
<?php $component = $__componentOriginal333b9e857c198bd0078774586fa40930; ?>
<?php unset($__componentOriginal333b9e857c198bd0078774586fa40930); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/user/dashboard/index.blade.php ENDPATH**/ ?>