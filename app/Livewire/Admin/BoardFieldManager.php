<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\BoardType;
use App\Models\BoardField;
use App\Services\TenantService;
use Illuminate\Support\Str;

class BoardFieldManager extends Component
{
    public $tenant;
    public $boardType;
    public $fields = [];
    
    public $showModal = false;
    public $editMode = false;
    public $selectedField = null;
    
    public $form = [
        'name' => '',
        'label' => '',
        'type' => 'text',
        'placeholder' => '',
        'default_value' => '',
        'is_required' => false,
        'is_active' => true,
        'options' => [],
    ];

    public $newOption = ['value' => '', 'label' => ''];

    protected $rules = [
        'form.name' => 'required|string|max:255',
        'form.label' => 'required|string|max:255',
        'form.type' => 'required|string',
        'form.placeholder' => 'nullable|string|max:255',
        'form.default_value' => 'nullable|string',
        'form.is_required' => 'boolean',
        'form.is_active' => 'boolean',
    ];

    public function mount($boardTypeId)
    {
        $this->tenant = TenantService::getCurrentTenant();
        $this->boardType = BoardType::where('id', $boardTypeId)
            ->where('tenant_id', $this->tenant->id)
            ->firstOrFail();
        $this->loadFields();
    }

    public function loadFields()
    {
        $this->fields = BoardField::where('board_type_id', $this->boardType->id)
            ->orderBy('position')
            ->get()
            ->toArray();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $field = BoardField::find($id);
        if ($field && $field->board_type_id === $this->boardType->id) {
            $this->selectedField = $field;
            $this->form = [
                'name' => $field->name,
                'label' => $field->label,
                'type' => $field->type,
                'placeholder' => $field->placeholder,
                'default_value' => $field->default_value,
                'is_required' => $field->is_required,
                'is_active' => $field->is_active,
                'options' => $field->options ?? [],
            ];
            $this->editMode = true;
            $this->showModal = true;
        }
    }

    public function addOption()
    {
        if (!empty($this->newOption['value']) && !empty($this->newOption['label'])) {
            $this->form['options'][] = $this->newOption;
            $this->newOption = ['value' => '', 'label' => ''];
        }
    }

    public function removeOption($index)
    {
        unset($this->form['options'][$index]);
        $this->form['options'] = array_values($this->form['options']);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => Str::slug($this->form['name'], '_'),
            'label' => $this->form['label'],
            'type' => $this->form['type'],
            'placeholder' => $this->form['placeholder'],
            'default_value' => $this->form['default_value'],
            'is_required' => $this->form['is_required'],
            'is_active' => $this->form['is_active'],
            'options' => in_array($this->form['type'], ['select', 'multiselect', 'radio', 'checkbox']) 
                ? $this->form['options'] 
                : null,
        ];

        if ($this->editMode && $this->selectedField) {
            $this->selectedField->update($data);
            session()->flash('message', 'Field updated successfully.');
        } else {
            $maxPosition = BoardField::where('board_type_id', $this->boardType->id)->max('position') ?? 0;
            $data['board_type_id'] = $this->boardType->id;
            $data['position'] = $maxPosition + 1;
            BoardField::create($data);
            session()->flash('message', 'Field created successfully.');
        }

        $this->closeModal();
        $this->loadFields();
    }

    public function delete($id)
    {
        $field = BoardField::find($id);
        if ($field && $field->board_type_id === $this->boardType->id) {
            $field->delete();
            session()->flash('message', 'Field deleted successfully.');
            $this->loadFields();
        }
    }

    public function toggleRequired($id)
    {
        $field = BoardField::find($id);
        if ($field && $field->board_type_id === $this->boardType->id) {
            $field->update(['is_required' => !$field->is_required]);
            $this->loadFields();
        }
    }

    public function toggleActive($id)
    {
        $field = BoardField::find($id);
        if ($field && $field->board_type_id === $this->boardType->id) {
            $field->update(['is_active' => !$field->is_active]);
            $this->loadFields();
        }
    }

    public function updateFieldOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            BoardField::where('id', $id)
                ->where('board_type_id', $this->boardType->id)
                ->update(['position' => $index + 1]);
        }
        $this->loadFields();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->form = [
            'name' => '',
            'label' => '',
            'type' => 'text',
            'placeholder' => '',
            'default_value' => '',
            'is_required' => false,
            'is_active' => true,
            'options' => [],
        ];
        $this->selectedField = null;
        $this->editMode = false;
        $this->newOption = ['value' => '', 'label' => ''];
    }

    public function getFieldTypes()
    {
        return BoardField::FIELD_TYPES;
    }

    public function render()
    {
        return view('livewire.admin.board-field-manager', [
            'fieldTypes' => $this->getFieldTypes(),
        ])->layout('components.layouts.admin');
    }
}
