<x-layouts.kanban :title="__('Dashboard')">
    <div>
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-100 dark:bg-green-900/50 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="bg-red-100 dark:bg-red-900/50 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Dashboard Header -->
        <div class="flex flex-col gap-4 mb-6 sm:mb-8">
            <div class="text-center sm:text-left">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm sm:text-base">Welcome back! Here's your sales pipeline overview</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full">
                <a href="{{ route('kanban.board') }}" class="flex-1 bg-primary text-white px-4 py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-blue-600 transition-all text-sm font-semibold active:scale-[0.98] touch-target">
                    <span class="material-symbols-outlined text-lg">view_kanban</span>
                    <span>View Kanban Board</span>
                </a>
                <button onclick="window.location.href='{{ route('kanban.board') }}'" class="flex-1 bg-green-600 text-white px-4 py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-green-700 transition-all text-sm font-semibold active:scale-[0.98] touch-target">
                    <span class="material-symbols-outlined text-lg">add</span>
                    <span>Add New Lead</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Total Leads -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="bg-blue-100 dark:bg-blue-900/50 p-2.5 sm:p-3 rounded-xl">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl sm:text-2xl">people</span>
                    </div>
                    <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">+12%</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-1">24</h3>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Total Leads</p>
            </div>

            <!-- Active Leads -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="bg-green-100 dark:bg-green-900/50 p-2.5 sm:p-3 rounded-xl">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl sm:text-2xl">trending_up</span>
                    </div>
                    <span class="text-xs sm:text-sm text-green-600 dark:text-green-400 font-medium">+8%</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-1">18</h3>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Active Leads</p>
            </div>

            <!-- Conversion Rate -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="bg-purple-100 dark:bg-purple-900/50 p-2.5 sm:p-3 rounded-xl">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl sm:text-2xl">percent</span>
                    </div>
                    <span class="text-xs sm:text-sm text-purple-600 dark:text-purple-400 font-medium">+5%</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-1">32.5%</h3>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Conversion Rate</p>
            </div>

            <!-- Total Value -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="bg-amber-100 dark:bg-amber-900/50 p-2.5 sm:p-3 rounded-xl">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-xl sm:text-2xl">euro</span>
                    </div>
                    <span class="text-xs sm:text-sm text-amber-600 dark:text-amber-400 font-medium">+18%</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-1">€125.5K</h3>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Total Pipeline Value</p>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Pipeline by Stage -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4 sm:mb-6">Pipeline by Stage</h3>
                <div class="space-y-3 sm:space-y-4">
                    @foreach(['Contacted' => 6, 'Responded' => 4, 'Negotiation' => 3, 'Active' => 3, 'Completed' => 2] as $stage => $count)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
                            <div class="@if($stage === 'Contacted') bg-amber-500 @elseif($stage === 'Responded') bg-blue-500 @elseif($stage === 'Negotiation') bg-purple-500 @elseif($stage === 'Active') bg-green-500 @else bg-emerald-500 @endif w-3 h-3 rounded-full flex-shrink-0"></div>
                            <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $stage }}</span>
                        </div>
                        <div class="flex items-center space-x-2 sm:space-x-3 flex-shrink-0">
                            <div class="w-20 sm:w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="@if($stage === 'Contacted') bg-amber-500 @elseif($stage === 'Responded') bg-blue-500 @elseif($stage === 'Negotiation') bg-purple-500 @elseif($stage === 'Active') bg-green-500 @else bg-emerald-500 @endif h-2 rounded-full transition-all duration-300" style="width: {{ ($count / 18) * 100 }}%"></div>
                            </div>
                            <span class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white w-6 sm:w-8 text-right">{{ $count }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4 sm:mb-6">Recent Activity</h3>
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="bg-green-100 dark:bg-green-900/50 p-2 rounded-xl flex-shrink-0">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-base">add</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">New lead created</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Cloud Migration Services • 2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-blue-100 dark:bg-blue-900/50 p-2 rounded-xl flex-shrink-0">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-base">drag_indicator</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">Lead moved to Negotiation</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Marketing Automation • 4 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-emerald-100 dark:bg-emerald-900/50 p-2 rounded-xl flex-shrink-0">
                            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-base">check_circle</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">Lead won</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Updated Test Project • 1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Leads Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Recent Leads</h3>
                    <a href="{{ route('kanban.board') }}" class="text-xs sm:text-sm text-primary hover:text-blue-600 font-medium">View All</a>
                </div>
            </div>
            
            <!-- Mobile Card View -->
            <div class="block sm:hidden">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center text-white text-sm font-bold">A</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm">Cloud Migration Services</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Acme Corporation</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900 dark:text-white text-sm">€75,000</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">1 day ago</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">Migrate infrastructure to AWS</p>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 rounded-full">Active</span>
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 rounded-full">Active</span>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center text-white text-sm font-bold">A</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm">Marketing Automation</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Acme Corporation</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900 dark:text-white text-sm">€15,000</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">1 day ago</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">Implement HubSpot system</p>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 rounded-full">Completed</span>
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 rounded-full">Active</span>
                        </div>
                    </div>
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center text-white text-sm font-bold">A</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm">Updated Test Project</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Acme Corporation</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900 dark:text-white text-sm">€15,000</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">1 day ago</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">Test lead via automation</p>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300 rounded-full">Negotiation</span>
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 rounded-full">Active</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lead</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Value</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stage</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Cloud Migration Services</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Migrate infrastructure to AWS</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">A</div>
                                    <span class="text-sm text-gray-900 dark:text-white">Acme Corporation</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">€75,000</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 rounded-full">Active</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 rounded-full">Active</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">1 day ago</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Marketing Automation</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Implement HubSpot system</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">A</div>
                                    <span class="text-sm text-gray-900 dark:text-white">Acme Corporation</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">€15,000</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 rounded-full">Completed</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 rounded-full">Active</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">1 day ago</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Updated Test Project</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Test lead via automation</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">A</div>
                                    <span class="text-sm text-gray-900 dark:text-white">Acme Corporation</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">€15,000</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300 rounded-full">Negotiation</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 rounded-full">Active</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">1 day ago</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add any dashboard-specific JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            // Animate statistics cards on load
            const cards = document.querySelectorAll('.grid > div');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
    @endpush
</x-layouts.kanban>