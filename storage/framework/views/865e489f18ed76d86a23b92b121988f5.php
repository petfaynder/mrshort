<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmColor' => 'red',
    'icon' => 'warning'
]));

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

foreach (array_filter(([
    'id',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmColor' => 'red',
    'icon' => 'warning'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$iconConfig = [
    'warning' => ['icon' => 'warning', 'bg' => 'bg-red-100 dark:bg-red-500/20', 'color' => 'text-red-600 dark:text-red-400'],
    'delete' => ['icon' => 'delete', 'bg' => 'bg-red-100 dark:bg-red-500/20', 'color' => 'text-red-600 dark:text-red-400'],
    'info' => ['icon' => 'info', 'bg' => 'bg-blue-100 dark:bg-blue-500/20', 'color' => 'text-blue-600 dark:text-blue-400'],
    'success' => ['icon' => 'check_circle', 'bg' => 'bg-green-100 dark:bg-green-500/20', 'color' => 'text-green-600 dark:text-green-400'],
];

$buttonColors = [
    'red' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
    'blue' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
    'green' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
];

$currentIcon = $iconConfig[$icon] ?? $iconConfig['warning'];
$buttonColor = $buttonColors[$confirmColor] ?? $buttonColors['red'];
?>

<div 
    id="<?php echo e($id); ?>"
    x-data="{ open: false, onConfirm: null }"
    x-on:open-confirm-modal.window="
        if ($event.detail.id === $el.id) {
            open = true;
            onConfirm = $event.detail.onConfirm;
        }
    "
    x-on:keydown.escape.window="open = false"
    <?php echo e($attributes->merge(['class' => ''])); ?>

>
    <!-- Backdrop -->
    <div 
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm"
        @click="open = false"
        style="display: none;"
    ></div>

    <!-- Modal -->
    <div 
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            class="w-full max-w-md bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700"
            @click.away="open = false"
        >
            <!-- Content -->
            <div class="p-6 text-center">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full <?php echo e($currentIcon['bg']); ?> mb-5">
                    <span class="material-symbols-outlined text-3xl <?php echo e($currentIcon['color']); ?>"><?php echo e($currentIcon['icon']); ?></span>
                </div>
                
                <!-- Title -->
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    <?php echo e($title); ?>

                </h3>
                
                <!-- Message -->
                <p class="text-sm text-gray-500 dark:text-slate-400">
                    <?php echo e($message); ?>

                </p>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/50 rounded-b-xl flex justify-center gap-3">
                <button 
                    @click="open = false"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-slate-500"
                >
                    <?php echo e($cancelText); ?>

                </button>
                <button 
                    @click="if(onConfirm) onConfirm(); open = false;"
                    class="px-5 py-2.5 text-sm font-medium text-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 <?php echo e($buttonColor); ?>"
                >
                    <?php echo e($confirmText); ?>

                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/components/confirm-modal.blade.php ENDPATH**/ ?>