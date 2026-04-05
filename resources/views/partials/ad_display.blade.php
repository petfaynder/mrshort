@if ($ad->ad_type === \App\Enums\AdType::Banner)
    @if (!empty($ad->ad_data['custom_js']))
        {{-- Banner with custom JavaScript code (e.g. ad network scripts) --}}
        <div class="banner-js-content w-full h-full overflow-hidden relative" style="max-width: 100%; max-height: 100%;">
            {!! $ad->ad_data['custom_js'] !!}
        </div>
    @elseif (!empty($ad->ad_data['image']))
        {{-- Banner with uploaded image --}}
        @php
            $bannerSize = $ad->ad_data['size'] ?? 'auto';
            $sizeParts = explode('x', $bannerSize);
            $width = $sizeParts[0] ?? 'auto';
            $height = $sizeParts[1] ?? 'auto';
            $style = ($width !== 'auto' && $height !== 'auto') ? "width: {$width}px; height: {$height}px; max-width: 100%;" : 'max-width: 100%; height: auto;';
        @endphp
        <a href="{{ $ad->ad_data['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">
            <img src="{{ asset('storage/' . $ad->ad_data['image']) }}" alt="Banner Ad" style="{{ $style }}">
        </a>
    @elseif (!empty($ad->ad_data['url']))
        {{-- Banner with only a URL (no image, no JS) --}}
        <a href="{{ $ad->ad_data['url'] }}" target="_blank" rel="noopener noreferrer" class="w-full h-full flex items-center justify-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-lg hover:opacity-90 transition-opacity">
            <span class="material-icons mr-2">open_in_new</span> Visit Sponsor
        </a>
    @else
        <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded">
            <span class="text-gray-400 text-sm">Ad Slot Available</span>
        </div>
    @endif
@elseif ($ad->ad_type === \App\Enums\AdType::Html && isset($ad->ad_data['content']))
    <div class="html-content">
        {!! $ad->ad_data['content'] !!}
    </div>
@elseif ($ad->ad_type === \App\Enums\AdType::ThirdParty && isset($ad->ad_data['code']))
    <div class="third-party-content">
        {!! $ad->ad_data['code'] !!}
    </div>
@else
    {{-- Fallback for misconfigured ads --}}
    <div style="border: 1px dashed #ccc; padding: 20px; text-align: center; color: #888;">
        <p>Ad content is not available.</p>
    </div>
@endif
