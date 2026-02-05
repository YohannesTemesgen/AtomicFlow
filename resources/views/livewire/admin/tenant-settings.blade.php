<div>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Workspace Settings</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Configure your workspace appearance and preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- General Settings -->
            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">General Settings</h3>
                
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Workspace Name</label>
                        <input type="text" wire:model="form.name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="My Workspace">
                        @error('form.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Theme Color</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" wire:model.live="form.theme_color" class="w-12 h-12 rounded-lg cursor-pointer border-0">
                            <input type="text" wire:model.live="form.theme_color" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div class="flex space-x-2 mt-3">
                            @foreach(['#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444', '#EC4899', '#14B8A6', '#6366F1'] as $color)
                                <button type="button" wire:click="$set('form.theme_color', '{{ $color }}')" class="w-8 h-8 rounded-lg border-2 transition {{ $form['theme_color'] === $color ? 'border-gray-800 dark:border-white scale-110' : 'border-transparent hover:scale-105' }}" style="background-color: {{ $color }};"></button>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview -->
            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Preview</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 rounded-lg" style="background-color: {{ $form['theme_color'] }}20;">
                            <span class="material-icons-outlined" style="color: {{ $form['theme_color'] }};">bolt</span>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ $form['name'] }}</span>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex items-center px-3 py-2 rounded-lg text-white text-sm" style="background-color: {{ $form['theme_color'] }};">
                            <span class="material-icons-outlined mr-2 text-sm">dashboard</span>
                            Active Menu Item
                        </div>
                        <div class="flex items-center px-3 py-2 rounded-lg text-gray-600 dark:text-gray-300 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="material-icons-outlined mr-2 text-sm">view_kanban</span>
                            Inactive Menu Item
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 text-white rounded-lg text-sm" style="background-color: {{ $form['theme_color'] }};">Primary Button</button>
                        <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm">Secondary</button>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full text-white" style="background-color: {{ $form['theme_color'] }};">Badge</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full" style="background-color: {{ $form['theme_color'] }}20; color: {{ $form['theme_color'] }};">Light Badge</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workspace Info -->
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Workspace Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Workspace ID</p>
                    <p class="font-mono text-sm text-gray-800 dark:text-white">{{ $tenant?->id ?? 'N/A' }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Slug</p>
                    <p class="font-mono text-sm text-gray-800 dark:text-white">{{ $tenant?->slug ?? 'N/A' }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Created</p>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $tenant?->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
