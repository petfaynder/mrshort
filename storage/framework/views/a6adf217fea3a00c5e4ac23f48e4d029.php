<div class="daily-challenges-widget">
    <div class="bg-gradient-to-br from-blue-900/30 to-purple-900/30 rounded-xl p-5 border border-blue-500/20">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span class="text-2xl">🎯</span> Daily Challenges
            </h3>
            <span class="text-sm text-gray-400">
                <?php echo e(count($todaysChallenges->completed_ids ?? [])); ?>/<?php echo e(count($todaysChallenges->challenge_ids ?? [])); ?>

            </span>
        </div>

        <!-- Challenges List -->
        <div class="space-y-3">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $todaysChallenges->challenge_ids ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challengeId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $challenge = $challengeDetails[$challengeId] ?? null;
                    if (!$challenge) continue;
                    
                    $progress = $todaysChallenges->progress[$challengeId] ?? 0;
                    $isCompleted = in_array($challengeId, $todaysChallenges->completed_ids ?? []);
                    $percentage = min(100, ($progress / $challenge['target_value']) * 100);
                ?>
                
                <div class="bg-gray-800/50 rounded-lg p-3 <?php echo e($isCompleted ? 'border border-green-500/30' : ''); ?>">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-2">
                            <!--[if BLOCK]><![endif]--><?php if($isCompleted): ?>
                                <span class="text-green-400">✓</span>
                            <?php else: ?>
                                <span class="text-gray-500">○</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <span class="font-medium <?php echo e($isCompleted ? 'text-green-400' : 'text-white'); ?>">
                                <?php echo e($challenge['title']); ?>

                            </span>
                        </div>
                        <span class="text-sm px-2 py-0.5 rounded-full <?php echo e($challenge['difficulty'] === 'easy' ? 'bg-green-500/20 text-green-400' : 
                            ($challenge['difficulty'] === 'medium' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400')); ?>">
                            <?php echo e($challenge['difficulty'] === 'easy' ? 'Easy' : ($challenge['difficulty'] === 'medium' ? 'Medium' : 'Hard')); ?>

                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-1">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span><?php echo e($progress); ?>/<?php echo e($challenge['target_value']); ?></span>
                            <span class="text-yellow-400">+<?php echo e($challenge['points_reward']); ?> points</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div 
                                class="h-2 rounded-full transition-all duration-500 <?php echo e($isCompleted ? 'bg-green-500' : 'bg-gradient-to-r from-blue-500 to-purple-500'); ?>"
                                style="width: <?php echo e($percentage); ?>%"
                            ></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- Bonus Section -->
        <div class="mt-4 pt-4 border-t border-gray-700">
            <!--[if BLOCK]><![endif]--><?php if($todaysChallenges->isAllCompleted()): ?>
                <!--[if BLOCK]><![endif]--><?php if($todaysChallenges->bonus_claimed): ?>
                    <div class="flex items-center justify-center gap-2 text-green-400">
                        <span>🎉</span>
                        <span>3/3 Bonus claimed! (+<?php echo e($bonusPoints); ?> points)</span>
                    </div>
                <?php else: ?>
                    <button 
                        wire:click="claimBonus"
                        class="w-full py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:opacity-90 transition flex items-center justify-center gap-2"
                    >
                        <span class="animate-bounce">🎁</span>
                        3/3 Claim Bonus (+<?php echo e($bonusPoints); ?> Points)
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php else: ?>
                <div class="text-center text-gray-400 text-sm">
                    Complete all challenges and earn <span class="text-yellow-400 font-semibold">+<?php echo e($bonusPoints); ?> bonus points</span>!
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Tolga\Desktop\Proje Siteleri\linkkısaltmaservisi2\resources\views/livewire/user/daily-challenges.blade.php ENDPATH**/ ?>