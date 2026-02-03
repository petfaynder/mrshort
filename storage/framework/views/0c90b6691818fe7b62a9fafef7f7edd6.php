<?php if($ad->ad_type === \App\Enums\AdType::Banner && isset($ad->ad_data['image'])): ?>
    <?php
        $bannerSize = $ad->ad_data['size'] ?? 'auto';
        $sizeParts = explode('x', $bannerSize);
        $width = $sizeParts[0] ?? 'auto';
        $height = $sizeParts[1] ?? 'auto';
        $style = ($width !== 'auto' && $height !== 'auto') ? "width: {$width}px; height: {$height}px; max-width: 100%;" : 'max-width: 100%; height: auto;';
    ?>
    <a href="<?php echo e($ad->ad_data['url'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo e(asset('storage/' . $ad->ad_data['image'])); ?>" alt="Banner Ad" style="<?php echo e($style); ?>">
    </a>
<?php elseif($ad->ad_type === \App\Enums\AdType::Html && isset($ad->ad_data['content'])): ?>
    <div class="html-content">
        <?php echo $ad->ad_data['content']; ?>

    </div>
<?php elseif($ad->ad_type === \App\Enums\AdType::ThirdParty && isset($ad->ad_data['code'])): ?>
    <div class="third-party-content">
        <?php echo $ad->ad_data['code']; ?>

    </div>
<?php else: ?>
    
    <div style="border: 1px dashed #ccc; padding: 20px; text-align: center; color: #888;">
        <p>Ad content is not available.</p>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/partials/ad_display.blade.php ENDPATH**/ ?>