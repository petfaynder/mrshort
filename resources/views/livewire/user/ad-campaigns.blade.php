{{-- Required Styles and Scripts --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
<style>
    .font-poppins {
        font-family: 'Poppins', sans-serif;
    }
    .material-icons-outlined {
        font-size: 20px;
    }
</style>

{{-- Page Content --}}
<div class="bg-card-light dark:bg-card-dark p-6 md:p-8 rounded-lg shadow-sm font-poppins">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h2 class="text-2xl font-semibold text-heading-light dark:text-heading-dark mb-4 sm:mb-0">Campaigns</h2>
        <a href="{{ route('user.ads.create') }}" class="bg-primary text-white px-5 py-2.5 rounded-md font-semibold text-sm flex items-center gap-2 hover:bg-blue-600 transition-colors duration-200">
            <span class="material-icons-outlined" style="font-size: 18px;">add</span>
            {{ __('Create New Campaign') }}
        </a>
    </div>

    {{-- Flash Messages handled by toast at end of file --}}

    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="relative flex-grow">
            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light dark:text-text-dark">search</span>
            <input wire:model.live="search" class="w-full pl-10 pr-4 py-2.5 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md text-sm placeholder:text-text-light placeholder:dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary text-heading-light dark:text-heading-dark" placeholder="Search by name..." type="text"/>
        </div>
        <div class="flex gap-2 flex-wrap">
            <select wire:model.live="status" class="bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md text-sm text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary py-2.5">
                <option value="">Status: All</option>
                <option value="active">Active</option>
                <option value="paused">Paused</option>
            </select>
            <select wire:model.live="type" class="bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md text-sm text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary py-2.5">
                <option value="">Type: All</option>
                <option value="link">Link Campaign</option>
                <option value="banner">Banner Campaign</option>
            </select>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-border-light dark:border-border-dark">
            <tr>
                <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider">{{ __('Campaign Name') }}</th>
                <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider">{{ __('Type') }}</th>
                <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider">{{ __('Approval') }}</th>
                <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider">{{ __('Active') }}</th>
                <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider">{{ __('Impressions') }}</th>
                <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider">{{ __('Clicks') }}</th>
                <th class="p-4 text-xs font-semibold uppercase text-text-light dark:text-text-dark tracking-wider text-right">{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody class="text-heading-light dark:text-heading-dark">
                @forelse ($adCampaigns as $campaign)
                    <tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="p-4 whitespace-nowrap text-sm font-medium">{{ $campaign->name }}</td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">{{ $campaign->campaign_type->value }}</td>
                        <td class="p-4 text-sm">
                            @php $status = $campaign->approval_status ?? 'pending'; @endphp
                            @if ($status === 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('Approved') }}</span>
                            @elseif ($status === 'rejected')
                                <div class="flex items-center gap-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ __('Rejected') }}</span>
                                    @if ($campaign->rejection_reason)
                                        <span class="material-icons-outlined text-red-500 cursor-help" style="font-size: 16px;" title="{{ $campaign->rejection_reason }}">info</span>
                                    @endif
                                </div>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ __('Pending') }}</span>
                            @endif
                        </td>
                        <td class="p-4 whitespace-nowrap text-sm">
                            @if ($campaign->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('Yes') }}</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ __('No') }}</span>
                            @endif
                        </td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">{{ $campaign->total_impressions }}</td>
                        <td class="p-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">{{ $campaign->total_clicks }}</td>
                        <td class="p-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('user.ads.edit', $campaign) }}" class="text-primary hover:underline mr-3">{{ __('Edit') }}</a>
                            <button type="button" x-data
                                @click="$dispatch('open-confirm-modal', { id: 'delete-campaign-{{ $campaign->id }}', onConfirm: () => $wire.deleteCampaign({{ $campaign->id }}) })"
                                wire:loading.attr="disabled" class="text-red-600 hover:underline disabled:opacity-50">
                                <span wire:loading.remove wire:target="deleteCampaign({{ $campaign->id }})">{{ __('Delete') }}</span>
                                <span wire:loading wire:target="deleteCampaign({{ $campaign->id }})" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                            </button>
                            <x-confirm-modal id="delete-campaign-{{ $campaign->id }}" title="Delete Campaign" message="Are you sure you want to delete this campaign?" confirmText="Delete" cancelText="Cancel" confirmColor="red" icon="delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center py-12 px-4" colspan="7">
                            <p class="text-text-light dark:text-text-dark">{{ __("You don't have any active campaigns yet.") }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card Layout --}}
    <div class="md:hidden space-y-3">
        @forelse ($adCampaigns as $campaign)
            @php $status = $campaign->approval_status ?? 'pending'; @endphp
            <div class="border border-border-light dark:border-border-dark rounded-lg p-4">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div>
                        <p class="font-semibold text-sm text-heading-light dark:text-heading-dark">{{ $campaign->name }}</p>
                        <p class="text-xs text-text-light dark:text-text-dark mt-0.5">{{ $campaign->campaign_type->value }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        @if ($status === 'approved')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                        @elseif ($status === 'rejected')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                        @if ($campaign->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs text-text-light dark:text-text-dark mb-3">
                    <div><span class="font-medium">Impressions:</span> {{ $campaign->total_impressions }}</div>
                    <div><span class="font-medium">Clicks:</span> {{ $campaign->total_clicks }}</div>
                </div>
                <div class="flex items-center gap-3 pt-3 border-t border-border-light dark:border-border-dark">
                    <a href="{{ route('user.ads.edit', $campaign) }}" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">edit</span> Edit
                    </a>
                    <button type="button" x-data
                        @click="$dispatch('open-confirm-modal', { id: 'delete-campaign-mob-{{ $campaign->id }}', onConfirm: () => $wire.deleteCampaign({{ $campaign->id }}) })"
                        class="text-xs font-semibold text-red-600 dark:text-red-500 hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">delete</span> Delete
                    </button>
                    <x-confirm-modal id="delete-campaign-mob-{{ $campaign->id }}" title="Delete Campaign" message="Are you sure you want to delete this campaign?" confirmText="Delete" cancelText="Cancel" confirmColor="red" icon="delete" />
                </div>
            </div>
        @empty
            <div class="border border-border-light dark:border-border-dark rounded-lg p-8 text-center">
                <p class="text-sm text-text-light dark:text-text-dark">{{ __("You don't have any active campaigns yet.") }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $adCampaigns->links() }}
    </div>
</div>

{{-- Toast Notifications --}}
@if (session()->has('success'))
    <x-toast-notification type="success">{{ session('success') }}</x-toast-notification>
@endif
@if (session()->has('error'))
    <x-toast-notification type="error">{{ session('error') }}</x-toast-notification>
@endif
