<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Board;
use App\Models\BoardType;
use App\Services\TenantService;

use Livewire\Attributes\Computed;

class BoardManager extends Component
{
    public $showModal = false;
    public $editMode = false;
    public $selectedBoard = null;
    
    public $form = [
        'name' => '',
        'description' => '',
        'board_type_id' => '',
    ];

    protected $rules = [
        'form.name' => 'required|string|max:255',
        'form.description' => 'nullable|string',
        'form.board_type_id' => 'required|exists:board_types,id',
    ];

    #[Computed]
    public function tenant()
    {
        return TenantService::getCurrentTenant();
    }

    #[Computed]
    public function boards()
    {
        if (!$this->tenant) return [];

        return Board::where('tenant_id', $this->tenant->id)
            ->with('boardType')
            ->withCount('cards')
            ->get();
    }

    #[Computed]
    public function boardTypes()
    {
        if (!$this->tenant) return [];

        return BoardType::where('tenant_id', $this->tenant->id)
            ->where('is_active', true)
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
        $board = Board::find($id);
        if ($board && $board->tenant_id === $this->tenant->id) {
            $this->selectedBoard = $board;
            $this->form = [
                'name' => $board->name,
                'description' => $board->description,
                'board_type_id' => $board->board_type_id,
            ];
            $this->editMode = true;
            $this->showModal = true;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode && $this->selectedBoard) {
            $this->selectedBoard->update([
                'name' => $this->form['name'],
                'description' => $this->form['description'],
                'board_type_id' => $this->form['board_type_id'],
            ]);
            session()->flash('message', 'Board updated successfully.');
        } else {
            Board::create([
                'tenant_id' => $this->tenant->id,
                'name' => $this->form['name'],
                'description' => $this->form['description'],
                'board_type_id' => $this->form['board_type_id'],
            ]);
            session()->flash('message', 'Board created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $board = Board::find($id);
        if ($board && $board->tenant_id === $this->tenant->id) {
            $board->delete();
            session()->flash('message', 'Board deleted successfully.');
        }
    }

    public function toggleActive($id)
    {
        $board = Board::find($id);
        if ($board && $board->tenant_id === $this->tenant->id) {
            $board->update(['is_active' => !$board->is_active]);
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
            'board_type_id' => $this->boardTypes->first()?->id ?? '',
        ];
        $this->selectedBoard = null;
        $this->editMode = false;
    }

    public function render()
    {
        return view('livewire.admin.board-manager')
            ->layout('components.layouts.admin');
    }
}
