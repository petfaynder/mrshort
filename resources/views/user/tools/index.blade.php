<x-user-dashboard-layout>
    <x-slot name="header">
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-heading-light dark:text-heading-dark">Developer Tools & Integrations</h1>
            </div>
            <div class="flex items-center gap-4 mt-4 sm:mt-0">
                <button class="text-text-light dark:text-subtext-dark hover:text-heading-light dark:hover:text-heading-dark">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="flex items-center gap-2">
                    <img alt="User avatar" class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=40&background=0D8ABC&color=fff"/>
                    <div>
                        <p class="text-sm font-semibold text-heading-light dark:text-heading-dark">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-text-light dark:text-subtext-dark">Balance: ${{ number_format((Auth::user()->link_earnings ?? 0) + (Auth::user()->referral_earnings ?? 0), 2) }}</p>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="space-y-8">
            <livewire:user.api-token-manager />

            <div class="bg-white dark:bg-white/10 p-6 rounded-lg">
                <h2 class="text-lg font-semibold text-heading-light dark:text-heading-dark mb-2">API Integration Documentation</h2>
                <div class="mb-4">
                    <p class="text-sm text-text-light dark:text-subtext-dark font-medium mb-2">What does this API do?</p>
                    <p class="text-sm text-text-light dark:text-subtext-dark mb-3">
                        Our API allows you to shorten URLs programmatically from your own applications, websites, or scripts. 
                        This is useful for automating link creation, integrating with social media bots, or building custom analytics dashboards.
                    </p>
                    <p class="text-sm text-text-light dark:text-subtext-dark font-medium mb-2">Integration Steps:</p>
                    <ol class="list-decimal list-inside text-sm text-text-light dark:text-subtext-dark space-y-1">
                        <li>Create an <strong>API Token</strong> using the form above.</li>
                        <li>Include this token in the <code>Authorization</code> header as <code>Bearer {YOUR_TOKEN}</code>.</li>
                        <li>Send a <code>POST</code> request to the endpoint below with the <code>url</code> parameter.</li>
                    </ol>
                </div>
                <div class="bg-gray-100 dark:bg-[#313346] rounded-md p-4">
                    <p class="text-sm font-mono text-gray-700 dark:text-gray-300">POST <span class="text-blue-500 dark:text-blue-400">{{ route('api.shorten') }}</span></p>
                </div>
                <div class="mt-4" x-data="{ tab: 'curl' }">
                    <div class="flex border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                        <button @click="tab = 'curl'" :class="{ 'border-b-2 border-blue-500 text-blue-600 dark:text-white': tab === 'curl', 'text-text-light dark:text-subtext-dark hover:text-heading-light dark:hover:text-white': tab !== 'curl' }" class="px-4 py-2 text-sm font-medium flex-shrink-0 transition-colors">cURL</button>
                        <button @click="tab = 'php'" :class="{ 'border-b-2 border-blue-500 text-blue-600 dark:text-white': tab === 'php', 'text-text-light dark:text-subtext-dark hover:text-heading-light dark:hover:text-white': tab !== 'php' }" class="px-4 py-2 text-sm font-medium flex-shrink-0 transition-colors">PHP</button>
                        <button @click="tab = 'node'" :class="{ 'border-b-2 border-blue-500 text-blue-600 dark:text-white': tab === 'node', 'text-text-light dark:text-subtext-dark hover:text-heading-light dark:hover:text-white': tab !== 'node' }" class="px-4 py-2 text-sm font-medium flex-shrink-0 transition-colors">Node.js</button>
                        <button @click="tab = 'python'" :class="{ 'border-b-2 border-blue-500 text-blue-600 dark:text-white': tab === 'python', 'text-text-light dark:text-subtext-dark hover:text-heading-light dark:hover:text-white': tab !== 'python' }" class="px-4 py-2 text-sm font-medium flex-shrink-0 transition-colors">Python</button>
                        <button @click="tab = 'ruby'" :class="{ 'border-b-2 border-blue-500 text-blue-600 dark:text-white': tab === 'ruby', 'text-text-light dark:text-subtext-dark hover:text-heading-light dark:hover:text-white': tab !== 'ruby' }" class="px-4 py-2 text-sm font-medium flex-shrink-0 transition-colors">Ruby</button>
                        <button @click="tab = 'java'" :class="{ 'border-b-2 border-blue-500 text-blue-600 dark:text-white': tab === 'java', 'text-text-light dark:text-subtext-dark hover:text-heading-light dark:hover:text-white': tab !== 'java' }" class="px-4 py-2 text-sm font-medium flex-shrink-0 transition-colors">Java</button>
                    </div>
                    <div class="bg-gray-900/5 dark:bg-black/30 p-4 rounded-b-md">
                        <div x-show="tab === 'curl'">
<pre class="text-sm text-gray-800 dark:text-gray-300 overflow-x-auto"><code class="language-bash">curl -X POST {{ route('api.shorten') }} \
-H "Authorization: Bearer {YOUR_API_TOKEN}" \
-H "Content-Type: application/json" \
-d '{"url": "https://example.com"}'</code></pre>
                        </div>
                        <div x-show="tab === 'php'">
<pre class="text-sm text-gray-800 dark:text-gray-300 overflow-x-auto"><code class="language-php">$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, '{{ route('api.shorten') }}');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['url' => 'https://example.com']));

$headers = [
    'Authorization: Bearer {YOUR_API_TOKEN}',
    'Content-Type: application/json'
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);</code></pre>
                        </div>
                        <div x-show="tab === 'node'">
<pre class="text-sm text-gray-800 dark:text-gray-300 overflow-x-auto"><code class="language-javascript">const axios = require('axios');

axios.post('{{ route('api.shorten') }}', {
    url: 'https://example.com'
}, {
    headers: {
        'Authorization': 'Bearer {YOUR_API_TOKEN}',
        'Content-Type': 'application/json'
    }
})
.then((response) => {
    console.log(response.data);
})
.catch((error) => {
    console.error(error);
});</code></pre>
                        </div>
                        <div x-show="tab === 'python'">
<pre class="text-sm text-gray-800 dark:text-gray-300 overflow-x-auto"><code class="language-python">import requests

url = "{{ route('api.shorten') }}"
payload = {"url": "https://example.com"}
headers = {
    "Authorization": "Bearer {YOUR_API_TOKEN}",
    "Content-Type": "application/json"
}

response = requests.post(url, json=payload, headers=headers)
print(response.json())</code></pre>
                        </div>
                        <div x-show="tab === 'ruby'">
<pre class="text-sm text-gray-800 dark:text-gray-300 overflow-x-auto"><code class="language-ruby">require 'uri'
require 'net/http'
require 'json'

url = URI("{{ route('api.shorten') }}")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = (url.scheme == "https")

request = Net::HTTP::Post.new(url)
request["Authorization"] = "Bearer {YOUR_API_TOKEN}"
request["Content-Type"] = "application/json"
request.body = JSON.dump({ "url": "https://example.com" })

response = http.request(request)
puts response.read_body</code></pre>
                        </div>
                        <div x-show="tab === 'java'">
<pre class="text-sm text-gray-800 dark:text-gray-300 overflow-x-auto"><code class="language-java">import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class Main {
    public static void main(String[] args) throws Exception {
        String url = "{{ route('api.shorten') }}";
        String payload = "{\"url\":\"https://example.com\"}";

        HttpClient client = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
            .uri(URI.create(url))
            .header("Authorization", "Bearer {YOUR_API_TOKEN}")
            .header("Content-Type", "application/json")
            .POST(HttpRequest.BodyPublishers.ofString(payload))
            .build();

        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());
        System.out.println(response.body());
    }
}</code></pre>
                        </div>
                    </div>
                </div>
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4"
                     x-data="{
                        url: '',
                        token: '',
                        response: null,
                        loading: false,
                        async sendRequest() {
                            if (!this.url || !this.token) {
                                alert('Please enter both URL and API Token');
                                return;
                            }
                            this.loading = true;
                            this.response = null;
                            try {
                                const res = await fetch('{{ route('api.shorten') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Authorization': 'Bearer ' + this.token,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ url: this.url })
                                });
                                const data = await res.json();
                                this.response = JSON.stringify(data, null, 2);
                            } catch (e) {
                                this.response = 'Error: ' + e.message;
                            } finally {
                                this.loading = false;
                            }
                        }
                     }">
                    <h3 class="text-md font-semibold text-heading-light dark:text-heading-dark mb-3">Interactive API Explorer</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-text-light dark:text-gray-300 mb-1" for="api-token">API Token</label>
                            <input x-model="token" class="w-full bg-gray-50 dark:bg-[#313346] border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md focus:ring-primary focus:border-primary placeholder-gray-400 text-sm" id="api-token" placeholder="Paste your API Token here" type="password"/>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-text-light dark:text-gray-300 mb-1" for="api-url">URL to shorten</label>
                            <input x-model="url" class="w-full bg-gray-50 dark:bg-[#313346] border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md focus:ring-primary focus:border-primary placeholder-gray-400 text-sm" id="api-url" placeholder="https://example.com" type="text"/>
                        </div>
                        <button @click="sendRequest" :disabled="loading" class="w-full bg-primary text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-600 transition-colors duration-300 disabled:opacity-50 flex justify-center items-center gap-2">
                            <span x-show="!loading">Send Request</span>
                            <span x-show="loading" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                            <span x-show="loading">Sending...</span>
                        </button>
                        <div class="bg-gray-900/5 dark:bg-black/30 p-4 rounded-md" x-show="response" style="display: none;">
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Response:</p>
                            <pre class="text-xs text-green-600 dark:text-green-400 overflow-x-auto" x-text="response"></pre>
                        </div>
                    </div>
                </div>
            </div>
            
            <livewire:user.mass-shortener />
        </div>
        <div class="space-y-8">
            <div class="bg-white dark:bg-white/10 p-6 rounded-lg">
                <h2 class="text-lg font-semibold text-heading-light dark:text-heading-dark mb-4">WordPress Plugin</h2>
                <p class="text-sm text-text-light dark:text-subtext-dark">Automatically shorten links with our plugin to make money from your WordPress site and get detailed statistics.</p>
                <ul class="list-disc list-inside text-sm text-text-light dark:text-subtext-dark space-y-1 my-4">
                    <li>Automatic link shortening</li>
                    <li>Category based filtering</li>
                    <li>Post type based filtering</li>
                </ul>
                <button class="w-full bg-gray-400 dark:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md cursor-not-allowed">Coming Soon</button>
                <p class="text-xs text-center text-text-light dark:text-subtext-dark mt-2">The plugin is under review. It will be live soon.</p>
            </div>

            <livewire:user.dead-link-checker />
        </div>
    </div>
</x-user-dashboard-layout>
