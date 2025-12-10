<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>API Documentation - MrShort</title>
    <meta name="description" content="MrShort API Documentation - Integrate our powerful link shortening and monetization API into your applications."/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
</head>
<body class="bg-[#050505] text-white font-display overflow-x-hidden">
    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <main class="pt-32 pb-24 px-4 sm:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="mb-12">
                <h1 class="text-5xl md:text-7xl font-bold tracking-tighter text-white mb-6 leading-none">
                    API<br/>
                    <span class="text-gray-700">DOCUMENTATION.</span>
                </h1>
                <p class="text-gray-400 text-lg max-w-2xl">Integrate MrShort's powerful link shortening and monetization capabilities into your own applications with our simple REST API.</p>
            </div>

            <!-- Quick Start -->
            <div class="mb-16">
                <div class="bg-gradient-to-r from-electric-blue/20 to-bright-magenta/20 border border-electric-blue/30 rounded-3xl p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="material-symbols-outlined text-4xl text-electric-blue">rocket_launch</span>
                        <h2 class="text-3xl font-bold text-white">Quick Start</h2>
                    </div>
                    <p class="text-gray-300 text-lg mb-6">Get started with MrShort API in minutes. No complex setup required.</p>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="bg-black/30 rounded-2xl p-6 text-center">
                            <div class="text-4xl font-bold text-electric-blue mb-2">1</div>
                            <p class="text-gray-400">Create an account at MrShort.io</p>
                        </div>
                        <div class="bg-black/30 rounded-2xl p-6 text-center">
                            <div class="text-4xl font-bold text-bright-magenta mb-2">2</div>
                            <p class="text-gray-400">Get your API key from Dashboard</p>
                        </div>
                        <div class="bg-black/30 rounded-2xl p-6 text-center">
                            <div class="text-4xl font-bold text-electric-blue mb-2">3</div>
                            <p class="text-gray-400">Start shortening links via API</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Base URL -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-electric-blue">link</span>
                    Base URL
                </h2>
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 font-mono">
                    <span class="text-gray-500 select-none">Base URL: </span>
                    <span class="text-electric-blue">https://mrshort.io/api</span>
                </div>
            </div>

            <!-- Authentication -->
            <div class="mb-12 bg-white/5 border border-white/10 rounded-3xl p-8 md:p-12">
                <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-electric-blue">key</span>
                    Authentication
                </h2>
                <p class="text-gray-400 leading-relaxed mb-6">
                    All API requests require authentication using an API token. Include your token in the request header:
                </p>
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 overflow-x-auto">
                    <pre class="text-sm"><code class="language-http">Authorization: Bearer YOUR_API_TOKEN
Content-Type: application/json
Accept: application/json</code></pre>
                </div>
                <div class="mt-6 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl flex items-start gap-3">
                    <span class="material-symbols-outlined text-yellow-500">warning</span>
                    <p class="text-yellow-200 text-sm">Keep your API token secret. Never share it publicly or commit it to version control.</p>
                </div>
            </div>

            <!-- Endpoints -->
            <div class="space-y-12">
                
                <!-- Shorten Link Endpoint -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-lg">POST</span>
                        <h3 class="text-2xl font-bold text-white">/shorten</h3>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-6">
                        Create a new shortened URL. The shortened link will be monetized and you'll earn revenue for each valid click.
                    </p>

                    <h4 class="text-lg font-bold text-white mb-4">Request Body</h4>
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-800">
                                    <th class="py-3 px-4 text-gray-400 font-medium">Parameter</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Type</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Required</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-800/50">
                                    <td class="py-3 px-4 text-electric-blue font-mono">url</td>
                                    <td class="py-3 px-4 text-gray-400">string</td>
                                    <td class="py-3 px-4"><span class="text-green-400">Yes</span></td>
                                    <td class="py-3 px-4 text-gray-400">The original URL to shorten (must be a valid URL)</td>
                                </tr>
                                <tr class="border-b border-gray-800/50">
                                    <td class="py-3 px-4 text-electric-blue font-mono">alias</td>
                                    <td class="py-3 px-4 text-gray-400">string</td>
                                    <td class="py-3 px-4"><span class="text-gray-500">No</span></td>
                                    <td class="py-3 px-4 text-gray-400">Custom alias for the short link (alphanumeric, 3-20 chars)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="text-lg font-bold text-white mb-4">Example Request</h4>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 overflow-x-auto mb-6">
                        <pre class="text-sm"><code class="language-bash">curl -X POST https://mrshort.io/api/shorten \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://example.com/very-long-url-that-needs-shortening"
  }'</code></pre>
                    </div>

                    <h4 class="text-lg font-bold text-white mb-4">Success Response</h4>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 overflow-x-auto">
                        <pre class="text-sm"><code class="language-json">{
  "success": true,
  "short_link": "https://mrshort.io/abc123",
  "code": "abc123",
  "original_url": "https://example.com/very-long-url-that-needs-shortening"
}</code></pre>
                    </div>
                </div>

                <!-- Get Link Stats Endpoint -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="px-3 py-1 bg-blue-500 text-white text-sm font-bold rounded-lg">GET</span>
                        <h3 class="text-2xl font-bold text-white">/stats/{code}</h3>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-6">
                        Retrieve statistics for a specific shortened link including click counts, geographic data, and earnings.
                    </p>

                    <h4 class="text-lg font-bold text-white mb-4">Path Parameters</h4>
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-800">
                                    <th class="py-3 px-4 text-gray-400 font-medium">Parameter</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Type</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-800/50">
                                    <td class="py-3 px-4 text-electric-blue font-mono">code</td>
                                    <td class="py-3 px-4 text-gray-400">string</td>
                                    <td class="py-3 px-4 text-gray-400">The short code of the link</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="text-lg font-bold text-white mb-4">Example Request</h4>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 overflow-x-auto mb-6">
                        <pre class="text-sm"><code class="language-bash">curl -X GET https://mrshort.io/api/stats/abc123 \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"</code></pre>
                    </div>

                    <h4 class="text-lg font-bold text-white mb-4">Success Response</h4>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 overflow-x-auto">
                        <pre class="text-sm"><code class="language-json">{
  "success": true,
  "data": {
    "code": "abc123",
    "original_url": "https://example.com/...",
    "total_clicks": 1542,
    "unique_clicks": 1203,
    "earnings": 12.45,
    "created_at": "2024-12-01T10:30:00Z",
    "countries": {
      "US": 523,
      "UK": 234,
      "DE": 156
    },
    "devices": {
      "Desktop": 892,
      "Mobile": 587,
      "Tablet": 63
    }
  }
}</code></pre>
                    </div>
                </div>

                <!-- Get All Links Endpoint -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="px-3 py-1 bg-blue-500 text-white text-sm font-bold rounded-lg">GET</span>
                        <h3 class="text-2xl font-bold text-white">/links</h3>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-6">
                        Retrieve a paginated list of all your shortened links.
                    </p>

                    <h4 class="text-lg font-bold text-white mb-4">Query Parameters</h4>
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-800">
                                    <th class="py-3 px-4 text-gray-400 font-medium">Parameter</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Type</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Default</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-800/50">
                                    <td class="py-3 px-4 text-electric-blue font-mono">page</td>
                                    <td class="py-3 px-4 text-gray-400">integer</td>
                                    <td class="py-3 px-4 text-gray-500">1</td>
                                    <td class="py-3 px-4 text-gray-400">Page number for pagination</td>
                                </tr>
                                <tr class="border-b border-gray-800/50">
                                    <td class="py-3 px-4 text-electric-blue font-mono">per_page</td>
                                    <td class="py-3 px-4 text-gray-400">integer</td>
                                    <td class="py-3 px-4 text-gray-500">20</td>
                                    <td class="py-3 px-4 text-gray-400">Number of results per page (max: 100)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="text-lg font-bold text-white mb-4">Example Request</h4>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 overflow-x-auto">
                        <pre class="text-sm"><code class="language-bash">curl -X GET "https://mrshort.io/api/links?page=1&per_page=20" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"</code></pre>
                    </div>
                </div>

            </div>

            <!-- Error Codes -->
            <div class="mt-16 bg-white/5 border border-white/10 rounded-3xl p-8 md:p-12">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-bright-magenta">error</span>
                    Error Codes
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-800">
                                <th class="py-3 px-4 text-gray-400 font-medium">Code</th>
                                <th class="py-3 px-4 text-gray-400 font-medium">Status</th>
                                <th class="py-3 px-4 text-gray-400 font-medium">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-800/50">
                                <td class="py-3 px-4 text-yellow-500 font-mono">400</td>
                                <td class="py-3 px-4 text-white">Bad Request</td>
                                <td class="py-3 px-4 text-gray-400">Invalid request parameters or malformed JSON</td>
                            </tr>
                            <tr class="border-b border-gray-800/50">
                                <td class="py-3 px-4 text-red-500 font-mono">401</td>
                                <td class="py-3 px-4 text-white">Unauthorized</td>
                                <td class="py-3 px-4 text-gray-400">Missing or invalid API token</td>
                            </tr>
                            <tr class="border-b border-gray-800/50">
                                <td class="py-3 px-4 text-red-500 font-mono">403</td>
                                <td class="py-3 px-4 text-white">Forbidden</td>
                                <td class="py-3 px-4 text-gray-400">Access denied to the requested resource</td>
                            </tr>
                            <tr class="border-b border-gray-800/50">
                                <td class="py-3 px-4 text-yellow-500 font-mono">404</td>
                                <td class="py-3 px-4 text-white">Not Found</td>
                                <td class="py-3 px-4 text-gray-400">The requested resource was not found</td>
                            </tr>
                            <tr class="border-b border-gray-800/50">
                                <td class="py-3 px-4 text-orange-500 font-mono">422</td>
                                <td class="py-3 px-4 text-white">Validation Error</td>
                                <td class="py-3 px-4 text-gray-400">URL validation failed or invalid input</td>
                            </tr>
                            <tr class="border-b border-gray-800/50">
                                <td class="py-3 px-4 text-red-500 font-mono">429</td>
                                <td class="py-3 px-4 text-white">Too Many Requests</td>
                                <td class="py-3 px-4 text-gray-400">Rate limit exceeded</td>
                            </tr>
                            <tr class="border-b border-gray-800/50">
                                <td class="py-3 px-4 text-red-500 font-mono">500</td>
                                <td class="py-3 px-4 text-white">Server Error</td>
                                <td class="py-3 px-4 text-gray-400">Internal server error</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rate Limits -->
            <div class="mt-12 bg-white/5 border border-white/10 rounded-3xl p-8 md:p-12">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-electric-blue">speed</span>
                    Rate Limits
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gray-900/50 rounded-2xl p-6 border border-gray-800">
                        <h3 class="text-xl font-bold text-electric-blue mb-3">Standard Plan</h3>
                        <p class="text-gray-400">60 requests per minute</p>
                        <p class="text-gray-400">1000 requests per day</p>
                    </div>
                    <div class="bg-gray-900/50 rounded-2xl p-6 border border-gray-800">
                        <h3 class="text-xl font-bold text-bright-magenta mb-3">Premium Plan</h3>
                        <p class="text-gray-400">300 requests per minute</p>
                        <p class="text-gray-400">Unlimited daily requests</p>
                    </div>
                </div>
                <p class="text-gray-500 text-sm mt-6">Rate limit information is included in the response headers: X-RateLimit-Limit, X-RateLimit-Remaining, X-RateLimit-Reset</p>
            </div>

            <!-- Support -->
            <div class="mt-12 text-center">
                <p class="text-gray-400 mb-4">Need help with the API?</p>
                <a href="mailto:api-support@mrshort.io" class="inline-flex items-center gap-2 text-electric-blue hover:text-white transition-colors">
                    <span class="material-symbols-outlined">mail</span>
                    Contact API Support
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            hljs.highlightAll();
        });
    </script>
</body>
</html>
