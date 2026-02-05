<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Task Tracker Settings</h1>
        <p class="mt-1 text-gray-600 dark:text-gray-400">Manage task stages, assigned people, and preferences.</p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/50 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-300 flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex flex-wrap gap-1 p-2">
                <button wire:click="setActiveTab('stages')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm transition-all {{ $activeTab === 'stages' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="material-symbols-outlined text-xl">view_kanban</span>
                    Task Stages
                </button>
                <button wire:click="setActiveTab('projects')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm transition-all {{ $activeTab === 'projects' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="material-symbols-outlined text-xl">folder</span>
                    Projects
                </button>
                <button wire:click="setActiveTab('assigned_people')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm transition-all {{ $activeTab === 'assigned_people' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="material-symbols-outlined text-xl">assignment_ind</span>
                    Assigned People
                </button>
                <button wire:click="setActiveTab('general')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm transition-all {{ $activeTab === 'general' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="material-symbols-outlined text-xl">settings</span>
                    General
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    
    <!-- Stages Tab -->
    @if ($activeTab === 'stages')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Task Stages</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage the stages in your task tracker board.</p>
                </div>
                <button wire:click="openStageModal"
                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-purple-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-xl mr-2">add</span>
                    Add Stage
                </button>
            </div>

            @if (count($stages) > 0)
                <div class="space-y-3">
                    @foreach ($stages as $index => $stage)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary dark:hover:border-primary transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-lg text-sm font-bold text-gray-600 dark:text-gray-300">
                                    {{ $index + 1 }}
                                </div>
                                <div class="w-4 h-4 rounded-full shadow-sm" style="background-color: {{ $stage['color'] ?? '#8b5cf6' }}"></div>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $stage['name'] }}</span>
                                        @if (!$stage['is_active'])
                                            <span class="px-2 py-0.5 text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full">Inactive</span>
                                        @endif
                                    </div>
                                    @if ($stage['description'])
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $stage['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <button wire:click="toggleStageStatus({{ $stage['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="{{ $stage['is_active'] ? 'Deactivate' : 'Activate' }}">
                                    <span class="material-symbols-outlined text-xl {{ $stage['is_active'] ? 'text-green-500' : '' }}">
                                        {{ $stage['is_active'] ? 'visibility' : 'visibility_off' }}
                                    </span>
                                </button>
                                <button wire:click="openStageModal({{ $stage['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </button>
                                <button wire:click="confirmDeleteStage({{ $stage['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-500">view_kanban</span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No stages yet</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Create stages to build your task tracker board.</p>
                    <div class="mt-6">
                        <button wire:click="openStageModal"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-purple-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-xl mr-2">add</span>
                            Add Stage
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Projects Tab -->
    @if ($activeTab === 'projects')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Projects</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage projects to organize your tasks.</p>
                </div>
                <button wire:click="openProjectModal"
                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-purple-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-xl mr-2">add</span>
                    Add Project
                </button>
            </div>

            @if (count($projects) > 0)
                <div class="space-y-3">
                    @foreach ($projects as $project)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary dark:hover:border-primary transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-4 h-4 rounded-full shadow-sm" style="background-color: {{ $project['color'] ?? '#8b5cf6' }}"></div>
                                <div>
                                    <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $project['name'] }}</span>
                                        <span class="px-2 py-0.5 text-xs rounded-full
                                            @if($project['status'] === 'active') bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300
                                            @elseif($project['status'] === 'on_hold') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300
                                            @elseif($project['status'] === 'completed') bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300
                                            @else bg-gray-200 text-gray-600 dark:bg-gray-600 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $project['status'])) }}
                                        </span>
                                        @if (!$project['is_active'])
                                            <span class="px-2 py-0.5 text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full">Inactive</span>
                                        @endif
                                    </div>
                                    @if ($project['description'])
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($project['description'], 80) }}</p>
                                    @endif
                                    @if ($project['start_date'] || $project['end_date'])
                                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                                            @if($project['start_date'])
                                                {{ \Carbon\Carbon::parse($project['start_date'])->format('M d, Y') }}
                                            @endif
                                            @if($project['start_date'] && $project['end_date']) - @endif
                                            @if($project['end_date'])
                                                {{ \Carbon\Carbon::parse($project['end_date'])->format('M d, Y') }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <button wire:click="toggleProjectStatus({{ $project['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="{{ $project['is_active'] ? 'Deactivate' : 'Activate' }}">
                                    <span class="material-symbols-outlined text-xl {{ $project['is_active'] ? 'text-green-500' : '' }}">
                                        {{ $project['is_active'] ? 'visibility' : 'visibility_off' }}
                                    </span>
                                </button>
                                <button wire:click="openProjectModal({{ $project['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </button>
                                <button wire:click="confirmDeleteProject({{ $project['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-500">folder</span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No projects yet</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Create projects to organize your tasks.</p>
                    <div class="mt-6">
                        <button wire:click="openProjectModal"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-purple-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-xl mr-2">add</span>
                            Add Project
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Assigned People Tab -->
    @if ($activeTab === 'assigned_people')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Assigned People</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage people who can be assigned to tasks.</p>
                </div>
                <button wire:click="openAssignedPersonModal"
                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-purple-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-xl mr-2">add</span>
                    Add Person
                </button>
            </div>

            @if (count($assignedPeople) > 0)
                <div class="space-y-3">
                    @foreach ($assignedPeople as $person)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary dark:hover:border-primary transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-10 h-10 bg-purple-500 rounded-full text-white font-bold">
                                    {{ strtoupper(substr($person['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $person['name'] }}</span>
                                        @if ($person['role'])
                                            <span class="px-2 py-0.5 text-xs bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-full">{{ $person['role'] }}</span>
                                        @endif
                                        @if (!$person['is_active'])
                                            <span class="px-2 py-0.5 text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full">Inactive</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        @if ($person['email'])
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-base">mail</span>
                                                {{ $person['email'] }}
                                            </span>
                                        @endif
                                        @if ($person['phone'])
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-base">phone</span>
                                                {{ $person['phone'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <button wire:click="toggleAssignedPersonStatus({{ $person['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="{{ $person['is_active'] ? 'Deactivate' : 'Activate' }}">
                                    <span class="material-symbols-outlined text-xl {{ $person['is_active'] ? 'text-green-500' : '' }}">
                                        {{ $person['is_active'] ? 'visibility' : 'visibility_off' }}
                                    </span>
                                </button>
                                <button wire:click="openAssignedPersonModal({{ $person['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </button>
                                <button wire:click="confirmDeleteAssignedPerson({{ $person['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-500">assignment_ind</span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No assigned people yet</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Add people who can be assigned to tasks.</p>
                    <div class="mt-6">
                        <button wire:click="openAssignedPersonModal"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-purple-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-xl mr-2">add</span>
                            Add Person
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- General Settings Tab -->
    @if ($activeTab === 'general')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">General Settings</h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Configure general task tracker preferences.</p>
            
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Default Task Types</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Available types for categorizing tasks.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($taskTypes as $type)
                                <span class="px-3 py-1 text-sm bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-full">{{ $type }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Task Priorities</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Priority levels for tasks.</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 text-sm bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300 rounded-full">Low</span>
                            <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300 rounded-full">Medium</span>
                            <span class="px-3 py-1 text-sm bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 rounded-full">High</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Task Statuses</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Available status options for tasks.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 text-sm bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 rounded-full">Active</span>
                            <span class="px-3 py-1 text-sm bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300 rounded-full">Completed</span>
                            <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300 rounded-full">On Hold</span>
                            <span class="px-3 py-1 text-sm bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300 rounded-full">Cancelled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Stage Modal -->
    @if ($showStageModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeStageModal"></div>

                <div class="relative inline-block bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                    <form wire:submit.prevent="saveStage">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="flex items-center justify-center w-10 h-10 bg-primary/10 rounded-xl">
                                    <span class="material-symbols-outlined text-primary">view_kanban</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ $editingStageId ? 'Edit Stage' : 'Add New Stage' }}
                                </h3>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Stage Name *</label>
                                    <input type="text" wire:model="stageName" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="e.g., In Progress">
                                    @error('stageName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Color</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" wire:model="stageColor" class="h-10 w-14 rounded-lg cursor-pointer border border-gray-300 dark:border-gray-600">
                                        <input type="text" wire:model="stageColor" class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="#3b82f6">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                    <textarea wire:model="stageDescription" rows="2" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="Optional description..."></textarea>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="stageIsActive" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    </label>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                            <button type="button" wire:click="closeStageModal" class="px-4 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-purple-600 font-medium transition-colors">
                                {{ $editingStageId ? 'Update Stage' : 'Create Stage' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Assigned Person Modal -->
    @if ($showAssignedPersonModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeAssignedPersonModal"></div>

                <div class="relative inline-block bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                    <form wire:submit.prevent="saveAssignedPerson">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="flex items-center justify-center w-10 h-10 bg-primary/10 rounded-xl">
                                    <span class="material-symbols-outlined text-primary">assignment_ind</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ $editingAssignedPersonId ? 'Edit Person' : 'Add New Person' }}
                                </h3>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                                    <input type="text" wire:model="assignedPersonName" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="Full name">
                                    @error('assignedPersonName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                    <input type="email" wire:model="assignedPersonEmail" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="email@example.com">
                                    @error('assignedPersonEmail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                                    <input type="text" wire:model="assignedPersonPhone" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="+1 234 567 890">
                                    @error('assignedPersonPhone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Role</label>
                                    <input type="text" wire:model="assignedPersonRole" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="e.g., Developer, Designer">
                                    @error('assignedPersonRole') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="assignedPersonIsActive" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    </label>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                            <button type="button" wire:click="closeAssignedPersonModal" class="px-4 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-purple-600 font-medium transition-colors">
                                {{ $editingAssignedPersonId ? 'Update Person' : 'Add Person' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Project Modal -->
    @if ($showProjectModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeProjectModal"></div>

                <div class="relative inline-block bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                    <form wire:submit.prevent="saveProject">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="flex items-center justify-center w-10 h-10 bg-primary/10 rounded-xl">
                                    <span class="material-symbols-outlined text-primary">folder</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ $editingProjectId ? 'Edit Project' : 'Add New Project' }}
                                </h3>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Project Name *</label>
                                    <input type="text" wire:model="projectName" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="e.g., Website Redesign">
                                    @error('projectName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                    <textarea wire:model="projectDescription" rows="2" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="Project description..."></textarea>
                                    @error('projectDescription') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Color</label>
                                        <div class="flex items-center gap-3">
                                            <input type="color" wire:model="projectColor" class="h-10 w-14 rounded-lg cursor-pointer border border-gray-300 dark:border-gray-600">
                                            <input type="text" wire:model="projectColor" class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="#8b5cf6">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                        <select wire:model="projectStatus" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            <option value="active">Active</option>
                                            <option value="on_hold">On Hold</option>
                                            <option value="completed">Completed</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                                        <input type="date" wire:model="projectStartDate" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        @error('projectStartDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                                        <input type="date" wire:model="projectEndDate" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        @error('projectEndDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="projectIsActive" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    </label>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                            <button type="button" wire:click="closeProjectModal" class="px-4 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-purple-600 font-medium transition-colors">
                                {{ $editingProjectId ? 'Update Project' : 'Create Project' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeDeleteModal"></div>

                <div class="relative inline-block bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-gray-200 dark:border-gray-700">
                    <div class="px-6 pt-6 pb-4">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex items-center justify-center w-10 h-10 bg-red-100 dark:bg-red-900/50 rounded-xl">
                                <span class="material-symbols-outlined text-red-600 dark:text-red-400">warning</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Confirm Delete</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">Are you sure you want to delete this {{ $deletingType === 'stage' ? 'stage' : ($deletingType === 'project' ? 'project' : 'person') }}? This action cannot be undone.</p>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                        <button wire:click="closeDeleteModal" class="px-4 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 font-medium transition-colors">
                            Cancel
                        </button>
                        <button wire:click="confirmDelete" class="px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 font-medium transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
