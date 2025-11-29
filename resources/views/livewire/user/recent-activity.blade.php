<div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-md">
    <h3 class="text-xl font-semibold text-heading-light dark:text-heading-dark mb-4">Recent Activity Feed</h3>
    <ul class="space-y-4">
        @forelse ($activities as $activity)
            <li class="flex items-start gap-3">
                <div class="mt-1 p-1.5 {{ $activity['color_class'] }} rounded-full">
                    <span class="material-symbols-outlined text-base">{{ $activity['icon'] }}</span>
                </div>
                <div>
                    <p class="text-sm text-heading-light dark:text-heading-dark">{{ $activity['description'] }}</p>
                    <p class="text-xs text-text-light dark:text-text-dark">{{ $activity['created_at']->diffForHumans() }}</p>
                </div>
            </li>
        @empty
            <li class="text-sm text-text-light dark:text-text-dark">No recent activity.</li>
        @endforelse
    </ul>
</div>
