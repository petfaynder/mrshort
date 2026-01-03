<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Logs</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ \App\Models\AuditLog::count() }}
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">Today's Logs</div>
                <div class="text-2xl font-bold text-primary-600">
                    {{ \App\Models\AuditLog::whereDate('created_at', today())->count() }}
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">Failed Logins</div>
                <div class="text-2xl font-bold text-danger-600">
                    {{ \App\Models\AuditLog::where('action', 'login_failed')->whereDate('created_at', today())->count() }}
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">Withdrawals</div>
                <div class="text-2xl font-bold text-success-600">
                    {{ \App\Models\AuditLog::where('action', 'like', 'withdrawal%')->whereDate('created_at', today())->count() }}
                </div>
            </div>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>
