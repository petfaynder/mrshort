<div x-data="{ 
        activeAccordion: null, 
        showSuccessModal: false,
        isModalOpen: false
    }"
    @ticket-created.window="
        showSuccessModal = true;
        setTimeout(() => showSuccessModal = false, 4000);
    "
    @open-ticket-modal.window="
        isModalOpen = true;
        document.body.style.overflow = 'hidden';
    "
    x-init="
        $watch('isModalOpen', value => {
            if (!value) {
                document.body.style.overflow = 'auto';
            }
        });
    ">

    {{-- Session Messages (for other actions like reply, close etc.) --}}
    @if (session()->has('message'))
        <div class="flex items-center gap-3 bg-green-900/50 border border-green-800 text-green-300 text-sm rounded-lg p-4 mb-8">
            <span class="material-symbols-outlined flex-shrink-0">check_circle</span>
            <div>
                <span class="font-semibold">Başarılı:</span> {{ session('message') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="flex items-center gap-3 bg-red-900/50 border border-red-800 text-red-300 text-sm rounded-lg p-4 mb-8">
            <span class="material-symbols-outlined flex-shrink-0">error</span>
            <div>
                <span class="font-semibold">Hata:</span> {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="flex items-center gap-3 bg-blue-900/50 border border-blue-800 text-blue-300 text-sm rounded-lg p-4 mb-8">
        <span class="material-symbols-outlined flex-shrink-0">info</span>
        <div>
            <span class="font-semibold">Service Status:</span> All systems are currently operational. No planned maintenance.
        </div>
    </div>

    <div class="mb-12">
        <h2 class="text-2xl font-semibold mb-6 text-text-light-primary dark:text-white">Frequently Asked Questions</h2>
        <div class="space-y-4">
            <div class="bg-card-dark rounded-lg">
                <button @click="activeAccordion = activeAccordion === 1 ? null : 1" class="flex justify-between items-center w-full p-5 text-left font-medium text-white focus:outline-none">
                    <span>How do I create a custom short link?</span>
                    <span :class="{'rotate-180': activeAccordion === 1}" class="material-symbols-outlined transform transition-transform">expand_more</span>
                </button>
                <div class="overflow-hidden transition-all max-h-0 duration-300 ease-in-out" x-bind:style="activeAccordion === 1 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''" x-ref="container1">
                    <div class="p-5 border-t border-slate-700">
                        <p class="text-dark-secondary">You can create a custom short link by navigating to the "Links" section and clicking on the "Create New" button. In the creation form, you will find an option to specify a custom alias for your short link.</p>
                    </div>
                </div>
            </div>
            <div class="bg-card-dark rounded-lg">
                <button @click="activeAccordion = activeAccordion === 2 ? null : 2" class="flex justify-between items-center w-full p-5 text-left font-medium text-white focus:outline-none">
                    <span>Can I track the analytics of my links?</span>
                    <span :class="{'rotate-180': activeAccordion === 2}" class="material-symbols-outlined transform transition-transform">expand_more</span>
                </button>
                <div class="overflow-hidden transition-all max-h-0 duration-300 ease-in-out" x-bind:style="activeAccordion === 2 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : ''" x-ref="container2">
                    <div class="p-5 border-t border-slate-700">
                        <p class="text-dark-secondary">Yes, absolutely. Our dashboard provides detailed analytics for each link, including click-through rates, geographic data, referrer information, and more. Go to the "Dashboard" or "Reports" page to view these stats.</p>
                    </div>
                </div>
            </div>
            <div class="bg-card-dark rounded-lg">
                <button @click="activeAccordion = activeAccordion === 3 ? null : 3" class="flex justify-between items-center w-full p-5 text-left font-medium text-white focus:outline-none">
                    <span>What is the difference between plans?</span>
                    <span :class="{'rotate-180': activeAccordion === 3}" class="material-symbols-outlined transform transition-transform">expand_more</span>
                </button>
                <div class="overflow-hidden transition-all max-h-0 duration-300 ease-in-out" x-bind:style="activeAccordion === 3 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : ''" x-ref="container3">
                    <div class="p-5 border-t border-slate-700">
                        <p class="text-dark-secondary">Our plans differ based on the number of links you can create, the level of analytics provided, and access to premium features like custom domains and team collaboration. Please visit our pricing page for a detailed comparison.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-card-light dark:bg-card-dark rounded-lg p-8 shadow-sm">
        <h2 class="text-2xl font-semibold mb-6 text-text-light-primary dark:text-white">Create Support Request</h2>
        <form wire:submit.prevent="createTicket" class="space-y-6">
            <div>
                <label class="block text-sm font-medium mb-2 text-text-light-secondary dark:text-white" for="subject">Subject</label>
                <input wire:model.defer="newTicketSubject" class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-text-light-primary dark:text-white focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 placeholder:text-slate-400" id="subject" name="subject" placeholder="e.g. Issue with link redirection" type="text"/>
                @error('newTicketSubject') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-2 text-text-light-secondary dark:text-white" for="category">Category</label>
                <select wire:model.defer="newTicketCategory" class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-text-light-primary dark:text-white focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" id="category" name="category">
                    <option value="">Select Category</option>
                    <option value="technical">Technical Issue</option>
                    <option value="payment">Billing Inquiry</option>
                    <option value="feature_request">Feature Request</option>
                    <option value="general">General Question</option>
                </select>
                @error('newTicketCategory') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-2 text-text-light-secondary dark:text-white" for="priority">Priority</label>
                <select wire:model.defer="newTicketPriority" class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-text-light-primary dark:text-white focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" id="priority" name="priority">
                    <option value="">Select Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
                @error('newTicketPriority') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-2 text-text-light-secondary dark:text-white" for="message">Message</label>
                <textarea wire:model.defer="newTicketMessage" class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-text-light-primary dark:text-white focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 placeholder:text-slate-400" id="message" name="message" placeholder="Please describe your issue in detail..." rows="5"></textarea>
                @error('newTicketMessage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="pt-2 flex justify-end">
                <button class="inline-flex justify-center rounded-md border border-transparent bg-gradient-to-b from-blue-500 to-blue-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-primary-darker focus:ring-offset-2 dark:focus:ring-offset-slate-900" type="submit">
                    Send
                </button>
            </div>
        </form>
    </div>

    <div class="mt-12">
        <h2 class="text-2xl font-semibold mb-6 text-text-light-primary dark:text-white">Your Support Requests</h2>
        <div class="bg-card-light dark:bg-card-dark rounded-lg shadow-sm">
            <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-grow">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                        <input wire:model.live.debounce.300ms="search" class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 pl-10 text-text-light-primary dark:text-white focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 placeholder:text-slate-400" id="search-request" name="search" placeholder="Search by subject or content..." type="text"/>
                    </div>
                    <div class="flex items-center gap-4">
                        <select wire:model.live="statusFilter" class="w-full md:w-auto rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-text-light-primary dark:text-white focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" id="filter-status" name="status">
                            <option value="all">All Statuses</option>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                            <option value="in_progress">In Progress</option>
                        </select>
                        <button class="p-2.5 rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-text-light-secondary dark:text-dark-secondary hover:bg-slate-50 dark:hover:bg-slate-700">
                            <span class="material-symbols-outlined">filter_list</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($tickets as $ticket)
                    <div wire:click="selectTicket({{ $ticket->id }})" class="p-6 flex items-center justify-between cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors duration-150">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span @class([
                                    'w-3 h-3 rounded-full flex-shrink-0',
                                    'bg-green-500' => $ticket->status === 'open',
                                    'bg-yellow-400' => $ticket->status === 'in_progress',
                                    'bg-red-500' => $ticket->status === 'closed',
                                    'bg-gray-400' => !in_array($ticket->status, ['open', 'in_progress', 'closed']),
                                ])></span>
                                <h3 class="font-semibold text-text-light-primary dark:text-white">{{ $ticket->subject }}</h3>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-sm text-text-light-secondary dark:text-dark-secondary pl-6">
                                <span>Category: {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</span>
                                <span>Priority: {{ ucfirst($ticket->priority) }}</span>
                                <span>Status: {{ ucfirst($ticket->status) }}</span>
                                <span>Last Update: {{ $ticket->updated_at->format('Y-m-d') }}</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-text-light-secondary dark:text-dark-secondary ml-4">chevron_right</span>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-500 dark:text-slate-400">
                        You have no support requests yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Ticket Detail Modal --}}
    @if($selectedTicket)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" 
         x-show="isModalOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         style="display: none;">
        <div @click.outside="isModalOpen = false; $wire.deselectTicket()" 
             class="w-full max-w-2xl bg-card-dark rounded-lg shadow-xl overflow-hidden"
             x-show="isModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <div class="px-6 py-4 border-b border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">Ticket #{{$selectedTicket->id}}: {{ $selectedTicket->subject }}</h3>
                <button @click="isModalOpen = false; $wire.deselectTicket()" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <div class="space-y-6 text-sm">
                    {{-- Initial Message --}}
                    <div class="flex items-start gap-4">
                        <img alt="User avatar" class="h-8 w-8 rounded-full object-cover flex-shrink-0" src="{{ $selectedTicket->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($selectedTicket->user->name) }}"/>
                        <div class="flex-1 bg-slate-900 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-semibold text-white">{{ $selectedTicket->user->name }} (You)</span>
                                <span class="text-xs text-slate-400">{{ $selectedTicket->created_at->format('d M, Y, H:i A') }}</span>
                            </div>
                            <p class="text-slate-300 whitespace-pre-wrap">{{ $selectedTicket->message }}</p>
                        </div>
                    </div>
                    {{-- Replies --}}
                    @foreach($selectedTicket->replies as $reply)
                    <div class="flex items-start gap-4">
                        @if($reply->user && $reply->user->is_admin)
                        <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-white text-base">support_agent</span>
                        </div>
                        <div class="flex-1 bg-slate-700 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-semibold text-white">{{ $reply->user->name }} (Support)</span>
                                <span class="text-xs text-slate-400">{{ $reply->created_at->format('d M, Y, H:i A') }}</span>
                            </div>
                            <p class="text-slate-300 whitespace-pre-wrap">{{ $reply->message }}</p>
                        </div>
                        @else
                        <img alt="User avatar" class="h-8 w-8 rounded-full object-cover flex-shrink-0" src="{{ $reply->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}"/>
                        <div class="flex-1 bg-slate-900 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-semibold text-white">{{ $reply->user->name }} (You)</span>
                                <span class="text-xs text-slate-400">{{ $reply->created_at->format('d M, Y, H:i A') }}</span>
                            </div>
                            <p class="text-slate-300 whitespace-pre-wrap">{{ $reply->message }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @if($selectedTicket->status !== 'closed')
            <div class="px-6 py-4 bg-slate-800 border-t border-slate-700">
                <form wire:submit.prevent="sendUserReply">
                    <textarea wire:model="userReply" class="block w-full rounded-md border-slate-600 bg-slate-900 text-white placeholder:text-slate-400 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm" placeholder="Type your reply..." rows="3"></textarea>
                    @error('userReply') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    <div class="mt-3 flex justify-end gap-3">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-primary py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-primary-darker focus:outline-none focus:ring-2 focus:ring-primary-darker focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                            Reply
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Success Modal --}}
    <div x-show="showSuccessModal" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         style="display: none;">
        <div @click.outside="showSuccessModal = false" class="w-full max-w-md bg-card-dark rounded-lg shadow-xl p-8 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-500">
                <span class="material-symbols-outlined text-white">check</span>
            </div>
            <h3 class="text-lg leading-6 font-medium text-white mt-5">Success!</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-dark-secondary">
                    Your support request has been created successfully.
                </p>
            </div>
            <div class="mt-4">
                <button @click="showSuccessModal = false" class="inline-flex justify-center rounded-md border border-transparent bg-primary py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-primary-darker focus:outline-none focus:ring-2 focus:ring-primary-darker focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
