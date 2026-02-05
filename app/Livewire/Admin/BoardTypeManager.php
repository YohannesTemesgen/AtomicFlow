<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\BoardType;
use App\Models\BoardField;
use App\Models\BoardStage;
use App\Services\TenantService;
use Illuminate\Support\Str;

use Livewire\Attributes\Computed;

class BoardTypeManager extends Component
{
    public $selectedBoardType = null;
    
    public $showModal = false;
    public $editMode = false;
    
    public $form = [
        'name' => '',
        'description' => '',
        'theme_color' => '#10B981',
        'icon' => 'view_kanban',
    ];

    protected $rules = [
        'form.name' => 'required|string|max:255',
        'form.description' => 'nullable|string',
        'form.theme_color' => 'required|string|max:7',
        'form.icon' => 'required|string|max:50',
    ];

    #[Computed]
    public function tenant()
    {
        return TenantService::getCurrentTenant();
    }

    #[Computed]
    public function boardTypes()
    {
        if (!$this->tenant) return [];

        return BoardType::where('tenant_id', $this->tenant->id)
            ->withCount(['fields', 'stages', 'boards'])
            ->get();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $boardType = BoardType::find($id);
        if ($boardType && $boardType->tenant_id === $this->tenant->id) {
            $this->selectedBoardType = $boardType;
            $this->form = [
                'name' => $boardType->name,
                'description' => $boardType->description,
                'theme_color' => $boardType->theme_color,
                'icon' => $boardType->icon,
            ];
            $this->editMode = true;
            $this->showModal = true;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode && $this->selectedBoardType) {
            $this->selectedBoardType->update([
                'name' => $this->form['name'],
                'slug' => Str::slug($this->form['name']),
                'description' => $this->form['description'],
                'theme_color' => $this->form['theme_color'],
                'icon' => $this->form['icon'],
            ]);
            session()->flash('message', 'Board type updated successfully.');
        } else {
            $boardType = BoardType::create([
                'tenant_id' => $this->tenant->id,
                'name' => $this->form['name'],
                'slug' => Str::slug($this->form['name']),
                'description' => $this->form['description'],
                'theme_color' => $this->form['theme_color'],
                'icon' => $this->form['icon'],
            ]);
            
            TenantService::createDefaultFields($boardType);
            TenantService::createDefaultStages($boardType);
            
            session()->flash('message', 'Board type created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $boardType = BoardType::find($id);
        if ($boardType && $boardType->tenant_id === $this->tenant->id) {
            $boardType->delete();
            session()->flash('message', 'Board type deleted successfully.');
        }
    }

    public function toggleActive($id)
    {
        $boardType = BoardType::find($id);
        if ($boardType && $boardType->tenant_id === $this->tenant->id) {
            $boardType->update(['is_active' => !$boardType->is_active]);
        }
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
            'description' => '',
            'theme_color' => '#10B981',
            'icon' => 'view_kanban',
        ];
        $this->selectedBoardType = null;
        $this->editMode = false;
    }

    public function render()
    {
        return view('livewire.admin.board-type-manager')
            ->layout('components.layouts.admin');
    }
}
