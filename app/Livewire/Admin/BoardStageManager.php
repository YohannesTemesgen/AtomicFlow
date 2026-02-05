<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\BoardType;
use App\Models\BoardStage;
use App\Services\TenantService;
use Illuminate\Support\Str;

class BoardStageManager extends Component
{
    public $tenant;
    public $boardType;
    public $stages = [];
    
    public $showModal = false;
    public $editMode = false;
    public $selectedStage = null;
    
    public $form = [
        'name' => '',
        'color' => '#6B7280',
    ];

    protected $rules = [
        'form.name' => 'required|string|max:255',
        'form.color' => 'required|string|max:7',
    ];

    public function mount($boardTypeId)
    {
        $this->tenant = TenantService::getCurrentTenant();
        $this->boardType = BoardType::where('id', $boardTypeId)
            ->where('tenant_id', $this->tenant->id)
            ->firstOrFail();
        $this->loadStages();
    }

    public function loadStages()
    {
        $this->stages = BoardStage::where('board_type_id', $this->boardType->id)
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
        $stage = BoardStage::find($id);
        if ($stage && $stage->board_type_id === $this->boardType->id) {
            $this->selectedStage = $stage;
            $this->form = [
                'name' => $stage->name,
                'color' => $stage->color,
            ];
            $this->editMode = true;
            $this->showModal = true;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode && $this->selectedStage) {
            $this->selectedStage->update([
                'name' => $this->form['name'],
                'slug' => Str::slug($this->form['name']),
                'color' => $this->form['color'],
            ]);
            session()->flash('message', 'Stage updated successfully.');
        } else {
            $maxPosition = BoardStage::where('board_type_id', $this->boardType->id)->max('position') ?? 0;
            BoardStage::create([
                'board_type_id' => $this->boardType->id,
                'name' => $this->form['name'],
                'slug' => Str::slug($this->form['name']),
                'color' => $this->form['color'],
                'position' => $maxPosition + 1,
            ]);
            session()->flash('message', 'Stage created successfully.');
        }

        $this->closeModal();
        $this->loadStages();
    }

    public function delete($id)
    {
        $stage = BoardStage::find($id);
        if ($stage && $stage->board_type_id === $this->boardType->id) {
            $stage->delete();
            session()->flash('message', 'Stage deleted successfully.');
            $this->loadStages();
        }
    }

    public function updateStageOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            BoardStage::where('id', $id)
                ->where('board_type_id', $this->boardType->id)
                ->update(['position' => $index + 1]);
        }
        $this->loadStages();
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
            'color' => '#6B7280',
        ];
        $this->selectedStage = null;
        $this->editMode = false;
    }

    public function render()
    {
        return view('livewire.admin.board-stage-manager')
            ->layout('components.layouts.admin');
    }
}
