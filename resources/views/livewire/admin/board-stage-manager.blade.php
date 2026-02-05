<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center space-x-2 mb-1">
                    <a href="{{ route('admin.board-types') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary">
                        <span class="material-icons-outlined text-sm">arrow_back</span>
                    </a>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $boardType->name }} - Stages</h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Configure workflow stages for this board type. Drag to reorder.</p>
            </div>
            <button wire:click="openCreateModal" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition flex items-center">
                <span class="material-icons-outlined mr-2 text-sm">add</span>
                Add Stage
            </button>
        </div>

        <!-- Stages List -->
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div wire:sortable="updateStageOrder" wire:sortable.options="{ animation: 150 }" class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($stages as $stage)
                    <div wire:sortable.item="{{ $stage['id'] }}" wire:key="stage-{{ $stage['id'] }}" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span wire:sortable.handle class="cursor-move text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <span class="material-icons-outlined">drag_indicator</span>
                                </span>
                                <div class="w-4 h-4 rounded-full" style="background-color: {{ $stage['color'] }};"></div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $stage['name'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $stage['slug'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $stage['is_active'] ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $stage['is_active'] ? 'Active' : 'Inactive' }}
                                </span>
                                <button wire:click="openEditModal({{ $stage['id'] }})" class="p-1 text-gray-500 hover:text-primary">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button wire:click="delete({{ $stage['id'] }})" wire:confirm="Are you sure you want to delete this stage?" class="p-1 text-gray-500 hover:text-red-500">
                                    <span class="material-icons-outlined text-sm">delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <span class="material-icons-outlined text-4xl text-gray-400 mb-2">view_column</span>
                        <p class="text-gray-500 dark:text-gray-400">No stages configured yet.</p>
                        <button wire:click="openCreateModal" class="text-primary hover:underline mt-2">Add your first stage</button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Preview -->
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Stage Preview</h3>
            <div class="flex space-x-4 overflow-x-auto pb-4">
                @foreach($stages as $stage)
                    <div class="flex-shrink-0 w-64 bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <div class="flex items-center space-x-2 mb-3">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $stage['color'] }};"></div>
                            <span class="font-medium text-gray-700 dark:text-gray-300 text-sm">{{ $stage['name'] }}</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded p-3 border border-gray-200 dark:border-gray-600 text-sm text-gray-500 dark:text-gray-400">
                            Cards will appear here
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $editMode ? 'Edit Stage' : 'Add Stage' }}
                        </h3>
                    </div>
                    
                    <form wire:submit="save">
                        <div class="px-6 py-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stage Name</label>
                                <input type="text" wire:model="form.name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="e.g., In Progress">
                                @error('form.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" wire:model="form.color" class="w-10 h-10 rounded cursor-pointer border-0">
                                    <input type="text" wire:model="form.color" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                </div>
                                <div class="flex space-x-2 mt-2">
                                    @foreach(['#6B7280', '#3B82F6', '#F59E0B', '#8B5CF6', '#10B981', '#EF4444', '#EC4899'] as $color)
                                        <button type="button" wire:click="$set('form.color', '{{ $color }}')" class="w-6 h-6 rounded-full border-2 {{ $form['color'] === $color ? 'border-gray-800 dark:border-white' : 'border-transparent' }}" style="background-color: {{ $color }};"></button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition">
                                {{ $editMode ? 'Update' : 'Add' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
