<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar (Metadata & Actions) -->
            <div class="lg:col-span-1 space-y-6">
                 <div>
                   <a href="{{ route('feedback.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Back to Feedback
                   </a>
                </div>

                <!-- Big Vote Button -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-700 text-center">
                    <button wire:click="vote" class="w-full flex flex-col items-center justify-center p-4 rounded-xl border-2 transition-all duration-200 {{ $post->isVotedBy(auth()->user()) ? 'bg-blue-50 border-blue-500 text-blue-600 dark:bg-blue-900/20' : 'bg-gray-50 border-gray-200 text-gray-500 hover:border-blue-300 hover:bg-blue-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-3xl font-bold">{{ $post->vote_count }}</span>
                        <span class="text-xs uppercase tracking-wider font-semibold mt-1">Votes</span>
                    </button>
                    <p class="mt-3 text-sm text-gray-500">
                        {{ $post->isVotedBy(auth()->user()) ? 'You voted for this' : 'Click to support this idea' }}
                    </p>
                </div>

                <!-- Status & Info -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-700 space-y-4">
                     <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</span>
                        @php
                            $colors = [
                                'review' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                'planned' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                'in_progress' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                'declined' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                            ];
                            $label = ucwords(str_replace('_', ' ', $post->status));
                        @endphp
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors[$post->status] }}">
                                {{ $label }}
                            </span>
                        </div>
                     </div>

                     <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Submitted By</span>
                        <div class="mt-1 flex items-center gap-2">
                             <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                {{ substr($post->user->name, 0, 1) }}
                             </div>
                             <span class="text-sm text-gray-900 dark:text-white">{{ $post->user->name }}</span>
                        </div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $post->created_at->diffForHumans() }}</div>
                     </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Post Content -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 border border-gray-100 dark:border-gray-700">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>
                    <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                        {{ $post->description }}
                    </div>
                </div>

                <!-- Admin Response (Placeholder logic) -->
                @if($post->comments->where('is_official_response', true)->count() > 0)
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 border border-blue-100 dark:border-blue-800">
                        <div class="flex items-center gap-2 mb-2 text-blue-700 dark:text-blue-400 font-semibold">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Official Response
                        </div>
                         <!-- Logic for official response loop/display -->
                    </div>
                @endif

                <!-- Comments Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 rounded-t-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Discussion ({{ $post->comments_count }})</h3>
                    </div>
                    
                    <div class="p-6">
                        <!-- Comment List -->
                        <div class="space-y-6 mb-8">
                            @forelse($post->comments as $comment)
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0">
                                         <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600">
                                            {{ substr($comment->user->name, 0, 1) }}
                                         </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="text-gray-700 dark:text-gray-300 text-sm">
                                                {{ $comment->body }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 py-4">No comments yet. Be the first to share your thoughts!</div>
                            @endforelse
                        </div>

                        <!-- Post Comment Form -->
                        <form wire:submit.prevent="saveComment">
                            <div>
                                <label for="comment" class="sr-only">Your Comment</label>
                                <textarea wire:model="body" id="comment" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white p-3" placeholder="Leave a comment..." required></textarea>
                                @error('body') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-3 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
