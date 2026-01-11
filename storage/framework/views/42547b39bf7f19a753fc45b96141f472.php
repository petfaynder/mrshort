<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-heading-light dark:text-heading-dark mb-3">
                🗺️ Product Roadmap
            </h1>
            <p class="text-lg text-text-light dark:text-text-dark max-w-2xl mx-auto">
                Where we're heading next
            </p>
            <a href="<?php echo e(route('feedback.index')); ?>" class="inline-flex items-center gap-2 mt-4 text-primary hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Back to Feedback
            </a>
        </div>

        <!-- Community-Driven Message -->
        <div class="bg-card-light dark:bg-card-dark rounded-2xl border border-border-light dark:border-border-dark p-8 md:p-12 text-center shadow-xl">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                <span class="material-symbols-outlined text-white text-4xl">groups</span>
            </div>
            
            <h2 class="text-2xl font-bold text-heading-light dark:text-heading-dark mb-4">
                Our Roadmap is Shaped by You
            </h2>
            
            <p class="text-lg text-text-light dark:text-text-dark max-w-xl mx-auto mb-6 leading-relaxed">
                We believe in building what matters most to our community. Our product roadmap is directly influenced by your feedback, feature requests, and votes.
            </p>

            <p class="text-text-light dark:text-text-dark max-w-xl mx-auto mb-8 opacity-80">
                The most requested features rise to the top of our priorities. Your voice truly matters in shaping the future of MRShort.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="<?php echo e(route('feedback.index')); ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-200 hover:scale-105">
                    <span class="material-symbols-outlined">lightbulb</span>
                    Submit Your Ideas
                </a>
                <a href="<?php echo e(route('feedback.index')); ?>?sort=popular" class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-700 text-heading-light dark:text-heading-dark font-semibold px-6 py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                    <span class="material-symbols-outlined">trending_up</span>
                    View Top Requests
                </a>
            </div>
        </div>

        <!-- How It Works -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-2xl">edit_note</span>
                </div>
                <h3 class="font-semibold text-heading-light dark:text-heading-dark mb-2">1. Share Your Idea</h3>
                <p class="text-sm text-text-light dark:text-text-dark opacity-80">
                    Submit a feature request describing what you'd like to see in MRShort.
                </p>
            </div>
            
            <div class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-2xl">thumb_up</span>
                </div>
                <h3 class="font-semibold text-heading-light dark:text-heading-dark mb-2">2. Community Votes</h3>
                <p class="text-sm text-text-light dark:text-text-dark opacity-80">
                    Other users vote on ideas they want. The most wanted features rise to the top.
                </p>
            </div>
            
            <div class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">rocket_launch</span>
                </div>
                <h3 class="font-semibold text-heading-light dark:text-heading-dark mb-2">3. We Build It</h3>
                <p class="text-sm text-text-light dark:text-text-dark opacity-80">
                    We prioritize and develop top-voted features, keeping you updated on progress.
                </p>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/feedback/roadmap.blade.php ENDPATH**/ ?>