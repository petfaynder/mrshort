<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 transition-all duration-200 hover:shadow-md hover:border-gray-200 dark:hover:border-gray-600">
    <div class="flex items-start gap-4">
        <!-- Vote Button with spin/pulse effect -->
        <button wire:click="vote" class="vote-btn flex flex-col items-center justify-center p-3 rounded-xl border-2 min-w-[70px] cursor-pointer transition-all duration-200 {{ $post->isVotedBy(auth()->user()) ? 'bg-blue-50 border-blue-300 text-blue-600 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-400 shadow-sm' : 'bg-gray-50 border-gray-200 text-gray-500 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 dark:hover:border-blue-600 dark:hover:bg-blue-900/10' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-1 {{ $post->isVotedBy(auth()->user()) ? 'animate-bounce' : '' }}" viewBox="0 0 20 20" fill="currentColor" style="animation-duration: 1s; animation-iteration-count: 1;">
                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            <span class="font-bold text-xl leading-none">{{ $post->vote_count }}</span>
            <span class="text-[10px] uppercase tracking-wide mt-0.5 opacity-75">votes</span>
        </button>
        
        <!-- Content -->
        <div class="flex-1 min-w-0">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                <a href="{{ route('feedback.show', $post->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $post->title }}</a>
            </h3>
            
            <p class="mt-1 text-gray-500 dark:text-gray-400 text-sm line-clamp-2">
                {{ Str::limit($post->description, 160) }}
            </p>

            <div class="mt-3 flex items-center gap-4 text-xs">
                <!-- Status Badge -->
                @php
                    $colors = [
                        'review' => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600',
                        'planned' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                        'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800',
                        'declined' => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                    ];
                    $icons = [
                        'review' => '🔍',
                        'planned' => '📋',
                        'in_progress' => '🚧',
                        'completed' => '✅',
                        'declined' => '❌',
                    ];
                    $label = ucwords(str_replace('_', ' ', $post->status));
                @endphp
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium border {{ $colors[$post->status] }}">
                    <span>{{ $icons[$post->status] }}</span>
                    {{ $label }}
                </span>

                <!-- Author -->
                <span class="text-gray-400">
                    by <span class="font-medium text-gray-600 dark:text-gray-300">{{ $post->user->name ?? 'Anonymous' }}</span>
                </span>

                <!-- Comments -->
                <a href="{{ route('feedback.show', $post->slug) }}" class="flex items-center gap-1 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors ml-auto">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                    <span class="font-medium">{{ $post->comment_count }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
