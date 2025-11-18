<!DOCTYPE html>
<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Dashboard - Link Shortener</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
<div class="flex h-screen">
<aside class="w-64 bg-card-light dark:bg-card-dark flex flex-col p-4 border-r border-border-light dark:border-border-dark">
<div class="flex items-center gap-2 px-4 py-2 mb-8">
<span class="material-symbols-outlined text-primary text-3xl">link</span>
<h1 class="text-2xl font-bold text-heading-light dark:text-heading-dark">Linkly</h1>
</div>
<nav class="flex-grow">
<ul>
<li class="mb-2">
<a class="flex items-center gap-3 px-4 py-2 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-primary font-semibold" href="#">
<span class="material-symbols-outlined">dashboard</span>
                        Dashboard
                    </a>
</li>
<li class="mb-2">
<a class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark" href="#">
<span class="material-symbols-outlined">link</span>
                        Links
                    </a>
</li>
<li class="mb-2">
<a class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark" href="#">
<span class="material-symbols-outlined">payments</span>
                        Withdrawals
                    </a>
</li>
<li class="mb-2">
<a class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark" href="#">
<span class="material-symbols-outlined">construction</span>
                        Tools
                    </a>
</li>
<li class="mb-2">
<a class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark" href="#">
<span class="material-symbols-outlined">campaign</span>
                        Campaigns
                    </a>
</li>
<li class="mb-2">
<a class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark" href="#">
<span class="material-symbols-outlined">group</span>
                        Referrals
                    </a>
</li>
<li class="mb-2">
<a class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark" href="#">
<span class="material-symbols-outlined">contact_support</span>
                        Contact Us
                    </a>
</li>
</ul>
</nav>
<div>
<a class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark" href="#">
<span class="material-symbols-outlined">settings</span>
                Settings
            </a>
<a class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark" href="#">
<span class="material-symbols-outlined">logout</span>
                Logout
            </a>
</div>
</aside>
<main class="flex-1 p-8 overflow-y-auto">
<header class="flex justify-between items-center mb-8">
<div>
<h2 class="text-3xl font-bold text-heading-light dark:text-heading-dark">Dashboard Overview</h2>
<p class="text-text-light dark:text-text-dark">Welcome back, let's see your progress!</p>
</div>
<div class="flex items-center gap-4">
<button class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
<span class="material-symbols-outlined text-text-light dark:text-text-dark">notifications</span>
</button>
<div class="flex items-center gap-3">
<img alt="User avatar" class="w-10 h-10 rounded-full" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBYz4wvulK3vVsIwBRqELm_CC4lkTGI9tXg50Ro5Cn8brLG8usCafMaPjV5Z2rR7uml03pbtZ_NKea1Vwmgd1bBR9asqgPIBJOcNlr8e0v54TRNIAvbBiumwMfhuFO9UA0V54rYA8hL1WhbVmzD0vM6M6nIwE5V9vhnaGH9d6tB7s8EuvpliLYMpxzS2mj38QDSvT0EQ8etWxTxLEdo2dvn84ztoGzoDDKtBeIi_fgbhSQyqWATv49l-i7_p62JeVlCUG83BpvbUvSj"/>
<div>
<p class="font-semibold text-heading-light dark:text-heading-dark">Admin User</p>
<p class="text-sm text-text-light dark:text-text-dark">Balance: $0.00300</p>
</div>
</div>
</div>
</header>

    @if (session('status'))
        <div class="items-center gap-3 rounded-lg border border-primary/50 bg-primary/10 p-3 text-sm text-primary dark:text-blue-400 mb-6" style="display: flex;">
            <span class="material-symbols-outlined">check_circle</span>
            <p>{{ session('status') }}</p>
        </div>
    @endif

    {{-- Güvenlik Önerisi Banner'ı --}}
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
        <p>It is very recommended to enable 2 Factor Authentication on your security settings to ensure the security of your account</p>
    </div>

    {{-- Reklam Banner'ı --}}
    <div class="bg-gray-200 p-4 mb-6 text-center">
        <p>Reklam Alanı</p>
        {{-- Buraya reklam kodu gelecek --}}
    </div>

<div class="bg-card-light dark:bg-card-dark p-6 rounded-lg mb-8 shadow-md">
<div class="flex items-center gap-4">
<input class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent text-heading-light dark:text-heading-dark placeholder:text-text-light dark:placeholder:text-text-dark" placeholder="Paste your long URL here" type="text"/>
<button class="bg-primary text-white font-semibold px-6 py-3 rounded-lg flex items-center gap-2 whitespace-nowrap hover:bg-blue-600 transition-colors">
<span class="material-symbols-outlined">content_cut</span>
                    Shrink Now
                </button>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <livewire:user.dashboard-stats />
</div>
<div class="grid grid-cols-1 gap-8 mb-8">
<div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
<div class="flex flex-wrap justify-between items-center mb-6 gap-4">
<h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark">Statistics Visualization</h3>
<div class="flex items-center bg-background-light dark:bg-background-dark p-1 rounded-lg">
    <livewire:user.stats-date-filter />
</div>
</div>
<div class="h-[400px]" id="chart">
    <livewire:user.earnings-chart />
</div>
</div>
<div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
<h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Daily Statistics</h3>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
    {{-- Daily statistics will be rendered by earnings-chart livewire component --}}
</div>
</div>
</div>
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
<div class="xl:col-span-1 bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
<h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Earnings Goal</h3>
<div class="text-center">
<div class="relative inline-flex items-center justify-center">
<svg class="w-32 h-32" viewBox="0 0 120 120">
<circle class="stroke-current text-gray-200 dark:text-gray-700" cx="60" cy="60" fill="transparent" r="54" stroke-width="12"></circle>
<circle class="stroke-current text-primary -rotate-90 origin-center" cx="60" cy="60" fill="transparent" r="54" stroke-dasharray="339.292" stroke-dashoffset="84.823" stroke-linecap="round" stroke-width="12" style="stroke-dashoffset: 84.823;"></circle>
</svg>
<div class="absolute flex flex-col items-center">
<span class="text-3xl font-bold text-heading-light dark:text-heading-dark">75%</span>
</div>
</div>
<p class="text-lg font-medium text-heading-light dark:text-heading-dark mt-4">$75.00 / $100.00</p>
<p class="text-sm text-text-light dark:text-text-dark">of your monthly goal.</p>
<button class="mt-4 bg-primary/10 text-primary font-semibold px-4 py-2 rounded-lg hover:bg-primary/20 transition-colors">Set New Goal</button>
</div>
</div>
<div class="xl:col-span-2 bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
<h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Top Viewed Countries</h3>
<div class="space-y-4">
<div class="flex items-center gap-4">
<div class="flex-grow">
<div class="flex justify-between items-center mb-1">
<span class="text-heading-light dark:text-heading-dark font-medium">United States</span>
<span class="font-bold text-sm text-heading-light dark:text-heading-dark">79 Clicks</span>
</div>
<div class="w-full bg-background-light dark:bg-background-dark rounded-full h-1.5">
<div class="bg-primary h-1.5 rounded-full" style="width: 40%"></div>
</div>
</div>
</div>
<div class="flex items-center gap-4">
<div class="flex-grow">
<div class="flex justify-between items-center mb-1">
<span class="text-heading-light dark:text-heading-dark font-medium">United Kingdom</span>
<span class="font-bold text-sm text-heading-light dark:text-heading-dark">45 Clicks</span>
</div>
<div class="w-full bg-background-light dark:bg-background-dark rounded-full h-1.5">
<div class="bg-primary h-1.5 rounded-full" style="width: 23%"></div>
</div>
</div>
</div>
<div class="flex items-center gap-4">
<div class="flex-grow">
<div class="flex justify-between items-center mb-1">
<span class="text-heading-light dark:text-heading-dark font-medium">Canada</span>
<span class="font-bold text-sm text-heading-light dark:text-heading-dark">21 Clicks</span>
</div>
<div class="w-full bg-background-light dark:bg-background-dark rounded-full h-1.5">
<div class="bg-primary h-1.5 rounded-full" style="width: 11%"></div>
</div>
</div>
</div>
<div class="flex items-center gap-4">
<div class="flex-grow">
<div class="flex justify-between items-center mb-1">
<span class="text-heading-light dark:text-heading-dark font-medium">Germany</span>
<span class="font-bold text-sm text-heading-light dark:text-heading-dark">18 Clicks</span>
</div>
<div class="w-full bg-background-light dark:bg-background-dark rounded-full h-1.5">
<div class="bg-primary h-1.5 rounded-full" style="width: 9%"></div>
</div>
</div>
</div>
<div class="flex items-center gap-4">
<div class="flex-grow">
<div class="flex justify-between items-center mb-1">
<span class="text-heading-light dark:text-heading-dark font-medium">Australia</span>
<span class="font-bold text-sm text-heading-light dark:text-heading-dark">15 Clicks</span>
</div>
<div class="w-full bg-background-light dark:bg-background-dark rounded-full h-1.5">
<div class="bg-primary h-1.5 rounded-full" style="width: 7%"></div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md mb-8">
<h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Optimized Link Suggestions</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="flex items-start gap-4 p-4 bg-background-light dark:bg-background-dark rounded-lg">
<div class="mt-1 p-2 bg-green-100 dark:bg-green-900/50 rounded-full"><span class="material-symbols-outlined text-green-500 text-base">trending_up</span></div>
<div>
<h4 class="font-semibold text-heading-light dark:text-heading-dark">High Traffic Potential</h4>
<p class="text-sm text-text-light dark:text-text-dark">Your link for "Tech Gadgets 2024" is performing well. Consider creating more content around this topic.</p>
</div>
</div>
<div class="flex items-start gap-4 p-4 bg-background-light dark:bg-background-dark rounded-lg">
<div class="mt-1 p-2 bg-blue-100 dark:bg-blue-900/50 rounded-full"><span class="material-symbols-outlined text-primary text-base">public</span></div>
<div>
<h4 class="font-semibold text-heading-light dark:text-heading-dark">Geo-Targeting Tip</h4>
<p class="text-sm text-text-light dark:text-text-dark">High CPM in Germany. Try sharing your links in German-speaking forums for higher earnings.</p>
</div>
</div>
</div>
</div>
<div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md mb-8">
<h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Link Manager</h3>
<div class="flex flex-wrap items-center justify-between gap-4 mb-4">
<div class="relative flex-grow sm:flex-grow-0 sm:w-72">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light dark:text-text-dark">search</span>
<input class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent text-heading-light dark:text-heading-dark placeholder:text-text-light dark:placeholder:text-text-dark" placeholder="Search links..." type="text"/>
</div>
<div class="flex items-center gap-4">
<select class="bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent text-heading-light dark:text-heading-dark">
<option>Filter by Status</option>
<option>Active</option>
<option>Archived</option>
</select>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="border-b border-border-light dark:border-border-dark">
<tr>
<th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Short Link</th>
<th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Original Link</th>
<th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Clicks</th>
<th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Status</th>
<th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark">Date</th>
<th class="p-3 text-sm font-semibold uppercase text-text-light dark:text-text-dark text-right">Actions</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="p-3 text-primary font-semibold">linkly.to/xY2zAb</td>
<td class="p-3 text-heading-light dark:text-heading-dark truncate max-w-xs">https://example.com/very-long-url-that-needs-to-be-shortened</td>
<td class="p-3 text-heading-light dark:text-heading-dark">1,204</td>
<td class="p-3"><span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">Active</span></td>
<td class="p-3 text-heading-light dark:text-heading-dark">Aug 12, 2023</td>
<td class="p-3">
<div class="flex justify-end items-center gap-2">
<button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm">edit</span></button>
<button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm">bar_chart</span></button>
<button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm text-red-500">delete</span></button>
</div>
</td>
</tr>
<tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="p-3 text-primary font-semibold">linkly.to/aB3cDe</td>
<td class="p-3 text-heading-light dark:text-heading-dark truncate max-w-xs">https://another-example.com/another-long-url-for-shortening</td>
<td class="p-3 text-heading-light dark:text-heading-dark">856</td>
<td class="p-3"><span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">Active</span></td>
<td class="p-3 text-heading-light dark:text-heading-dark">Aug 11, 2023</td>
<td class="p-3">
<div class="flex justify-end items-center gap-2">
<button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm">edit</span></button>
<button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm">bar_chart</span></button>
<button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm text-red-500">delete</span></button>
</div>
</td>
</tr>
<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="p-3 text-primary font-semibold">linkly.to/fG4hIj</td>
<td class="p-3 text-heading-light dark:text-heading-dark truncate max-w-xs">https://archived-example.com/old-link-to-be-archived</td>
<td class="p-3 text-heading-light dark:text-heading-dark">512</td>
<td class="p-3"><span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">Archived</span></td>
<td class="p-3 text-heading-light dark:text-heading-dark">Jul 25, 2023</td>
<td class="p-3">
<div class="flex justify-end items-center gap-2">
<button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm">edit</span></button>
<button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm">bar_chart</span></button>
<button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"><span class="material-symbols-outlined text-sm text-red-500">delete</span></button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
<div class="lg:col-span-2 bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
<h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Payment Information Summary</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<p class="text-sm text-text-light dark:text-text-dark mb-1">Current Balance</p>
<p class="text-3xl font-bold text-green-500">$36.75</p>
<p class="text-sm text-text-light dark:text-text-dark mt-2">Available for withdrawal.</p>
</div>
<div>
<p class="text-sm text-text-light dark:text-text-dark mb-1">Payment Threshold</p>
<p class="text-2xl font-bold text-heading-light dark:text-heading-dark">$50.00</p>
<div class="w-full bg-background-light dark:bg-background-dark rounded-full h-2.5 mt-2">
<div class="bg-primary h-2.5 rounded-full" style="width: 73.5%"></div>
</div>
</div>
<div>
<p class="text-sm text-text-light dark:text-text-dark mb-1">Last Payment Date</p>
<p class="text-lg font-semibold text-heading-light dark:text-heading-dark">July 1, 2023</p>
</div>
<div>
<p class="text-sm text-text-light dark:text-text-dark mb-1">Next Payment Date</p>
<p class="text-lg font-semibold text-heading-light dark:text-heading-dark">September 1, 2023</p>
</div>
</div>
</div>
<div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
<h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Recent Activity Feed</h3>
<ul class="space-y-4">
    <livewire:user.announcements />
</ul>
</div>
</div>
</main>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
      const isDarkMode = document.documentElement.classList.contains('dark');
      var options = {
        chart: {
          type: 'area',
          height: '100%',
          toolbar: {
            show: false
          },
          zoom: {
              enabled: false
          }
        },
        series: [{
          name: 'Views',
          data: [1247, 1502, 980, 1100, 1380, 1200, 1450]
        }, {
          name: 'Earnings',
          data: [1.25, 1.58, 0.93, 1.15, 1.42, 1.22, 1.50]
        }],
        xaxis: {
          categories: ['Aug 12', 'Aug 11', 'Aug 10', 'Aug 9', 'Aug 8', 'Aug 7', 'Aug 6'],
          labels: {
            style: {
              colors: isDarkMode ? '#94a3b8' : '#64748b'
            }
          },
          axisBorder: {
              show: false,
          },
          axisTicks: {
              show: false,
          }
        },
        yaxis: [{
          title: {
            text: 'Views',
            style: {
                color: isDarkMode ? '#94a3b8' : '#64748b'
            }
          },
          labels: {
            style: {
              colors: isDarkMode ? '#94a3b8' : '#64748b'
            }
          }
        },
        {
          opposite: true,
          title: {
            text: 'Earnings ($)',
            style: {
                color: isDarkMode ? '#94a3b8' : '#64748b'
            }
          },
           labels: {
            style: {
              colors: isDarkMode ? '#94a3b8' : '#64748b'
            }
          }
        }],
        colors: ['#3b82f6', '#10b981'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 2
        },
        grid: {
          borderColor: isDarkMode ? '#334155' : '#e2e8f0',
          strokeDashArray: 5
        },
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        tooltip: {
            theme: isDarkMode ? 'dark' : 'light',
        },
        legend: {
            labels: {
                colors: isDarkMode ? '#ffffff' : '#0f172a'
            }
        }
      }
      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();
    });
    </script>
</body></html>
