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

    <!-- Board Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Project Kanban Board</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Manage and track your project tasks efficiently</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Search -->
            <div class="relative w-full md:w-auto">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                <input wire:model.live.debounce.300ms="search" class="w-full md:w-56 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-primary focus:border-primary" placeholder="Search tasks..." type="text"/>
            </div>
            
            <!-- Priority Filter -->
            <div class="relative">
                <select wire:model.live="priority" class="appearance-none w-full md:w-auto bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg pl-4 pr-10 py-2 text-sm focus:ring-primary focus:border-primary">
                    <option value="">All Priorities</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none text-lg">expand_more</span>
            </div>

            <!-- Assigned Person Filter -->
            <div class="relative">
                <select wire:model.live="assignedToFilter" class="appearance-none w-full md:w-auto bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg pl-4 pr-10 py-2 text-sm focus:ring-primary focus:border-primary">
                    <option value="">All Assignees</option>
                    @foreach($assignedPeople as $person)
                        <option value="{{ $person->id }}">{{ $person->name }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none text-lg">expand_more</span>
            </div>

            <!-- Project Filter -->
            <div class="relative">
                <select wire:model.live="projectFilter" class="appearance-none w-full md:w-auto bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg pl-4 pr-10 py-2 text-sm focus:ring-primary focus:border-primary">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none text-lg">expand_more</span>
            </div>
            
            <!-- Clear Filters -->
            <button wire:click="clearFilters" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm font-medium flex items-center gap-1">
                <span class="material-symbols-outlined text-base">filter_alt_off</span>
                Clear
            </button>
            
            <!-- Add New Task -->
            <button wire:click="openCreateModal" class="bg-primary text-white px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-purple-600 transition-colors text-sm font-semibold whitespace-nowrap">
                <span class="material-symbols-outlined text-base">add</span>
                <span>Add Task</span>
            </button>
        </div>
    </div>

    <!-- Kanban Columns -->
    <div class="flex gap-4 w-full overflow-x-auto pb-4" style="height: calc(100vh - 250px);">
        @forelse($stages as $stage)
            <div class="flex flex-col gap-3 h-full kanban-column flex-shrink-0" 
                 style="min-width: 280px; width: calc((100% - 4 * 1rem) / 5);"
                 data-stage-id="{{ $stage['id'] }}"
                 ondrop="handleDrop(event, {{ $stage['id'] }})"
                 ondragover="handleDragOver(event)"
                 ondragenter="handleDragEnter(event)"
                 ondragleave="handleDragLeave(event)">
                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $stage['color'] ?? '#6366f1' }}"></div>
                        <h2 class="font-semibold text-gray-700 dark:text-gray-300 text-sm truncate">{{ $stage['name'] }}</h2>
                        <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold px-2 py-0.5 rounded-full flex-shrink-0">{{ count($tasks[$stage['id']] ?? []) }}</span>
                    </div>
                </div>
                
                @if(count($tasks[$stage['id']] ?? []) > 0)
                    <div class="flex-1 bg-gray-100 dark:bg-gray-800/50 p-3 rounded-lg overflow-y-auto space-y-3 drop-zone">
                        @foreach($tasks[$stage['id']] ?? [] as $task)
                            @php
                                $dueDate = $task['due_date'] ? \Carbon\Carbon::parse($task['due_date']) : null;
                                $isOverdue = $dueDate && $dueDate->isPast();
                                $isSoon = $dueDate && !$isOverdue && $dueDate->diffInDays(now()) <= 3;
                            @endphp
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 cursor-grab active:cursor-grabbing transition-all hover:shadow-lg task-card"
                                 draggable="true"
                                 ondragstart="handleDragStart(event, {{ $task['id'] }}, {{ $stage['id'] }})"
                                 id="task-{{ $task['id'] }}"
                                 wire:click="openEditModal({{ $task['id'] }})">
                                <!-- Priority Badge -->
                                <div class="mb-3 flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                                        @if($task['priority'] === 'high') bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-400
                                        @elseif($task['priority'] === 'medium') bg-yellow-100 text-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-400
                                        @else bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-400 @endif">
                                        <span class="w-1.5 h-1.5 rounded-full 
                                            @if($task['priority'] === 'high') bg-red-500
                                            @elseif($task['priority'] === 'medium') bg-yellow-500
                                            @else bg-green-500 @endif"></span>
                                        {{ ucfirst($task['priority']) }}
                                    </span>
                                    @if($task['type'])
                                        <span class="text-xs font-medium px-2 py-0.5 rounded bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-400">
                                            {{ $task['type'] }}
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Title -->
                                <h3 class="font-bold text-gray-900 dark:text-gray-100 text-sm mb-1.5 line-clamp-2">{{ $task['title'] }}</h3>
                                
                                <!-- Description -->
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">{{ $task['description'] ?: 'No description' }}</p>
                                
                                <!-- Date & Status -->
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex items-center gap-1 text-xs 
                                        @if($isOverdue) text-red-500 dark:text-red-400
                                        @elseif($isSoon) text-yellow-600 dark:text-yellow-400
                                        @else text-gray-500 dark:text-gray-400 @endif">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ $dueDate ? $dueDate->format('M d, Y') : 'No date' }}</span>
                                    </div>
                                    @if($isOverdue)
                                        <span class="text-xs font-medium text-red-500 dark:text-red-400">Overdue</span>
                                    @elseif($isSoon)
                                        <span class="text-xs font-medium text-yellow-600 dark:text-yellow-400">Soon</span>
                                    @endif
                                </div>
                                
                                <!-- Project & Assigned To -->
                                <div class="flex items-center justify-between gap-2">
                                    @if(isset($task['assigned_to']) && $task['assigned_to'])
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                                                {{ strtoupper(substr($task['assigned_to']['name'], 0, 1)) }}
                                            </div>
                                            <span class="text-xs text-purple-600 dark:text-purple-400 truncate">{{ $task['assigned_to']['name'] }}</span>
                                        </div>
                                    @else
                                        <span></span>
                                    @endif
                                    @if(isset($task['project']) && $task['project'])
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full truncate max-w-[100px]" style="background-color: {{ $task['project']['color'] ?? '#8b5cf6' }}20; color: {{ $task['project']['color'] ?? '#8b5cf6' }};">
                                            {{ $task['project']['name'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex-1 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center drop-zone">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No tasks yet</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Drag tasks here</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                <p class="text-lg">No stages configured</p>
                <p class="text-sm">Please set up your task stages first</p>
                <a href="{{ route('task.tracker.settings') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-purple-600 transition-colors">
                    <span class="material-symbols-outlined mr-2">settings</span>
                    Go to Settings
                </a>
            </div>
        @endforelse
    </div>

<!-- Create Task Modal -->
@if($showCreateModal)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeModals">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto" wire:click.stop>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Create New Task</h3>
            <button wire:click="closeModals" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form wire:submit.prevent="createTask">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                    <input type="text" wire:model="title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stage *</label>
                        <select wire:model="stageId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @foreach($stages as $stage)
                                <option value="{{ $stage['id'] }}">{{ $stage['name'] }}</option>
                            @endforeach
                        </select>
                        @error('stageId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date</label>
                        <input type="date" wire:model="dueDate" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        @error('dueDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority *</label>
                        <select wire:model="taskPriority" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                        @error('taskPriority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                        <select wire:model="taskType" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Select Type</option>
                            <option value="Bug">Bug</option>
                            <option value="Feature">Feature</option>
                            <option value="Enhancement">Enhancement</option>
                            <option value="Documentation">Documentation</option>
                            <option value="Research">Research</option>
                            <option value="Testing">Testing</option>
                        </select>
                        @error('taskType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned To</label>
                        <select wire:model="assignedToId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Not Assigned</option>
                            @foreach($assignedPeople as $person)
                                <option value="{{ $person->id }}">{{ $person->name }}{{ $person->role ? ' (' . $person->role . ')' : '' }}</option>
                            @endforeach
                        </select>
                        @error('assignedToId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Client (Optional)</label>
                        <select wire:model="clientId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">No Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('clientId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Project</label>
                    <select wire:model="projectId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">No Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('projectId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" wire:click="closeModals" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-purple-600">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Edit Task Modal -->
@if($showEditModal)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeModals">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto" wire:click.stop>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Edit Task</h3>
            <button wire:click="closeModals" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form wire:submit.prevent="updateTask">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                    <input type="text" wire:model="title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stage *</label>
                        <select wire:model="stageId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @foreach($stages as $stage)
                                <option value="{{ $stage['id'] }}">{{ $stage['name'] }}</option>
                            @endforeach
                        </select>
                        @error('stageId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date</label>
                        <input type="date" wire:model="dueDate" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        @error('dueDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority *</label>
                        <select wire:model="taskPriority" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                        @error('taskPriority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                        <select wire:model="taskStatus" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('taskStatus') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                        <select wire:model="taskType" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Select Type</option>
                            <option value="Bug">Bug</option>
                            <option value="Feature">Feature</option>
                            <option value="Enhancement">Enhancement</option>
                            <option value="Documentation">Documentation</option>
                            <option value="Research">Research</option>
                            <option value="Testing">Testing</option>
                        </select>
                        @error('taskType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned To</label>
                        <select wire:model="assignedToId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Not Assigned</option>
                            @foreach($assignedPeople as $person)
                                <option value="{{ $person->id }}">{{ $person->name }}{{ $person->role ? ' (' . $person->role . ')' : '' }}</option>
                            @endforeach
                        </select>
                        @error('assignedToId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Client (Optional)</label>
                        <select wire:model="clientId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">No Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('clientId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Project</label>
                        <select wire:model="projectId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">No Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('projectId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between mt-6">
                <button type="button" wire:click="deleteTask({{ $taskId }})" 
                        onclick="return confirm('Are you sure you want to delete this task?')"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete Task
                </button>
                <div class="flex gap-2">
                    <button type="button" wire:click="closeModals" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-purple-600">
                        Update Task
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif


@push('scripts')
<script>
    let draggedTaskId = null;
    let draggedFromStageId = null;
    let draggedElement = null;

    function handleDragStart(event, taskId, stageId) {
        console.log('Drag started:', taskId, 'from stage:', stageId);
        draggedTaskId = taskId;
        draggedFromStageId = stageId;
        draggedElement = event.target;
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', taskId);
        
        setTimeout(() => {
            if (draggedElement) {
                draggedElement.style.opacity = '0.4';
                draggedElement.classList.add('dragging');
            }
        }, 0);
    }

    function handleDragOver(event) {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
        return false;
    }

    function handleDragEnter(event) {
        event.preventDefault();
        const column = event.target.closest('.kanban-column');
        if (column) {
            column.classList.add('drag-over');
            const dropZone = column.querySelector('.drop-zone');
            if (dropZone) {
                dropZone.style.backgroundColor = 'rgba(139, 92, 246, 0.1)';
                dropZone.style.borderColor = 'rgba(139, 92, 246, 0.5)';
            }
        }
    }

    function handleDragLeave(event) {
        const column = event.target.closest('.kanban-column');
        if (column && !column.contains(event.relatedTarget)) {
            column.classList.remove('drag-over');
            const dropZone = column.querySelector('.drop-zone');
            if (dropZone) {
                dropZone.style.backgroundColor = '';
                dropZone.style.borderColor = '';
            }
        }
    }

    function handleDrop(event, toStageId) {
        event.preventDefault();
        event.stopPropagation();
        
        console.log('Drop on stage:', toStageId, 'from stage:', draggedFromStageId);
        
        // Remove visual feedback
        document.querySelectorAll('.kanban-column').forEach(col => {
            col.classList.remove('drag-over');
            const dropZone = col.querySelector('.drop-zone');
            if (dropZone) {
                dropZone.style.backgroundColor = '';
                dropZone.style.borderColor = '';
            }
        });
        
        if (draggedElement) {
            draggedElement.style.opacity = '1';
            draggedElement.classList.remove('dragging');
        }
        
        if (draggedTaskId && draggedFromStageId !== toStageId) {
            console.log('Moving task', draggedTaskId, 'to stage', toStageId);
            // Set the dragged data in Livewire component
            const livewire = window.Livewire.find('{{ $_instance->getId() }}');
            livewire.set('draggedTaskId', draggedTaskId);
            livewire.set('draggedFromStage', draggedFromStageId);
            // Call Livewire method
            livewire.call('handleDrop', toStageId);
        } else {
            console.log('No move needed - same stage or invalid data');
        }
        
        draggedTaskId = null;
        draggedFromStageId = null;
        draggedElement = null;
    }

    document.addEventListener('dragend', function(event) {
        console.log('Drag ended');
        
        // Clean up all visual feedback
        document.querySelectorAll('.kanban-column').forEach(col => {
            col.classList.remove('drag-over');
            const dropZone = col.querySelector('.drop-zone');
            if (dropZone) {
                dropZone.style.backgroundColor = '';
                dropZone.style.borderColor = '';
            }
        });
        
        document.querySelectorAll('.task-card').forEach(card => {
            card.style.opacity = '1';
            card.classList.remove('dragging');
        });
        
        draggedTaskId = null;
        draggedFromStageId = null;
        draggedElement = null;
    });

    // Add CSS for dragging state
    const style = document.createElement('style');
    style.textContent = `
        .task-card.dragging {
            opacity: 0.4 !important;
            transform: scale(0.95);
        }
        .kanban-column.drag-over {
            background-color: rgba(139, 92, 246, 0.05);
        }
        .drop-zone {
            transition: all 0.2s ease;
        }
        .task-card {
            user-select: none;
            -webkit-user-drag: element;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
                        box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.2s ease;
        }
        .task-card:hover {
            transform: translateY(-3px);
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
</div>
