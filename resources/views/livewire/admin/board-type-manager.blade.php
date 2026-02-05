<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Board Types</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Configure different types of Kanban boards</p>
            </div>
            <button wire:click="openCreateModal" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition flex items-center">
                <span class="material-icons-outlined mr-2 text-sm">add</span>
                New Board Type
            </button>
        </div>

        <!-- Board Types Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->boardTypes as $type)
                <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 rounded-lg" style="background-color: {{ $type->theme_color }}20;">
                                    <span class="material-icons-outlined" style="color: {{ $type->theme_color }};">{{ $type->icon }}</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 dark:text-white">{{ $type->name }}</h3>
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $type->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ $type->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="material-icons-outlined text-gray-500">more_vert</span>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                    <button wire:click="openEditModal({{ $type->id }})" @click="open = false" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                        <span class="material-icons-outlined mr-2 text-sm">edit</span> Edit
                                    </button>
                                    <a href="{{ route('admin.board-type.fields', $type->id) }}" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                        <span class="material-icons-outlined mr-2 text-sm">input</span> Manage Fields
                                    </a>
                                    <a href="{{ route('admin.board-type.stages', $type->id) }}" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                        <span class="material-icons-outlined mr-2 text-sm">view_column</span> Manage Stages
                                    </a>
                                    <button wire:click="toggleActive({{ $type->id }})" @click="open = false" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                        <span class="material-icons-outlined mr-2 text-sm">{{ $type->is_active ? 'visibility_off' : 'visibility' }}</span>
                                        {{ $type->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <button wire:click="delete({{ $type->id }})" wire:confirm="Are you sure you want to delete this board type?" @click="open = false" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center">
                                        <span class="material-icons-outlined mr-2 text-sm">delete</span> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ $type->description ?? 'No description' }}</p>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center"><span class="material-icons-outlined mr-1 text-sm">input</span> {{ $type->fields_count }} fields</span>
                            <span class="flex items-center"><span class="material-icons-outlined mr-1 text-sm">view_column</span> {{ $type->stages_count }} stages</span>
                            <span class="flex items-center"><span class="material-icons-outlined mr-1 text-sm">view_kanban</span> {{ $type->boards_count }} boards</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 dark:border-gray-700 px-5 py-3 bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400 mr-2">Theme:</span>
                            <span class="w-4 h-4 rounded-full border border-gray-200 dark:border-gray-600" style="background-color: {{ $type->theme_color }};"></span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-surface-light dark:bg-surface-dark rounded-2xl border border-gray-100 dark:border-gray-700">
                    <span class="material-icons-outlined text-5xl text-gray-400 mb-3">category</span>
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">No Board Types Yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Create your first board type to get started</p>
                    <button wire:click="openCreateModal" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition">
                        Create Board Type
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $editMode ? 'Edit Board Type' : 'Create Board Type' }}
                        </h3>
                    </div>
                    
                    <form wire:submit="save">
                        <div class="px-6 py-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                <input type="text" wire:model="form.name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="e.g., Task Board">
                                @error('form.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                <textarea wire:model="form.description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Describe this board type..."></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Theme Color</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" wire:model="form.theme_color" class="w-10 h-10 rounded cursor-pointer border-0">
                                        <input type="text" wire:model="form.theme_color" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icon</label>
                                    <select wire:model="form.icon" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="view_kanban">Kanban</option>
                                        <option value="task">Task</option>
                                        <option value="assignment">Assignment</option>
                                        <option value="work">Work</option>
                                        <option value="folder">Folder</option>
                                        <option value="dashboard">Dashboard</option>
                                        <option value="list">List</option>
                                        <option value="check_circle">Check Circle</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition">
                                {{ $editMode ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
