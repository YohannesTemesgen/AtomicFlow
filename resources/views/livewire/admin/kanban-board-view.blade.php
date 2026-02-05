<div>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; }
    </style>

    <div class="space-y-6">
        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-7xl px-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                <div class="bg-green-100 border border-green-200 text-green-700 px-6 py-3 rounded-xl shadow-sm flex items-center gap-3 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
                    <span class="material-icons-outlined text-sm">check_circle</span>
                    <p class="text-sm font-medium">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        <!-- Header -->
        <header class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('admin.boards') }}" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors text-slate-500 dark:text-slate-400">
                        <span class="material-icons-outlined text-xl">arrow_back</span>
                    </a>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-800 dark:text-white">{{ $this->board->name }}</h1>
                </div>
                <p class="text-slate-500 dark:text-slate-400 ml-12">{{ $this->board->description ?? $this->boardType->name }}</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative min-w-[240px]">
                    <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                    <input wire:model.live.debounce.300ms="search" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-surface-dark border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" placeholder="Search tasks..." type="text"/>
                </div>
                
                <div class="flex items-center gap-2">
                    <select wire:model.live="filterPriority" class="bg-white dark:bg-surface-dark border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 px-3 pr-8 focus:ring-primary">
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <button wire:click="clearFilters" class="flex items-center gap-2 px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    <span class="material-icons-outlined text-sm">filter_list_off</span>
                    <span class="font-medium text-sm">Clear</span>
                </button>

                <button wire:click="openCreateModal({{ $this->stages->first()?->id }})" class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-all font-medium shadow-sm">
                    <span class="material-icons-outlined text-sm">add</span>
                    <span>Add Task</span>
                </button>
            </div>
        </header>

        <!-- Kanban Board -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ $this->stages->count() }} gap-6 items-start overflow-x-auto pb-6">
            @foreach($this->stages as $stage)
                <div class="flex flex-col gap-4 min-w-[300px]">
                    <!-- Stage Header -->
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $stage->color }};"></div>
                            <h3 class="font-semibold text-slate-700 dark:text-slate-200">{{ $stage->name }}</h3>
                            <span class="bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-xs px-2 py-0.5 rounded-full font-medium">
                                {{ isset($this->cards[$stage->id]) ? $this->cards[$stage->id]->count() : 0 }}
                            </span>
                        </div>
                        <button wire:click="openCreateModal({{ $stage->id }})" class="p-1 hover:bg-slate-100 dark:hover:bg-slate-800 rounded text-slate-400 hover:text-primary transition-colors">
                            <span class="material-icons-outlined text-sm">add</span>
                        </button>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex flex-col gap-4 max-h-[calc(100vh-300px)] overflow-y-auto custom-scrollbar pr-2 kanban-column" data-stage-id="{{ $stage->id }}">
                        @if(isset($this->cards[$stage->id]) && $this->cards[$stage->id]->count() > 0)
                            @foreach($this->cards[$stage->id] as $card)
                                <div class="bg-white dark:bg-surface-dark p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all cursor-grab active:cursor-grabbing group" 
                                     wire:click="openEditModal({{ $card->id }})"
                                     data-card-id="{{ $card->id }}">
                                    
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex flex-wrap gap-1.5">
                                            @php
                                                $priority = $card->field_values['priority'] ?? null;
                                                $priorityStyles = [
                                                    'high' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400',
                                                    'medium' => 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400',
                                                    'low' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-500 dark:text-blue-400',
                                                ];
                                            @endphp
                                            @if($priority)
                                                <span class="{{ $priorityStyles[$priority] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-500' }} text-[10px] font-bold uppercase px-2 py-1 rounded">
                                                    ● {{ ucfirst($priority) }}
                                                </span>
                                            @endif
                                            
                                            @if(isset($card->field_values['type']))
                                                <span class="bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 text-[10px] font-bold uppercase px-2 py-1 rounded">
                                                    {{ $card->field_values['type'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <button wire:click.stop="deleteCard({{ $card->id }})" wire:confirm="Delete this card?" class="opacity-0 group-hover:opacity-100 p-1 text-slate-400 hover:text-red-500 transition-all">
                                            <span class="material-icons-outlined text-sm">close</span>
                                        </button>
                                    </div>

                                    <h4 class="font-bold text-slate-800 dark:text-slate-100 leading-snug group-hover:text-primary transition-colors">
                                        {{ $card->title }}
                                    </h4>
                                    
                                    @php $description = $card->field_values['description'] ?? null; @endphp
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 mb-4 line-clamp-2">
                                        {{ $description ? strip_tags($description) : 'No description' }}
                                    </p>

                                    <div class="flex items-center gap-2 text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-4">
                                        <span class="material-icons-outlined text-sm">schedule</span>
                                        @if(isset($card->field_values['due_date']) && $card->field_values['due_date'])
                                            @php 
                                                $dueDate = \Carbon\Carbon::parse($card->field_values['due_date']);
                                                $isOverdue = $dueDate->isPast() && !$dueDate->isToday();
                                                $isSoon = $dueDate->isToday() || ($dueDate->isFuture() && $dueDate->diffInDays(now()) <= 3);
                                            @endphp
                                            {{ $dueDate->format('M d, Y') }}
                                            @if($isOverdue)
                                                <span class="text-red-500">Overdue</span>
                                            @elseif($isSoon)
                                                <span class="text-orange-500">Soon</span>
                                            @endif
                                        @else
                                            No due date
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-[10px] text-white font-bold shadow-sm">
                                                {{ strtoupper(substr($card->creator?->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="text-xs text-slate-600 dark:text-slate-400">{{ $card->creator?->name ?? 'Unknown' }}</span>
                                        </div>
                                        <span class="bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] px-2 py-0.5 rounded truncate max-w-[80px]">
                                            {{ $this->board->name }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="h-48 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center text-slate-400 text-center p-6">
                                <div class="w-12 h-12 bg-slate-50 dark:bg-slate-900 rounded-xl flex items-center justify-center mb-3">
                                    <span class="material-icons-outlined">inbox</span>
                                </div>
                                <p class="text-sm font-medium">No tasks yet</p>
                                <p class="text-[11px]">Drag tasks here</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:navigated', () => {
            initSortable();
        });

        function initSortable() {
            const columns = document.querySelectorAll('.kanban-column');
            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban',
                    animation: 150,
                    ghostClass: 'bg-slate-50',
                    dragClass: 'opacity-50',
                    onEnd: function (evt) {
                        const cardId = evt.item.getAttribute('data-card-id');
                        const newStageId = evt.to.getAttribute('data-stage-id');
                        const newPosition = evt.newIndex + 1;
                        
                        @this.dispatch('cardMoved', { 
                            cardId: cardId, 
                            newStageId: newStageId, 
                            newPosition: newPosition 
                        });
                    }
                });
            });
        }

        initSortable();
    </script>
    @endpush

    <!-- Card Modal -->
    @if($showCardModal)
        <div class="fixed inset-0 z-[60] overflow-y-auto" x-data="{ init() { this.initSummernote() }, initSummernote() { 
            setTimeout(() => {
                $('.summernote').summernote({
                    height: 150,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['codeview']]
                    ],
                    callbacks: {
                        onChange: function(contents) {
                            @this.set('cardForm.description', contents);
                        }
                    }
                });
            }, 100);
        }}" x-init="init()">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeCardModal"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-surface-dark rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200 dark:border-slate-700">
                    <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                            {{ $editMode ? 'Edit Card' : 'Create Card' }}
                        </h3>
                        <button wire:click="closeCardModal" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-400 transition-colors">
                            <span class="material-icons-outlined">close</span>
                        </button>
                    </div>
                    
                    <form wire:submit="saveCard">
                        <div class="px-8 py-6 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            <!-- Title Field -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="cardForm.title" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" placeholder="Enter card title">
                                @error('cardForm.title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Dynamic Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($this->fields as $field)
                                    @if($field->name !== 'title')
                                        <div class="{{ in_array($field->type, ['summernote', 'textarea', 'multiselect']) ? 'md:col-span-2' : '' }}">
                                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                                {{ $field->label }}
                                                @if($field->is_required) <span class="text-red-500">*</span> @endif
                                            </label>
                                            
                                            @switch($field->type)
                                                @case('text')
                                                @case('email')
                                                @case('phone')
                                                @case('url')
                                                    <input type="{{ $field->type === 'phone' ? 'tel' : $field->type }}" 
                                                        wire:model="cardForm.{{ $field->name }}" 
                                                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" 
                                                        placeholder="{{ $field->placeholder }}">
                                                    @break
                                                
                                                @case('textarea')
                                                    <textarea wire:model="cardForm.{{ $field->name }}" 
                                                        rows="3" 
                                                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" 
                                                        placeholder="{{ $field->placeholder }}"></textarea>
                                                    @break
                                                
                                                @case('summernote')
                                                    <div wire:ignore>
                                                        <textarea class="summernote" wire:model="cardForm.{{ $field->name }}">{!! $cardForm[$field->name] ?? '' !!}</textarea>
                                                    </div>
                                                    @break
                                                
                                                @case('number')
                                                @case('currency')
                                                    <input type="number" 
                                                        wire:model="cardForm.{{ $field->name }}" 
                                                        step="{{ $field->type === 'currency' ? '0.01' : '1' }}"
                                                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" 
                                                        placeholder="{{ $field->placeholder }}">
                                                    @break
                                                
                                                @case('date')
                                                    <input type="date" 
                                                        wire:model="cardForm.{{ $field->name }}" 
                                                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none">
                                                    @break
                                                
                                                @case('datetime')
                                                    <input type="datetime-local" 
                                                        wire:model="cardForm.{{ $field->name }}" 
                                                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none">
                                                    @break
                                                
                                                @case('select')
                                                    <select wire:model="cardForm.{{ $field->name }}" 
                                                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none">
                                                        <option value="">{{ $field->placeholder ?: 'Select...' }}</option>
                                                        @if($field->options)
                                                            @foreach($field->options as $option)
                                                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @break
                                                
                                                @case('multiselect')
                                                    <select wire:model="cardForm.{{ $field->name }}" 
                                                        multiple
                                                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none min-h-[120px]">
                                                        @if($field->options)
                                                            @foreach($field->options as $option)
                                                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @break
                                                
                                                @case('checkbox')
                                                    <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl">
                                                        @if($field->options)
                                                            @foreach($field->options as $option)
                                                                <label class="flex items-center gap-2 cursor-pointer group">
                                                                    <input type="checkbox" 
                                                                        wire:model="cardForm.{{ $field->name }}" 
                                                                        value="{{ $option['value'] }}"
                                                                        class="w-4 h-4 text-primary border-slate-300 dark:border-slate-700 rounded focus:ring-primary">
                                                                    <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $option['label'] }}</span>
                                                                </label>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    @break
                                                
                                                @case('radio')
                                                    <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl">
                                                        @if($field->options)
                                                            @foreach($field->options as $option)
                                                                <label class="flex items-center gap-2 cursor-pointer group">
                                                                    <input type="radio" 
                                                                        wire:model="cardForm.{{ $field->name }}" 
                                                                        value="{{ $option['value'] }}"
                                                                        class="w-4 h-4 text-primary border-slate-300 dark:border-slate-700 focus:ring-primary">
                                                                    <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $option['label'] }}</span>
                                                                </label>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    @break
                                                
                                                @case('color')
                                                    <input type="color" 
                                                        wire:model="cardForm.{{ $field->name }}" 
                                                        class="w-full h-12 p-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer">
                                                    @break
                                                
                                                @default
                                                    <input type="text" 
                                                        wire:model="cardForm.{{ $field->name }}" 
                                                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" 
                                                        placeholder="{{ $field->placeholder }}">
                                            @endswitch
                                            
                                            @error('cardForm.' . $field->name) <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                            <button type="button" wire:click="closeCardModal" class="px-6 py-2.5 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="px-8 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all active:scale-95">
                                {{ $editMode ? 'Update Task' : 'Create Task' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
