<div class="weekly-competition-widget">
    <!--[if BLOCK]><![endif]--><?php if($competition): ?>
        <div class="bg-gradient-to-br from-amber-900/30 to-orange-900/30 rounded-xl p-5 border border-amber-500/20">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🏆</span>
                    <div>
                        <h3 class="text-lg font-bold text-white"><?php echo e($competition->title); ?></h3>
                        <p class="text-xs text-gray-400"><?php echo e($competition->type_label); ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400">Ends</div>
                    <div class="text-sm font-semibold text-amber-400">
                        <?php echo e($competition->end_date->format('d.m H:i')); ?>

                    </div>
                </div>
            </div>

            <!-- Countdown -->
            <div class="mb-4 text-center">
                <div 
                    class="inline-flex items-center gap-2 bg-gray-800/50 rounded-lg px-4 py-2"
                    x-data="{ 
                        endTime: new Date('<?php echo e($competition->end_date->toIso8601String()); ?>').getTime(),
                        remaining: '',
                        init() {
                            this.updateCountdown();
                            setInterval(() => this.updateCountdown(), 1000);
                        },
                        updateCountdown() {
                            const now = new Date().getTime();
                            const distance = this.endTime - now;
                            if (distance < 0) {
                                this.remaining = 'Ended!';
                                return;
                            }
                            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            this.remaining = days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
                        }
                    }"
                >
                    <span class="text-gray-400">⏱️</span>
                    <span class="text-amber-400 font-mono" x-text="remaining"></span>
                </div>
            </div>

            <!-- Your Position -->
            <!--[if BLOCK]><![endif]--><?php if($userEntry): ?>
                <div class="mb-4 bg-gradient-to-r from-amber-600/20 to-orange-600/20 rounded-lg p-3 border border-amber-500/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold text-amber-400">#<?php echo e($userRank ?? '-'); ?></span>
                            <span class="text-gray-300">Your Rank</span>
                        </div>
                        <div class="text-right">
                            <div class="text-xl font-bold text-white"><?php echo e(number_format($userEntry->score)); ?></div>
                            <div class="text-xs text-gray-400"><?php echo e($competition->type === 'clicks' ? 'clicks' : 'points'); ?></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="mb-4 bg-gray-800/50 rounded-lg p-3 text-center">
                    <p class="text-gray-400 text-sm">Shorten links and get clicks to join the competition!</p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Leaderboard -->
            <div class="space-y-2">
                <h4 class="text-sm font-semibold text-gray-400 mb-2">Top 10</h4>
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $leaderboardData ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-2 rounded-lg <?php echo e($entry->user_id === auth()->id() ? 'bg-amber-600/20 border border-amber-500/30' : 'bg-gray-800/30'); ?>">
                        <div class="flex items-center gap-3">
                            <span class="w-8 text-center font-bold <?php echo e($index < 3 ? 'text-amber-400' : 'text-gray-500'); ?>">
                                <!--[if BLOCK]><![endif]--><?php if($index === 0): ?> 🥇
                                <?php elseif($index === 1): ?> 🥈
                                <?php elseif($index === 2): ?> 🥉
                                <?php else: ?> <?php echo e($index + 1); ?>

                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </span>
                            <div class="flex items-center gap-2">
                                <!--[if BLOCK]><![endif]--><?php if($entry->user->avatar): ?>
                                    <img src="<?php echo e($entry->user->avatar); ?>" class="w-6 h-6 rounded-full" alt="">
                                <?php else: ?>
                                    <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-xs">
                                        <?php echo e(substr($entry->user->name ?? 'U', 0, 1)); ?>

                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <span class="text-sm <?php echo e($entry->user_id === auth()->id() ? 'text-amber-400 font-semibold' : 'text-gray-300'); ?>">
                                    <?php echo e(Str::limit($entry->user->name ?? 'User', 15)); ?>

                                </span>
                            </div>
                        </div>
                        <span class="font-semibold text-white"><?php echo e(number_format($entry->score)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-gray-500 py-4">
                        No participants yet
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Prize Info -->
            <div class="mt-4 pt-4 border-t border-gray-700">
                <button 
                    class="w-full text-center text-sm text-gray-400 hover:text-amber-400 transition"
                    x-data="{ open: false }"
                    @click="open = !open"
                >
                    <span x-show="!open">🎁 View Prizes</span>
                    <span x-show="open">🎁 Hide Prizes</span>
                    <div x-show="open" class="mt-3 space-y-1 text-left">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $competition->prize_structure ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prize): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between text-xs">
                                <span>
                                    <!--[if BLOCK]><![endif]--><?php if(isset($prize['rank_to']) && $prize['rank_to']): ?>
                                        <?php echo e($prize['rank']); ?>-<?php echo e($prize['rank_to']); ?> place
                                    <?php else: ?>
                                        <?php echo e($prize['rank']); ?> place
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </span>
                                <span class="text-amber-400"><?php echo e(number_format($prize['points'])); ?> points</span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 rounded-xl p-5 border border-gray-700">
            <div class="text-center text-gray-400">
                <span class="text-4xl mb-2 block">🏆</span>
                <p>No active competition at the moment</p>
                <p class="text-xs mt-1">New competitions will start soon!</p>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/weekly-competition.blade.php ENDPATH**/ ?>