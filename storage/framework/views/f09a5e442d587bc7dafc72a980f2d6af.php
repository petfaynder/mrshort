<footer class="bg-black border-t border-gray-800 py-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <!-- Brand Column -->
            <div class="col-span-1">
                <a class="flex-shrink-0 mb-6 block" href="<?php echo e(url('/')); ?>">
                    <span class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-electric-blue to-bright-magenta tracking-tight" style="font-family: 'Inter', 'Space Grotesk', sans-serif;"><?php echo e(config('app.name', 'MrShort')); ?></span>
                </a>
                <p class="text-gray-400 text-base leading-relaxed mb-6" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                    The smartest way to monetize your links. High CPMs, reliable payments, and premium support.
                </p>
                <!-- Social Media Links from Settings -->
                <div class="flex gap-4">
                    <?php if(setting('facebook_url')): ?>
                    <a href="<?php echo e(setting('facebook_url')); ?>" target="_blank" rel="noopener noreferrer" class="w-11 h-11 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-electric-blue hover:border-electric-blue/50 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if(setting('twitter_url')): ?>
                    <a href="<?php echo e(setting('twitter_url')); ?>" target="_blank" rel="noopener noreferrer" class="w-11 h-11 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-electric-blue hover:border-electric-blue/50 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if(setting('instagram_url')): ?>
                    <a href="<?php echo e(setting('instagram_url')); ?>" target="_blank" rel="noopener noreferrer" class="w-11 h-11 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-electric-blue hover:border-electric-blue/50 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if(setting('youtube_url')): ?>
                    <a href="<?php echo e(setting('youtube_url')); ?>" target="_blank" rel="noopener noreferrer" class="w-11 h-11 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-electric-blue hover:border-electric-blue/50 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if(setting('linkedin_url')): ?>
                    <a href="<?php echo e(setting('linkedin_url')); ?>" target="_blank" rel="noopener noreferrer" class="w-11 h-11 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-electric-blue hover:border-electric-blue/50 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if(!setting('facebook_url') && !setting('twitter_url') && !setting('instagram_url')): ?>
                    <a href="mailto:support@mrshort.io" class="w-11 h-11 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-electric-blue hover:border-electric-blue/50 transition-all duration-300">
                        <span class="material-symbols-outlined text-xl">mail</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Platform Column -->
            <div>
                <h4 class="text-white font-bold text-lg mb-6" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">Platform</h4>
                <ul class="space-y-4 text-base text-gray-400" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                    <li><a href="<?php echo e(route('payout.rates')); ?>" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">payments</span> Payout Rates
                    </a></li>
                    <li><a href="<?php echo e(route('api.documentation')); ?>" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">api</span> API Documentation
                    </a></li>
                    <?php if(setting('blog_enabled', false)): ?>
                    <li><a href="<?php echo e(route('blog.index')); ?>" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">article</span> Blog
                    </a></li>
                    <?php endif; ?>
                    <?php if(auth()->guard()->check()): ?>
                    <li><a href="<?php echo e(route('dashboard')); ?>" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">dashboard</span> Dashboard
                    </a></li>
                    <?php else: ?>
                    <li><a href="<?php echo e(route('register')); ?>" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">person_add</span> Get Started
                    </a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <!-- Legal Column -->
            <div>
                <h4 class="text-white font-bold text-lg mb-6" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">Legal</h4>
                <ul class="space-y-4 text-base text-gray-400" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                    <li><a href="<?php echo e(route('privacy.policy')); ?>" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">privacy_tip</span> Privacy Policy
                    </a></li>
                    <li><a href="<?php echo e(route('terms.of.service')); ?>" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">gavel</span> Terms of Service
                    </a></li>
                    <li><a href="<?php echo e(route('cookie.policy')); ?>" class="hover:text-electric-blue transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">cookie</span> Cookie Policy
                    </a></li>
                </ul>
            </div>
            
            <!-- Stats Column -->
            <?php if(setting('display_homepage_stats', true)): ?>
            <div>
                <h4 class="text-white font-bold text-lg mb-6" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">Statistics</h4>
                <ul class="space-y-4 text-base text-gray-400" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                    <?php
                        $realUsers = \App\Models\User::count();
                        $realLinks = \App\Models\Link::count();
                        $realClicks = \App\Models\LinkClick::count();
                        $fakeUsers = (int) setting('fake_users_base', 0);
                        $fakeLinks = (int) setting('fake_links_base', 0);
                        $fakeClicks = (int) setting('fake_clicks_base', 0);
                    ?>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg text-electric-blue">group</span>
                        <span class="text-white font-semibold"><?php echo e(number_format($realUsers + $fakeUsers)); ?></span> Publishers
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg text-bright-magenta">link</span>
                        <span class="text-white font-semibold"><?php echo e(number_format($realLinks + $fakeLinks)); ?></span> Links
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg text-electric-blue">touch_app</span>
                        <span class="text-white font-semibold"><?php echo e(number_format($realClicks + $fakeClicks)); ?></span> Clicks
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="border-t border-gray-900 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-500 text-base" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">© <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'MrShort')); ?>. All rights reserved.</p>
            <div class="flex items-center gap-6 text-base text-gray-500" style="font-family: 'Inter', 'Space Grotesk', sans-serif;">
                <a href="<?php echo e(route('privacy.policy')); ?>" class="hover:text-gray-300 transition-colors">Privacy</a>
                <span class="text-gray-800">•</span>
                <a href="<?php echo e(route('terms.of.service')); ?>" class="hover:text-gray-300 transition-colors">Terms</a>
                <span class="text-gray-800">•</span>
                <a href="<?php echo e(route('cookie.policy')); ?>" class="hover:text-gray-300 transition-colors">Cookies</a>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/partials/footer.blade.php ENDPATH**/ ?>