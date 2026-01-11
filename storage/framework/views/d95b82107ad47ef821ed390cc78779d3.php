<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'success']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['type' => 'success']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $types = [
        'success' => ['bg' => 'bg-green-600', 'icon' => 'check_circle'],
        'error' => ['bg' => 'bg-red-600', 'icon' => 'error'],
        'info' => ['bg' => 'bg-blue-600', 'icon' => 'info'],
        'warning' => ['bg' => 'bg-yellow-600', 'icon' => 'warning'],
    ];
    $config = $types[$type] ?? $types['success'];
?>

<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 3000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     class="fixed bottom-4 right-4 z-50 rounded-lg <?php echo e($config['bg']); ?> px-4 py-3 text-white shadow-lg">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined"><?php echo e($config['icon']); ?></span>
        <span><?php echo e($slot); ?></span>
    </div>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/components/toast-notification.blade.php ENDPATH**/ ?>