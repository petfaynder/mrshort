<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Blocked Link - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        {{-- Icon --}}
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-500/10 mb-6">
            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>

        {{-- Title --}}
        <h1 class="text-3xl font-bold text-white mb-3">Link Blocked</h1>
        
        {{-- Description --}}
        <p class="text-gray-400 mb-6">
            This short link has been blocked due to a violation of our policies. 
            If you believe this is an error, please contact us.
        </p>

        {{-- Link Code --}}
        <div class="bg-gray-800/50 border border-gray-700 rounded-xl px-4 py-3 mb-8 inline-block">
            <span class="text-gray-500 text-sm">Link Code:</span>
            <code class="text-red-400 font-mono ml-2">{{ $code ?? 'N/A' }}</code>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="/" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Home
            </a>
            <a href="mailto:support@{{ request()->getHost() }}?subject=Blocked Link Inquiry - {{ $code ?? '' }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-700 hover:bg-gray-600 text-gray-200 font-semibold rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contact
            </a>
        </div>

        {{-- Footer --}}
        <p class="text-gray-600 text-sm mt-8">
            © {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>
</body>
</html>
