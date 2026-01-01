<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['form' => null]));

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

foreach (array_filter((['form' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $captchaService = app(\App\Services\CaptchaService::class);
    
    // Check if captcha should be shown for this specific form
    $showCaptcha = false;
    
    if (setting('captcha_enabled', false)) {
        if ($form) {
            // Check specific form setting
            $formSettingKey = 'captcha_on_' . $form;
            $showCaptcha = setting($formSettingKey, false);
        } else {
            // If no form specified, show captcha (backward compatibility)
            $showCaptcha = true;
        }
    }
?>

<?php if($showCaptcha): ?>
    
    <?php echo $captchaService->getScript(); ?>

    
    
    <div class="captcha-container my-4">
        <?php echo $captchaService->getWidget(); ?>

    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/components/captcha.blade.php ENDPATH**/ ?>