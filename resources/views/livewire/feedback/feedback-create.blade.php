<form wire:submit.prevent="save" class="space-y-4">
    @if (session()->has('message'))
        <div class="bg-green-50 text-green-700 p-3 rounded-lg mb-4 text-sm dark:bg-green-900/30 dark:text-green-300 flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">check_circle</span>
            {{ session('message') }}
        </div>
    @endif

    <div>
        <label for="title" class="sr-only">Title</label>
        <input 
            wire:model="title" 
            type="text" 
            id="title" 
            class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white p-3 transition-all duration-200 hover:border-gray-300" 
            placeholder="✨ I suggest..." 
            required
        >
        @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="description" class="sr-only">Description</label>
        <textarea 
            wire:model="description" 
            id="description" 
            rows="4" 
            class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white p-3 transition-all duration-200 hover:border-gray-300 resize-none" 
            placeholder="Because this would help..." 
            required
        ></textarea>
        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="btn-spin w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 hover:shadow-md">
        <span class="material-symbols-outlined btn-icon text-lg">send</span>
        Post Feedback
    </button>
    
    @guest
        <p class="text-xs text-center text-gray-400">
            You need to <a href="{{ route('login') }}" class="text-blue-600 hover:underline">log in</a> to post feedback.
        </p>
    @endguest
</form>
