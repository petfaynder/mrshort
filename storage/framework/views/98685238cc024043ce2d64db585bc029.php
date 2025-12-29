<div class="streak-display">
    <!-- Compact Streak Widget for Dashboard -->
    <div class="bg-gradient-to-r from-orange-600/20 via-red-600/20 to-yellow-600/20 rounded-xl p-4 border border-orange-500/30">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- Fire Icon with Animation -->
                <div class="relative">
                    <span class="text-4xl <?php echo e($streakStatus['current_streak'] > 0 ? 'animate-pulse' : 'grayscale opacity-50'); ?>">🔥</span>
                    <!--[if BLOCK]><![endif]--><?php if($streakStatus['current_streak'] >= 7): ?>
                        <span class="absolute -top-1 -right-1 text-lg">⭐</span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-white"><?php echo e($streakStatus['current_streak']); ?></span>
                        <span class="text-gray-400">days</span>
                    </div>
                    <p class="text-sm text-gray-400">
                        <!--[if BLOCK]><![endif]--><?php if($streakStatus['is_active_today']): ?>
                            <span class="text-green-400">✓ Active today</span>
                        <?php else: ?>
                            <span class="text-yellow-400">⚠ Keep your streak!</span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </p>
                </div>
            </div>

            <div class="text-right">
                <div class="text-sm text-gray-400 mb-1">
                    Longest: <span class="text-orange-400 font-semibold"><?php echo e($streakStatus['longest_streak']); ?> days</span>
                </div>
                <!--[if BLOCK]><![endif]--><?php if($streakStatus['freeze_available'] > 0): ?>
                    <div class="flex items-center justify-end gap-1 text-xs text-blue-400">
                        <span>🛡️</span>
                        <span><?php echo e($streakStatus['freeze_available']); ?> freeze</span>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <!-- Next Milestone Progress -->
        <?php
            $nextMilestone = collect($streakStatus['milestones'])->first(fn($m) => !$m['claimed'] && !$m['reachable']);
            $currentStreak = $streakStatus['current_streak'];
        ?>
        <!--[if BLOCK]><![endif]--><?php if($nextMilestone): ?>
            <div class="mt-4">
                <div class="flex justify-between text-xs text-gray-400 mb-1">
                    <span>Next milestone</span>
                    <span><?php echo e($nextMilestone['milestone']->days_required); ?> days</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div 
                        class="bg-gradient-to-r from-orange-500 to-yellow-500 h-2 rounded-full transition-all duration-500"
                        style="width: <?php echo e(min(100, ($currentStreak / $nextMilestone['milestone']->days_required) * 100)); ?>%"
                    ></div>
                </div>
                <div class="flex justify-between text-xs mt-1">
                    <span class="text-orange-400"><?php echo e($nextMilestone['milestone']->points_reward); ?> points</span>
                    <!--[if BLOCK]><![endif]--><?php if($nextMilestone['milestone']->badgeReward): ?>
                        <span class="text-purple-400">+ Badge</span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Milestones Grid (Collapsible) -->
    <div x-data="{ open: false }" class="mt-4">
        <button 
            @click="open = !open" 
            class="w-full flex items-center justify-between px-4 py-2 bg-gray-800/50 rounded-lg text-gray-300 hover:bg-gray-800 transition"
        >
            <span>Streak Milestones</span>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-collapse class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $streakStatus['milestones']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $milestone = $item['milestone'];
                    $claimed = $item['claimed'];
                    $reachable = $item['reachable'];
                ?>
                <div class="
                    p-3 rounded-lg border transition-all
                    <?php echo e($claimed ? 'bg-green-900/30 border-green-500/50' : ($reachable ? 'bg-yellow-900/30 border-yellow-500/50 animate-pulse' : 'bg-gray-800/50 border-gray-700')); ?>

                ">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-lg font-bold <?php echo e($claimed ? 'text-green-400' : ($reachable ? 'text-yellow-400' : 'text-gray-500')); ?>">
                            <?php echo e($milestone->days_required); ?> days
                        </span>
                        <!--[if BLOCK]><![endif]--><?php if($claimed): ?>
                            <span class="text-green-400">✓</span>
                        <?php elseif($reachable): ?>
                            <span class="text-yellow-400">🎁</span>
                        <?php else: ?>
                            <span class="text-gray-600">🔒</span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="text-xs space-y-1">
                        <div class="<?php echo e($claimed || $reachable ? 'text-gray-300' : 'text-gray-500'); ?>">
                            +<?php echo e($milestone->points_reward); ?> points
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if($milestone->badgeReward): ?>
                            <div class="text-purple-400">🏅 <?php echo e($milestone->badgeReward->name); ?></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($milestone->bonus_type === 'streak_freeze'): ?>
                            <div class="text-blue-400">🛡️ +<?php echo e($milestone->bonus_value); ?> freeze</div>
                        <?php elseif($milestone->bonus_type === 'xp_boost'): ?>
                            <div class="text-yellow-400">⚡ %<?php echo e($milestone->bonus_value); ?> XP</div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/streak-display.blade.php ENDPATH**/ ?>