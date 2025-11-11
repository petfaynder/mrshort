<div>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-auto sm:max-w-xs">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light dark:text-text-dark">search</span>
            <input class="w-full h-10 pl-10 pr-4 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Search by username..." type="text"/>
        </div>
        <select class="w-full sm:w-auto h-10 px-3 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:outline-none focus:ring-2 focus:ring-primary">
            <option>Filter by Status</option>
            <option>Active</option>
            <option>Inactive</option>
            <option>Pending</option>
        </select>
    </div>
    <div class="overflow-x-auto rounded-lg border border-border-light dark:border-border-dark mt-4">
        <table class="min-w-full divide-y divide-border-light dark:divide-border-dark">
            <thead class="bg-background-light dark:bg-background-dark">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Username</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Registration Date</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Earnings</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Last Activity</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-text-light dark:text-text-dark uppercase tracking-wider" scope="col">Status</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-border-light dark:divide-border-dark">
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-heading-light dark:text-heading-dark">john.doe</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">2023-05-15</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">$55.20</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">2 hours ago</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">Active</span>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-heading-light dark:text-heading-dark">jane.smith</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">2023-04-22</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">$41.80</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">2023-05-20</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">Active</span>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-heading-light dark:text-heading-dark">sam.wilson</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">2023-03-10</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">$12.50</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">2023-03-15</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700/50 text-gray-800 dark:text-gray-300">Inactive</span>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-heading-light dark:text-heading-dark">alex.ray</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">2023-02-01</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">$78.95</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">1 day ago</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">Active</span>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-heading-light dark:text-heading-dark">chris.lee</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">2023-01-18</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">$62.30</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light dark:text-text-dark">-</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">Pending</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <nav aria-label="Pagination" class="flex items-center justify-between pt-4">
        <div class="hidden sm:block">
            <p class="text-sm text-text-light dark:text-text-dark">
                Showing
                <span class="font-medium">1</span>
                to
                <span class="font-medium">5</span>
                of
                <span class="font-medium">15</span>
                results
            </p>
        </div>
        <div class="flex flex-1 justify-between sm:justify-end gap-2">
            <button class="relative inline-flex items-center px-4 py-2 border border-border-light dark:border-border-dark text-sm font-medium rounded-md text-text-light dark:text-text-dark bg-card-light dark:bg-card-dark hover:bg-gray-50 dark:hover:bg-gray-700/50">Previous</button>
            <button class="relative inline-flex items-center px-4 py-2 border border-border-light dark:border-border-dark text-sm font-medium rounded-md text-text-light dark:text-text-dark bg-card-light dark:bg-card-dark hover:bg-gray-50 dark:hover:bg-gray-700/50">Next</button>
        </div>
    </nav>
</div>
