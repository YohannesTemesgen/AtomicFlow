<?php

namespace App\Livewire;

use App\Models\Lead;
use App\Models\Stage;
use App\Models\AssignedPerson;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KanbanBoard extends Component
{
    public $stages = [];
    public $leads = [];
    public $search = '';
    public $priority = '';
    public $assignedToFilter = '';
    public $showDueSoon = false;
    public $showOverdue = false;
    public $draggedLeadId = null;
    public $draggedFromStage = null;
    public $statistics = [];
    
    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showViewModal = false;
    
    // Form fields
    public $leadId = null;
    public $title = '';
    public $description = '';
    public $value = 0;
    public $leadPriority = 'medium';
    public $leadStatus = 'active';
    public $expectedCloseDate = '';
    public $clientId = null;
    public $stageId = null;
    public $assignedToId = null;
    public $clients = [];
    public $assignedPeople = [];

    protected $listeners = ['refreshBoard' => '$refresh'];
    
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'value' => 'required|numeric|min:0',
        'leadPriority' => 'required|in:low,medium,high',
        'leadStatus' => 'required|in:active,won,lost,on_hold',
        'expectedCloseDate' => 'nullable|date',
        'clientId' => 'required|exists:clients,id',
        'stageId' => 'required|exists:stages,id',
        'assignedToId' => 'nullable|exists:assigned_people,id',
    ];

    public function mount()
    {
        $this->loadBoardData();
        $this->loadStatistics();
        $this->loadClients();
        $this->loadAssignedPeople();
    }

    public function loadBoardData()
    {
        try {
            // Get all active stages
            $this->stages = Stage::active()->ordered()->get()->toArray();

            // Build query for leads - load all leads for demo purposes
            $query = Lead::with(['client', 'stage', 'user', 'assignedTo'])
                ->where('status', 'active');

            // Apply search filter
            if (!empty($this->search)) {
                $query->search($this->search);
            }

            // Apply priority filter
            if (!empty($this->priority)) {
                $query->byPriority($this->priority);
            }

            // Apply assigned to filter
            if (!empty($this->assignedToFilter)) {
                $query->where('assigned_to_id', $this->assignedToFilter);
            }

            // Apply due date filters
            if ($this->showDueSoon) {
                $query->dueSoon();
            }

            if ($this->showOverdue) {
                $query->overdue();
            }

            $this->leads = $query->get()->groupBy('stage_id')->toArray();
        } catch (\Exception $e) {
            Log::error('Error loading Kanban board data: ' . $e->getMessage());
            session()->flash('error', 'Error loading board data');
        }
    }

    public function loadStatistics()
    {
        try {
            // Get stage statistics
            $stageStats = Stage::active()
                ->ordered()
                ->withCount(['leads' => function ($query) {
                    $query->where('status', 'active');
                }])
                ->get();

            // Get overall lead statistics
            $totalLeads = Lead::count();
            $activeLeads = Lead::where('status', 'active')->count();
            $wonLeads = Lead::where('status', 'won')->count();
            $lostLeads = Lead::where('status', 'lost')->count();

            // Get value statistics
            $totalValue = Lead::sum('value') ?? 0;
            $wonValue = Lead::where('status', 'won')->sum('value') ?? 0;

            // Get due date statistics
            $overdueLeads = Lead::where('status', 'active')
                ->where('expected_close_date', '<', now())
                ->count();

            $dueSoonLeads = Lead::where('status', 'active')
                ->where('expected_close_date', '<=', now()->addDays(7))
                ->where('expected_close_date', '>=', now())
                ->count();

            $this->statistics = [
                'stage_stats' => $stageStats,
                'overview' => [
                    'total_leads' => $totalLeads,
                    'active_leads' => $activeLeads,
                    'won_leads' => $wonLeads,
                    'lost_leads' => $lostLeads,
                    'conversion_rate' => $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 2) : 0,
                ],
                'value_stats' => [
                    'total_value' => $totalValue,
                    'won_value' => $wonValue,
                    'avg_deal_size' => $wonLeads > 0 ? round($wonValue / $wonLeads, 2) : 0,
                ],
                'due_date_stats' => [
                    'overdue_leads' => $overdueLeads,
                    'due_soon_leads' => $dueSoonLeads,
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Error loading statistics: ' . $e->getMessage());
        }
    }

    public function startDrag($leadId, $stageId)
    {
        $this->draggedLeadId = $leadId;
        $this->draggedFromStage = $stageId;
    }

    public function handleDrop($toStageId)
    {
        try {
            if (empty($this->draggedLeadId) || empty($this->draggedFromStage)) {
                return;
            }

            // Find the lead
            $lead = Lead::find($this->draggedLeadId);
            if (!$lead) {
                session()->flash('error', 'Lead not found');
                return;
            }

            // Check if the stage actually changed
            if ($this->draggedFromStage == $toStageId) {
                $this->resetDragData();
                return;
            }

            // Update lead stage
            $lead->updateStage($toStageId, $lead->user_id ?? 1, 'Moved via Kanban board');

            // Reload board data
            $this->loadBoardData();
            $this->loadStatistics();

            session()->flash('success', 'Lead moved successfully');
        } catch (\Exception $e) {
            Log::error('Error handling drag-drop: ' . $e->getMessage());
            session()->flash('error', 'Error moving lead');
        } finally {
            $this->resetDragData();
        }
    }

    public function resetDragData()
    {
        $this->draggedLeadId = null;
        $this->draggedFromStage = null;
    }

    public function updatedSearch()
    {
        $this->loadBoardData();
    }

    public function updatedPriority()
    {
        $this->loadBoardData();
    }

    public function updatedShowDueSoon()
    {
        $this->loadBoardData();
    }

    public function updatedShowOverdue()
    {
        $this->loadBoardData();
    }

    public function updatedAssignedToFilter()
    {
        $this->loadBoardData();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'priority', 'assignedToFilter', 'showDueSoon', 'showOverdue']);
        $this->loadBoardData();
    }

    public function getLeadsForStage($stageId)
    {
        return $this->leads[$stageId] ?? [];
    }

    public function getPriorityColor($priority)
    {
        return match ($priority) {
            'high' => 'text-red-600 bg-red-100',
            'medium' => 'text-yellow-600 bg-yellow-100',
            'low' => 'text-green-600 bg-green-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function isOverdue($expectedCloseDate)
    {
        if (!$expectedCloseDate) return false;
        return now()->greaterThan($expectedCloseDate);
    }

    public function isDueSoon($expectedCloseDate)
    {
        if (!$expectedCloseDate) return false;
        $dueDate = \Carbon\Carbon::parse($expectedCloseDate);
        return $dueDate->diffInDays(now()) <= 7 && $dueDate->isFuture();
    }
    
    public function loadClients()
    {
        $this->clients = \App\Models\Client::orderBy('name')->get();
    }
    
    public function loadAssignedPeople()
    {
        $this->assignedPeople = AssignedPerson::active()->ordered()->get();
    }
    
    public function openCreateModal($stageId = null)
    {
        $this->resetForm();
        $this->stageId = $stageId ?? ($this->stages[0]['id'] ?? null);
        $this->showCreateModal = true;
    }
    
    public function openEditModal($leadId)
    {
        try {
            $lead = Lead::with('client')->findOrFail($leadId);
            
            $this->leadId = $lead->id;
            $this->title = $lead->title;
            $this->description = $lead->description;
            $this->value = $lead->value;
            $this->leadPriority = $lead->priority;
            $this->leadStatus = $lead->status;
            $this->expectedCloseDate = $lead->expected_close_date ? $lead->expected_close_date->format('Y-m-d') : '';
            $this->clientId = $lead->client_id;
            $this->stageId = $lead->stage_id;
            $this->assignedToId = $lead->assigned_to_id;
            
            $this->showEditModal = true;
        } catch (\Exception $e) {
            Log::error('Error opening edit modal: ' . $e->getMessage());
            session()->flash('error', 'Error loading lead details');
        }
    }
    
    public function openViewModal($leadId)
    {
        $this->leadId = $leadId;
        $this->showViewModal = true;
    }
    
    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showViewModal = false;
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->leadId = null;
        $this->title = '';
        $this->description = '';
        $this->value = 0;
        $this->leadPriority = 'medium';
        $this->leadStatus = 'active';
        $this->expectedCloseDate = '';
        $this->clientId = null;
        $this->stageId = null;
        $this->assignedToId = null;
        $this->resetErrorBag();
    }
    
    public function createLead()
    {
        $this->validate();
        
        try {
            Lead::create([
                'title' => $this->title,
                'description' => $this->description,
                'value' => $this->value,
                'priority' => $this->leadPriority,
                'status' => $this->leadStatus,
                'expected_close_date' => $this->expectedCloseDate ?: null,
                'client_id' => $this->clientId,
                'stage_id' => $this->stageId,
                'user_id' => Auth::id() ?: 1, // Fallback to user ID 1 if not authenticated
                'assigned_to_id' => $this->assignedToId ?: null,
            ]);
            
            $this->loadBoardData();
            $this->loadStatistics();
            $this->closeModals();
            
            session()->flash('success', 'Lead created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating lead: ' . $e->getMessage());
            session()->flash('error', 'Error creating lead: ' . $e->getMessage());
        }
    }
    
    public function updateLead()
    {
        $this->validate();
        
        try {
            $lead = Lead::findOrFail($this->leadId);
            $oldStageId = $lead->stage_id;
            
            $lead->update([
                'title' => $this->title,
                'description' => $this->description,
                'value' => $this->value,
                'priority' => $this->leadPriority,
                'status' => $this->leadStatus,
                'expected_close_date' => $this->expectedCloseDate ?: null,
                'client_id' => $this->clientId,
                'stage_id' => $this->stageId,
                'assigned_to_id' => $this->assignedToId ?: null,
            ]);
            
            // If stage changed, record in history
            if ($oldStageId != $this->stageId) {
                $lead->updateStage($this->stageId, Auth::id() ?: 1, 'Updated via edit form');
            }
            
            $this->loadBoardData();
            $this->loadStatistics();
            $this->closeModals();
            
            session()->flash('success', 'Lead updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating lead: ' . $e->getMessage());
            session()->flash('error', 'Error updating lead: ' . $e->getMessage());
        }
    }
    
    public function deleteLead($leadId)
    {
        try {
            $lead = Lead::findOrFail($leadId);
            $lead->delete();
            
            $this->loadBoardData();
            $this->loadStatistics();
            $this->closeModals();
            
            session()->flash('success', 'Lead deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting lead: ' . $e->getMessage());
            session()->flash('error', 'Error deleting lead');
        }
    }

    public function render()
    {
        return view('livewire.kanban-board', [
            'stages' => $this->stages,
            'leads' => $this->leads,
            'statistics' => $this->statistics,
        ])->layout('components.layouts.kanban');
    }
}