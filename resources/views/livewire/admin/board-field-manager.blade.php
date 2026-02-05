<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center space-x-2 mb-1">
                    <a href="{{ route('admin.board-types') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary">
                        <span class="material-icons-outlined text-sm">arrow_back</span>
                    </a>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $boardType->name }} - Fields</h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Configure input fields for task creation. Drag to reorder.</p>
            </div>
            <button wire:click="openCreateModal" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition flex items-center">
                <span class="material-icons-outlined mr-2 text-sm">add</span>
                Add Field
            </button>
        </div>

        <!-- Fields List -->
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-12 gap-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                    <div class="col-span-1"></div>
                    <div class="col-span-3">Field Name</div>
                    <div class="col-span-2">Type</div>
                    <div class="col-span-2">Required</div>
                    <div class="col-span-2">Status</div>
                    <div class="col-span-2 text-right">Actions</div>
                </div>
            </div>
            
            <div wire:sortable="updateFieldOrder" wire:sortable.options="{ animation: 150 }" class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($fields as $field)
                    <div wire:sortable.item="{{ $field['id'] }}" wire:key="field-{{ $field['id'] }}" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-1">
                                <span wire:sortable.handle class="cursor-move text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <span class="material-icons-outlined">drag_indicator</span>
                                </span>
                            </div>
                            <div class="col-span-3">
                                <p class="font-medium text-gray-800 dark:text-white">{{ $field['label'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $field['name'] }}</p>
                            </div>
                            <div class="col-span-2">
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                    {{ $fieldTypes[$field['type']] ?? $field['type'] }}
                                </span>
                            </div>
                            <div class="col-span-2">
                                <button wire:click="toggleRequired({{ $field['id'] }})" class="px-2 py-1 text-xs font-medium rounded {{ $field['is_required'] ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $field['is_required'] ? 'Required' : 'Optional' }}
                                </button>
                            </div>
                            <div class="col-span-2">
                                <button wire:click="toggleActive({{ $field['id'] }})" class="px-2 py-1 text-xs font-medium rounded {{ $field['is_active'] ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $field['is_active'] ? 'Active' : 'Inactive' }}
                                </button>
                            </div>
                            <div class="col-span-2 flex justify-end space-x-2">
                                <button wire:click="openEditModal({{ $field['id'] }})" class="p-1 text-gray-500 hover:text-primary">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button wire:click="delete({{ $field['id'] }})" wire:confirm="Are you sure you want to delete this field?" class="p-1 text-gray-500 hover:text-red-500">
                                    <span class="material-icons-outlined text-sm">delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <span class="material-icons-outlined text-4xl text-gray-400 mb-2">input</span>
                        <p class="text-gray-500 dark:text-gray-400">No fields configured yet.</p>
                        <button wire:click="openCreateModal" class="text-primary hover:underline mt-2">Add your first field</button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $editMode ? 'Edit Field' : 'Add Field' }}
                        </h3>
                    </div>
                    
                    <form wire:submit="save">
                        <div class="px-6 py-4 space-y-4 max-h-96 overflow-y-auto">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label</label>
                                    <input type="text" wire:model.live="form.label" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="e.g., Due Date">
                                    @error('form.label') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Field Name</label>
                                    <input type="text" wire:model="form.name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="e.g., due_date">
                                    @error('form.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Field Type</label>
                                <select wire:model.live="form.type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    @foreach($fieldTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Placeholder</label>
                                <input type="text" wire:model="form.placeholder" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Enter placeholder text...">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Value</label>
                                <input type="text" wire:model="form.default_value" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Default value (optional)">
                            </div>
                            
                            @if(in_array($form['type'], ['select', 'multiselect', 'radio', 'checkbox']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Options</label>
                                    <div class="space-y-2 mb-3">
                                        @foreach($form['options'] as $index => $option)
                                            <div class="flex items-center space-x-2">
                                                <input type="text" value="{{ $option['value'] }}" readonly class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                                <input type="text" value="{{ $option['label'] }}" readonly class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                                <button type="button" wire:click="removeOption({{ $index }})" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                                                    <span class="material-icons-outlined text-sm">close</span>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="text" wire:model="newOption.value" placeholder="Value" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                        <input type="text" wire:model="newOption.label" placeholder="Label" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                        <button type="button" wire:click="addOption" class="p-2 bg-primary text-white rounded-lg hover:opacity-90">
                                            <span class="material-icons-outlined text-sm">add</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="form.is_required" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Required</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="form.is_active" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                </label>
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

@script
<script>
    Livewire.on('fieldsUpdated', () => {
        if (typeof Sortable !== 'undefined') {
            const el = document.querySelector('[wire\\:sortable]');
            if (el) {
                new Sortable(el, {
                    animation: 150,
                    handle: '[wire\\:sortable\\.handle]',
                    onEnd: function(evt) {
                        const items = [...el.querySelectorAll('[wire\\:sortable\\.item]')].map(item => item.getAttribute('wire:sortable.item'));
                        @this.updateFieldOrder(items);
                    }
                });
            }
        }
    });
</script>
@endscript
