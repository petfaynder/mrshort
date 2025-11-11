{{-- Gerekli Stiller ve Scriptler --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            primary: "#3b82f6", // Blue accent color
            "background-light": "#f4f7f9",
            "background-dark": "#0f172a", // Dark navy background
            "surface-light": "#ffffff",
            "surface-dark": "#1e293b", // Slightly lighter navy for cards/sidebar
            "text-light-primary": "#111827",
            "text-dark-primary": "#f8fafc",
            "text-light-secondary": "#6b7280",
            "text-dark-secondary": "#94a3b8",
            "border-light": "#e5e7eb",
            "border-dark": "#334155",
          },
          fontFamily: {
            display: ["Poppins", "sans-serif"],
          },
          borderRadius: {
            DEFAULT: "0.5rem", // 8px
            lg: "1rem", // 16px
          },
        },
      },
    };
</script>
<style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    .material-icons-outlined {
        font-size: 20px;
    }
</style>

{{-- Sayfa İçeriği --}}
<div class="bg-surface-light dark:bg-surface-dark p-6 md:p-8 rounded-lg shadow-sm font-display">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h2 class="text-2xl font-semibold text-text-light-primary dark:text-text-dark-primary mb-4 sm:mb-0">Campaigns</h2>
        <a href="{{ route('user.ads.create') }}" class="bg-primary text-white px-5 py-2.5 rounded-md font-semibold text-sm flex items-center gap-2 hover:bg-blue-600 transition-colors duration-200">
            <span class="material-icons-outlined" style="font-size: 18px;">add</span>
            {{ __('Create New Campaign') }}
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="relative flex-grow">
            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light-secondary dark:text-text-dark-secondary">search</span>
            <input class="w-full pl-10 pr-4 py-2.5 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md text-sm placeholder:text-text-light-secondary placeholder:dark:text-text-dark-secondary focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Search by name or type..." type="text"/>
        </div>
        <div class="flex gap-2 flex-wrap">
            <select class="bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md text-sm text-text-light-secondary dark:text-text-dark-primary focus:ring-2 focus:ring-primary focus:border-primary py-2.5">
                <option>Status: All</option>
                <option>Active</option>
                <option>Paused</option>
                <option>Completed</option>
            </select>
            <select class="bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md text-sm text-text-light-secondary dark:text-text-dark-primary focus:ring-2 focus:ring-primary focus:border-primary py-2.5">
                <option>Type: All</option>
                <option>Link Campaign</option>
                <option>Banner Campaign</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-border-light dark:border-border-dark">
                <tr>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light-secondary dark:text-text-dark-secondary tracking-wider">{{ __('Campaign Name') }}</th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light-secondary dark:text-text-dark-secondary tracking-wider">{{ __('Type') }}</th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light-secondary dark:text-text-dark-secondary tracking-wider">{{ __('Active') }}</th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light-secondary dark:text-text-dark-secondary tracking-wider">{{ __('Impressions') }}</th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light-secondary dark:text-text-dark-secondary tracking-wider">{{ __('Clicks') }}</th>
                    <th class="p-4 text-xs font-semibold uppercase text-text-light-secondary dark:text-text-dark-secondary tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="text-text-light-primary dark:text-text-dark-primary">
                @forelse ($adCampaigns as $campaign)
                    <tr class="border-b border-border-light dark:border-border-dark">
                        <td class="p-4 whitespace-nowrap text-sm font-medium">
                            {{ $campaign->name }}
                        </td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light-secondary dark:text-text-dark-secondary">
                            {{ $campaign->campaign_type->value }}
                        </td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light-secondary dark:text-text-dark-secondary">
                            @if ($campaign->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ __('Yes') }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ __('No') }}
                                </span>
                            @endif
                        </td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light-secondary dark:text-text-dark-secondary">
                            {{ $campaign->total_impressions }}
                        </td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light-secondary dark:text-text-dark-secondary">
                            {{ $campaign->total_clicks }}
                        </td>
                        <td class="p-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('user.ads.edit', $campaign) }}" class="text-primary hover:underline mr-3">{{ __('Edit') }}</a>
                            <button wire:click="deleteCampaign({{ $campaign->id }})" onclick="confirm('Are you sure you want to delete this campaign?') || event.stopImmediatePropagation()" class="text-red-600 hover:underline">{{ __('Delete') }}</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center py-20 px-4" colspan="6">
                            <p class="text-text-light-secondary dark:text-text-dark-secondary">{{ __("You don't have any active campaigns yet.") }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
