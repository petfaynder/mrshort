@props(['form' => null])

@php
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
@endphp

@if($showCaptcha)
    {{-- Captcha Script --}}
    {!! $captchaService->getScript() !!}
    
    {{-- Captcha Widget --}}
    <div class="captcha-container my-4">
        {!! $captchaService->getWidget() !!}
    </div>
@endif
