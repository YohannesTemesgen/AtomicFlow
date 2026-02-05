<div>
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-surface-light dark:bg-surface-dark p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400">
                        <span class="material-icons-outlined">category</span>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Board Types</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['board_types'] ?? 0 }}</p>
            </div>
            
            <div class="bg-surface-light dark:bg-surface-dark p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-500 dark:text-blue-400">
                        <span class="material-icons-outlined">view_kanban</span>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Boards</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['boards'] ?? 0 }}</p>
            </div>
            
            <div class="bg-surface-light dark:bg-surface-dark p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-orange-50 dark:bg-orange-900/30 rounded-lg text-orange-500 dark:text-orange-400">
                        <span class="material-icons-outlined">style</span>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cards</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['total_cards'] ?? 0 }}</p>
            </div>
            
            <div class="bg-surface-light dark:bg-surface-dark p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                        <span class="material-icons-outlined">check_circle</span>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Boards</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['active_boards'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Board Types Overview -->
        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Board Types</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage your Kanban board configurations</p>
                </div>
                <a href="{{ route('admin.board-types') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition flex items-center">
                    <span class="material-icons-outlined mr-2 text-sm">settings</span>
                    Manage
                </a>
            </div>
            
            @if($boardTypes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($boardTypes as $type)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="p-2 rounded-lg" style="background-color: {{ $type->theme_color }}20;">
                                    <span class="material-icons-outlined" style="color: {{ $type->theme_color }};">{{ $type->icon }}</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ $type->name }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $type->boards->count() }} boards</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $type->description ?? 'No description' }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <span class="material-icons-outlined text-4xl text-gray-400 mb-2">category</span>
                    <p class="text-gray-500 dark:text-gray-400">No board types created yet.</p>
                    <a href="{{ route('admin.board-types') }}" class="text-primary hover:underline">Create your first board type</a>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.board-types') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <span class="material-icons-outlined text-2xl text-primary mb-2">add_circle</span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">New Board Type</span>
                </a>
                <a href="{{ route('admin.boards') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <span class="material-icons-outlined text-2xl text-blue-500 mb-2">view_kanban</span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Create Board</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <span class="material-icons-outlined text-2xl text-orange-500 mb-2">palette</span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Theme Settings</span>
                </a>
                <a href="{{ route('admin.profile') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <span class="material-icons-outlined text-2xl text-purple-500 mb-2">person</span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Profile</span>
                </a>
            </div>
        </div>
    </div>
</div>
