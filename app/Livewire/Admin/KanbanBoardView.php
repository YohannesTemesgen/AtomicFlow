<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Board;
use App\Models\BoardType;
use App\Models\BoardCard;
use App\Models\BoardStage;
use App\Models\BoardField;
use App\Services\TenantService;

use Livewire\Attributes\Computed;

class KanbanBoardView extends Component
{
    public $boardId;
    public $showCardModal = false;
    public $editMode = false;
    public $selectedCard = null;
    public $selectedStageId = null;
    
    public $search = '';
    public $filterPriority = '';
    
    public $cardForm = [];

    protected $listeners = ['cardMoved' => 'handleCardMoved'];

    public function mount($boardId)
    {
        $this->boardId = $boardId;
        $this->initializeCardForm();
    }

    #[Computed]
    public function tenant()
    {
        return TenantService::getCurrentTenant();
    }

    #[Computed]
    public function board()
    {
        return Board::where('id', $this->boardId)
            ->where('tenant_id', $this->tenant->id)
            ->with('boardType')
            ->firstOrFail();
    }

    #[Computed]
    public function boardType()
    {
        return $this->board->boardType;
    }

    #[Computed]
    public function stages()
    {
        return BoardStage::where('board_type_id', $this->boardType->id)
            ->where('is_active', true)
            ->orderBy('position')
            ->get();
    }

    #[Computed]
    public function fields()
    {
        return BoardField::where('board_type_id', $this->boardType->id)
            ->where('is_active', true)
            ->orderBy('position')
            ->get();
    }

    #[Computed]
    public function cards()
    {
        return BoardCard::where('board_id', $this->boardId)
            ->with('creator')
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterPriority, function($query) {
                $query->where('field_values->priority', $this->filterPriority);
            })
            ->orderBy('position')
            ->get()
            ->groupBy('stage_id');
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterPriority']);
    }

    public function initializeCardForm()
    {
        $this->cardForm = ['title' => ''];
        foreach ($this->fields as $field) {
            $this->cardForm[$field->name] = $field->default_value ?? '';
        }
    }

    public function openCreateModal($stageId)
    {
        $this->initializeCardForm();
        $this->selectedStageId = $stageId;
        $this->editMode = false;
        $this->showCardModal = true;
    }

    public function openEditModal($cardId)
    {
        $card = BoardCard::find($cardId);
        if ($card && $card->board_id === $this->board->id) {
            $this->selectedCard = $card;
            $this->selectedStageId = $card->stage_id;
            $this->cardForm = array_merge(
                ['title' => $card->title],
                $card->field_values ?? []
            );
            $this->editMode = true;
            $this->showCardModal = true;
        }
    }

    public function saveCard()
    {
        $rules = ['cardForm.title' => 'required|string|max:255'];
        foreach ($this->fields as $field) {
            if ($field->is_required) {
                $rules["cardForm.{$field->name}"] = 'required';
            }
        }
        $this->validate($rules);

        $fieldValues = [];
        foreach ($this->fields as $field) {
            $fieldValues[$field->name] = $this->cardForm[$field->name] ?? null;
        }

        if ($this->editMode && $this->selectedCard) {
            $this->selectedCard->update([
                'title' => $this->cardForm['title'],
                'stage_id' => $this->selectedStageId,
                'field_values' => $fieldValues,
            ]);
            session()->flash('message', 'Card updated successfully.');
        } else {
            $maxPosition = BoardCard::where('board_id', $this->boardId)
                ->where('stage_id', $this->selectedStageId)
                ->max('position') ?? 0;

            BoardCard::create([
                'board_id' => $this->boardId,
                'stage_id' => $this->selectedStageId,
                'title' => $this->cardForm['title'],
                'field_values' => $fieldValues,
                'position' => $maxPosition + 1,
                'created_by' => auth()->id(),
            ]);
            session()->flash('message', 'Card created successfully.');
        }

        $this->closeCardModal();
    }

    public function deleteCard($cardId)
    {
        $card = BoardCard::find($cardId);
        if ($card && $card->board_id === $this->boardId) {
            $card->delete();
            session()->flash('message', 'Card deleted successfully.');
        }
    }

    public function handleCardMoved($cardId, $newStageId, $newPosition)
    {
        $card = BoardCard::find($cardId);
        if ($card && $card->board_id === $this->boardId) {
            $card->update([
                'stage_id' => $newStageId,
                'position' => $newPosition,
            ]);
        }
    }

    public function closeCardModal()
    {
        $this->showCardModal = false;
        $this->initializeCardForm();
        $this->selectedCard = null;
        $this->selectedStageId = null;
        $this->editMode = false;
    }

    public function render()
    {
        return view('livewire.admin.kanban-board-view')
            ->layout('components.layouts.admin');
    }
}
