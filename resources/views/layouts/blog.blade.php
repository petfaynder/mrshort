<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    
    {{-- SEO Meta Tags from Site Settings --}}
    <title>@yield('title', setting('site_name', 'MrShort') . ' - Blog')</title>
    <meta name="description" content="@yield('meta_description', setting('seo_description', 'Turn your links into revenue.'))"/>
    <meta name="keywords" content="{{ setting('seo_keywords', 'link shortener, url shortener, earn money, monetize links') }}"/>
    
    {{-- Favicon --}}
    @if(setting('favicon_url'))
    <link rel="icon" href="{{ setting('favicon_url') }}" type="image/x-icon">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    {{-- Custom Front Head Code from Settings --}}
    {!! setting('front_head_code', '') !!}
    
    @stack('head')
</head>
<body class="bg-[#050505] text-white font-display overflow-x-hidden">
    @include('partials.header')
    
    <main class="pt-20">
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    {{-- Cookie Consent Banner --}}
    @livewire('cookie-consent')
    
    {{-- Custom Footer Code from Settings --}}
    {!! setting('footer_code', '') !!}
    
    @stack('scripts')
</body>
</html>
