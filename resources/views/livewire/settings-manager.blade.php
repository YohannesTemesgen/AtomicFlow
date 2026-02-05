<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Settings</h1>
        <p class="mt-1 text-gray-600 dark:text-gray-400">Manage stages, clients, categories, and application preferences.</p>
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
                    Pipeline Stages
                </button>
                <button wire:click="setActiveTab('clients')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm transition-all {{ $activeTab === 'clients' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="material-symbols-outlined text-xl">people</span>
                    Clients
                </button>
                <button wire:click="setActiveTab('categories')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm transition-all {{ $activeTab === 'categories' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="material-symbols-outlined text-xl">category</span>
                    Categories
                </button>
                <button wire:click="setActiveTab('assigned_people')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm transition-all {{ $activeTab === 'assigned_people' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="material-symbols-outlined text-xl">assignment_ind</span>
                    Assigned To
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
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pipeline Stages</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage the stages in your lead conversion pipeline.</p>
                </div>
                <button wire:click="openStageModal"
                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
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
                                <div class="w-4 h-4 rounded-full shadow-sm" style="background-color: {{ $stage['color'] ?? '#3b82f6' }}"></div>
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
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Create stages to build your pipeline.</p>
                    <div class="mt-6">
                        <button wire:click="openStageModal"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-xl mr-2">add</span>
                            Add Stage
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Clients Tab -->
    @if ($activeTab === 'clients')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Clients</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage your clients for lead assignments.</p>
                </div>
                <button wire:click="openClientModal"
                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-xl mr-2">add</span>
                    Add Client
                </button>
            </div>

            @if (count($clients) > 0)
                <div class="space-y-3">
                    @foreach ($clients as $client)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary dark:hover:border-primary transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-10 h-10 bg-primary rounded-full text-white font-bold">
                                    {{ strtoupper(substr($client['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $client['name'] }}</span>
                                        @if ($client['project_category'])
                                            <span class="px-2 py-0.5 text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-full">{{ $client['project_category'] }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        @if ($client['email'])
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-base">mail</span>
                                                {{ $client['email'] }}
                                            </span>
                                        @endif
                                        @if ($client['phone'])
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-base">phone</span>
                                                {{ $client['phone'] }}
                                            </span>
                                        @endif
                                        @if ($client['location'])
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-base">location_on</span>
                                                {{ $client['location'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <button wire:click="openClientModal({{ $client['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </button>
                                <button wire:click="confirmDeleteClient({{ $client['id'] }})"
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
                    <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-500">people</span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No clients yet</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Add clients to assign them to leads.</p>
                    <div class="mt-6">
                        <button wire:click="openClientModal"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-xl mr-2">add</span>
                            Add Client
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Categories Tab -->
    @if ($activeTab === 'categories')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Client Categories</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage categories for organizing your clients and projects.</p>
                </div>
                <button wire:click="openCategoryModal"
                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-xl mr-2">add</span>
                    Add Category
                </button>
            </div>

            <!-- Categories List -->
            @if (count($categories) > 0)
                <div class="space-y-3">
                    @foreach ($categories as $category)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary dark:hover:border-primary transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-5 h-5 rounded-full shadow-sm" style="background-color: {{ $category['color'] }}"></div>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $category['name'] }}</span>
                                        @if (!$category['is_active'])
                                            <span class="px-2 py-0.5 text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full">Inactive</span>
                                        @endif
                                    </div>
                                    @if ($category['description'])
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $category['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <button wire:click="toggleCategoryStatus({{ $category['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="{{ $category['is_active'] ? 'Deactivate' : 'Activate' }}">
                                    <span class="material-symbols-outlined text-xl {{ $category['is_active'] ? 'text-green-500' : '' }}">
                                        {{ $category['is_active'] ? 'visibility' : 'visibility_off' }}
                                    </span>
                                </button>
                                <button wire:click="openCategoryModal({{ $category['id'] }})"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </button>
                                <button wire:click="confirmDeleteCategory({{ $category['id'] }})"
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
                    <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-500">category</span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No categories yet</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Get started by creating a new category.</p>
                    <div class="mt-6">
                        <button wire:click="openCategoryModal"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-xl mr-2">add</span>
                            Add Category
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
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage people who can be assigned to leads.</p>
                </div>
                <button wire:click="openAssignedPersonModal"
                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-xl mr-2">add</span>
                    Add Person
                </button>
            </div>

            @if (count($assignedPeople) > 0)
                <div class="space-y-3">
                    @foreach ($assignedPeople as $person)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary dark:hover:border-primary transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-10 h-10 bg-indigo-500 rounded-full text-white font-bold">
                                    {{ strtoupper(substr($person['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $person['name'] }}</span>
                                        @if ($person['role'])
                                            <span class="px-2 py-0.5 text-xs bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-full">{{ $person['role'] }}</span>
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
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Add people who can be assigned to leads.</p>
                    <div class="mt-6">
                        <button wire:click="openAssignedPersonModal"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
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
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">General application settings will be available here.</p>
            
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Default Currency</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Set the default currency for deals and values.</p>
                        </div>
                        <select class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg px-4 py-2 text-sm focus:ring-primary focus:border-primary">
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                            <option value="GBP">GBP (£)</option>
                            <option value="ETB">ETB (Br)</option>
                        </select>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Date Format</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Choose how dates are displayed.</p>
                        </div>
                        <select class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg px-4 py-2 text-sm focus:ring-primary focus:border-primary">
                            <option value="Y-m-d">2025-11-27</option>
                            <option value="d/m/Y">27/11/2025</option>
                            <option value="m/d/Y">11/27/2025</option>
                            <option value="d M Y">27 Nov 2025</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Notifications Tab -->
    @if ($activeTab === 'notifications')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Notification Preferences</h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Configure how and when you receive notifications.</p>
            
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Email Notifications</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Receive email alerts for important updates.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Lead Due Reminders</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Get notified when leads are approaching their due date.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Stage Change Alerts</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Notify when a lead moves to a different stage.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Category Modal -->
    @if ($showCategoryModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeCategoryModal"></div>

                <div class="relative inline-block bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                    <form wire:submit.prevent="saveCategory">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="flex items-center justify-center w-10 h-10 bg-primary/10 rounded-xl">
                                    <span class="material-symbols-outlined text-primary">category</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ $editingCategoryId ? 'Edit Category' : 'Add New Category' }}
                                </h3>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Category Name *</label>
                                    <input type="text" wire:model="categoryName"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                        placeholder="e.g., Web Development">
                                    @error('categoryName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Color</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" wire:model="categoryColor"
                                            class="w-14 h-12 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer p-1">
                                        <input type="text" wire:model="categoryColor"
                                            class="flex-1 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                            placeholder="#3b82f6">
                                    </div>
                                    @error('categoryColor') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                    <textarea wire:model="categoryDescription" rows="3"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none"
                                        placeholder="Optional description for this category"></textarea>
                                    @error('categoryDescription') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <input type="checkbox" wire:model="categoryIsActive" id="categoryIsActive"
                                        class="w-5 h-5 text-primary bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-primary">
                                    <label for="categoryIsActive" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Active category</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end space-x-3 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" wire:click="closeCategoryModal"
                                class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-white text-sm font-semibold rounded-xl transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                                {{ $editingCategoryId ? 'Update Category' : 'Create Category' }}
                            </button>
                        </div>
                    </form>
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
                                    <input type="text" wire:model="stageName"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                        placeholder="e.g., New Lead, Qualified, Proposal">
                                    @error('stageName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Color</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" wire:model="stageColor"
                                            class="w-14 h-12 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer p-1">
                                        <input type="text" wire:model="stageColor"
                                            class="flex-1 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                            placeholder="#3b82f6">
                                    </div>
                                    @error('stageColor') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                    <textarea wire:model="stageDescription" rows="3"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none"
                                        placeholder="Optional description for this stage"></textarea>
                                    @error('stageDescription') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <input type="checkbox" wire:model="stageIsActive" id="stageIsActive"
                                        class="w-5 h-5 text-primary bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-primary">
                                    <label for="stageIsActive" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Active stage</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end space-x-3 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" wire:click="closeStageModal"
                                class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-white text-sm font-semibold rounded-xl transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                                {{ $editingStageId ? 'Update Stage' : 'Create Stage' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Client Modal -->
    @if ($showClientModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeClientModal"></div>

                <div class="relative inline-block bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                    <form wire:submit.prevent="saveClient">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="flex items-center justify-center w-10 h-10 bg-primary/10 rounded-xl">
                                    <span class="material-symbols-outlined text-primary">person_add</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ $editingClientId ? 'Edit Client' : 'Add New Client' }}
                                </h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Client Name *</label>
                                    <input type="text" wire:model="clientName"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                        placeholder="e.g., John Doe">
                                    @error('clientName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                        <input type="email" wire:model="clientEmail"
                                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                            placeholder="john@example.com">
                                        @error('clientEmail') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                                        <input type="text" wire:model="clientPhone"
                                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                            placeholder="+1 234 567 890">
                                        @error('clientPhone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Location</label>
                                        <input type="text" wire:model="clientLocation"
                                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                            placeholder="e.g., New York, USA">
                                        @error('clientLocation') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Category</label>
                                        <select wire:model="clientCategory"
                                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category['name'] }}">{{ $category['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('clientCategory') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                                    <textarea wire:model="clientNotes" rows="3"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none"
                                        placeholder="Additional notes about this client"></textarea>
                                    @error('clientNotes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end space-x-3 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" wire:click="closeClientModal"
                                class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-white text-sm font-semibold rounded-xl transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                                {{ $editingClientId ? 'Update Client' : 'Create Client' }}
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
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                                    <input type="text" wire:model="assignedPersonName"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                        placeholder="e.g., John Smith">
                                    @error('assignedPersonName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                        <input type="email" wire:model="assignedPersonEmail"
                                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                            placeholder="john@example.com">
                                        @error('assignedPersonEmail') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                                        <input type="text" wire:model="assignedPersonPhone"
                                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                            placeholder="+1 234 567 890">
                                        @error('assignedPersonPhone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Role</label>
                                    <input type="text" wire:model="assignedPersonRole"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                        placeholder="e.g., Sales Manager, Account Executive">
                                    @error('assignedPersonRole') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <input type="checkbox" wire:model="assignedPersonIsActive" id="assignedPersonIsActive"
                                        class="w-5 h-5 text-primary bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-primary">
                                    <label for="assignedPersonIsActive" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Active person</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end space-x-3 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" wire:click="closeAssignedPersonModal"
                                class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-white text-sm font-semibold rounded-xl transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-primary hover:bg-blue-600 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                                {{ $editingAssignedPersonId ? 'Update Person' : 'Create Person' }}
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
                    <div class="px-6 pt-8 pb-6">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full mb-5">
                            <span class="material-symbols-outlined text-3xl text-red-500">warning</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">
                            Delete {{ ucfirst($deletingType ?? 'Item') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Are you sure you want to delete this {{ $deletingType ?? 'item' }}? This action cannot be undone.</p>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-center space-x-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeDeleteModal"
                            class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-white text-sm font-semibold rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button type="button" wire:click="confirmDelete"
                            class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                            Delete {{ ucfirst($deletingType ?? 'Item') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
