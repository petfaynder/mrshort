<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Project Info -->
                <div>
                   <h1 class="text-2xl font-bold text-heading-light dark:text-heading-dark">Feedback</h1>
                   <p class="text-sm text-text-light dark:text-text-dark mt-1">Help us improve MRShort by suggesting new features or voting on existing ones.</p>
                </div>

                <!-- Search -->
                <div class="relative">
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        placeholder="Search suggestions..." 
                        class="w-full pl-10 pr-4 py-2 border border-border-light dark:border-border-dark rounded-lg bg-card-light dark:bg-card-dark text-sm focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-text-light dark:text-text-dark opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Create Form -->
                <div class="bg-card-light dark:bg-card-dark rounded-lg shadow-sm p-5 border border-border-light dark:border-border-dark">
                    <h3 class="font-semibold text-heading-light dark:text-heading-dark mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">lightbulb</span>
                        Suggest a Feature
                    </h3>
                    @livewire('feedback.feedback-create')
                </div>

                <!-- Filters -->
                <div class="bg-card-light dark:bg-card-dark rounded-lg shadow-sm p-4 border border-border-light dark:border-border-dark">
                     <h4 class="text-xs font-semibold text-text-light dark:text-text-dark uppercase tracking-wider mb-3 opacity-60">Status</h4>
                     <nav class="space-y-1">
                        @foreach(['all' => 'All Suggestions', 'review' => 'Under Review', 'planned' => 'Planned', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'declined' => 'Declined'] as $key => $label)
                            @php
                                $colors = [
                                    'all' => '',
                                    'review' => 'bg-gray-400',
                                    'planned' => 'bg-blue-500',
                                    'in_progress' => 'bg-yellow-500',
                                    'completed' => 'bg-green-500',
                                    'declined' => 'bg-red-500',
                                ];
                            @endphp
                            <button 
                                wire:click="$set('status', '{{ $key }}')" 
                                class="w-full text-left px-3 py-2 text-sm font-medium rounded-md flex items-center gap-2 transition-all duration-200 {{ $status === $key ? 'bg-blue-100 text-primary dark:bg-blue-900/20 dark:text-blue-400' : 'text-text-light hover:bg-gray-100 dark:text-text-dark dark:hover:bg-gray-800' }}"
                            >
                                @if($key !== 'all')
                                    <span class="w-2 h-2 rounded-full {{ $colors[$key] }}"></span>
                                @endif
                                <span class="flex-1">{{ $label }}</span>
                                @if($status === $key)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                @endif
                            </button>
                        @endforeach
                     </nav>
                </div>

                <!-- Roadmap Link -->
                <a href="{{ route('feedback.roadmap') }}" class="block bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg p-4 shadow-md hover:shadow-lg transition-all duration-200 hover:scale-[1.02]">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-2xl">map</span>
                        <div>
                            <div class="font-semibold">View Roadmap</div>
                            <div class="text-xs text-blue-100">See what's coming next</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Sorting & Meta -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-medium text-text-light dark:text-text-dark">
                        <span class="font-bold text-heading-light dark:text-heading-dark">{{ $posts->total() }}</span> suggestions
                    </h2>
                    <div class="flex items-center space-x-2 bg-card-light dark:bg-card-dark rounded-lg p-1 shadow-sm border border-border-light dark:border-border-dark">
                        <button wire:click="$set('sort', 'popular')" class="px-3 py-1.5 text-sm font-medium rounded-md transition-all duration-200 {{ $sort === 'popular' ? 'bg-gray-100 text-heading-light dark:bg-gray-700 dark:text-heading-dark' : 'text-text-light hover:text-heading-light dark:text-text-dark dark:hover:text-heading-dark' }}">
                            🔥 Most Wanted
                        </button>
                        <button wire:click="$set('sort', 'newest')" class="px-3 py-1.5 text-sm font-medium rounded-md transition-all duration-200 {{ $sort === 'newest' ? 'bg-gray-100 text-heading-light dark:bg-gray-700 dark:text-heading-dark' : 'text-text-light hover:text-heading-light dark:text-text-dark dark:hover:text-heading-dark' }}">
                            ✨ Newest
                        </button>
                    </div>
                </div>

                <!-- List -->
                <div class="space-y-4">
                    @forelse($posts as $post)
                        @livewire('feedback.feedback-item', ['post' => $post], key($post->id))
                    @empty
                        <div class="text-center py-16 bg-card-light dark:bg-card-dark rounded-lg border border-dashed border-border-light dark:border-border-dark">
                            <span class="material-symbols-outlined text-5xl text-text-light dark:text-text-dark opacity-30 mb-4">lightbulb</span>
                            <h3 class="text-lg font-medium text-heading-light dark:text-heading-dark">No suggestions yet</h3>
                            <p class="mt-1 text-sm text-text-light dark:text-text-dark">Be the first to share your idea!</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
