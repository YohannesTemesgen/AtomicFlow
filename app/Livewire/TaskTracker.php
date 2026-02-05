<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskStage;
use App\Models\AssignedPerson;
use App\Models\Client;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskTracker extends Component
{
    public $stages = [];
    public $tasks = [];
    public $search = '';
    public $priority = '';
    public $assignedToFilter = '';
    public $projectFilter = '';
    public $draggedTaskId = null;
    public $draggedFromStage = null;
    public $statistics = [];
    
    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    
    // Form fields
    public $taskId = null;
    public $title = '';
    public $description = '';
    public $taskPriority = 'medium';
    public $taskStatus = 'active';
    public $taskType = '';
    public $dueDate = '';
    public $stageId = null;
    public $assignedToId = null;
    public $clientId = null;
    public $projectId = null;
    public $clients = [];
    public $assignedPeople = [];
    public $projects = [];

    protected $listeners = ['refreshBoard' => '$refresh'];
    
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'taskPriority' => 'required|in:low,medium,high',
        'taskStatus' => 'required|in:active,completed,on_hold,cancelled',
        'taskType' => 'nullable|string|max:100',
        'dueDate' => 'nullable|date',
        'stageId' => 'required|exists:task_stages,id',
        'assignedToId' => 'nullable|exists:assigned_people,id',
        'clientId' => 'nullable|exists:clients,id',
        'projectId' => 'nullable|exists:projects,id',
    ];

    public function mount()
    {
        $this->loadBoardData();
        $this->loadStatistics();
        $this->loadClients();
        $this->loadAssignedPeople();
        $this->loadProjects();
    }

    public function loadBoardData()
    {
        try {
            $this->stages = TaskStage::active()->ordered()->get()->toArray();

            $query = Task::with(['client', 'stage', 'user', 'assignedTo', 'project'])
                ->where('status', 'active');

            if (!empty($this->search)) {
                $query->search($this->search);
            }

            if (!empty($this->priority)) {
                $query->byPriority($this->priority);
            }

            if (!empty($this->assignedToFilter)) {
                $query->where('assigned_to_id', $this->assignedToFilter);
            }

            if (!empty($this->projectFilter)) {
                $query->where('project_id', $this->projectFilter);
            }

            $this->tasks = $query->get()->groupBy('stage_id')->toArray();
        } catch (\Exception $e) {
            Log::error('Error loading Task Tracker board data: ' . $e->getMessage());
            session()->flash('error', 'Error loading board data');
        }
    }

    public function loadStatistics()
    {
        try {
            $stageStats = TaskStage::active()
                ->ordered()
                ->withCount(['tasks' => function ($query) {
                    $query->where('status', 'active');
                }])
                ->get();

            $totalTasks = Task::count();
            $activeTasks = Task::where('status', 'active')->count();
            $completedTasks = Task::where('status', 'completed')->count();

            $overdueTasks = Task::where('status', 'active')
                ->where('due_date', '<', now())
                ->count();

            $dueSoonTasks = Task::where('status', 'active')
                ->where('due_date', '<=', now()->addDays(7))
                ->where('due_date', '>=', now())
                ->count();

            $this->statistics = [
                'stage_stats' => $stageStats,
                'overview' => [
                    'total_tasks' => $totalTasks,
                    'active_tasks' => $activeTasks,
                    'completed_tasks' => $completedTasks,
                    'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0,
                ],
                'due_date_stats' => [
                    'overdue_tasks' => $overdueTasks,
                    'due_soon_tasks' => $dueSoonTasks,
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Error loading task statistics: ' . $e->getMessage());
        }
    }

    public function startDrag($taskId, $stageId)
    {
        $this->draggedTaskId = $taskId;
        $this->draggedFromStage = $stageId;
    }

    public function handleDrop($toStageId)
    {
        try {
            if (empty($this->draggedTaskId) || empty($this->draggedFromStage)) {
                return;
            }

            $task = Task::find($this->draggedTaskId);
            if (!$task) {
                session()->flash('error', 'Task not found');
                return;
            }

            if ($this->draggedFromStage == $toStageId) {
                $this->resetDragData();
                return;
            }

            $task->updateStage($toStageId, Auth::id() ?: 1, 'Moved via Kanban board');

            $this->loadBoardData();
            $this->loadStatistics();

            session()->flash('success', 'Task moved successfully');
        } catch (\Exception $e) {
            Log::error('Error handling drag-drop: ' . $e->getMessage());
            session()->flash('error', 'Error moving task');
        } finally {
            $this->resetDragData();
        }
    }

    public function resetDragData()
    {
        $this->draggedTaskId = null;
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

    public function updatedAssignedToFilter()
    {
        $this->loadBoardData();
    }

    public function updatedProjectFilter()
    {
        $this->loadBoardData();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'priority', 'assignedToFilter', 'projectFilter']);
        $this->loadBoardData();
    }

    public function getTasksForStage($stageId)
    {
        return $this->tasks[$stageId] ?? [];
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

    public function isOverdue($dueDate)
    {
        if (!$dueDate) return false;
        return now()->greaterThan($dueDate);
    }

    public function isDueSoon($dueDate)
    {
        if (!$dueDate) return false;
        $date = \Carbon\Carbon::parse($dueDate);
        return $date->diffInDays(now()) <= 7 && $date->isFuture();
    }
    
    public function loadClients()
    {
        $this->clients = Client::orderBy('name')->get();
    }
    
    public function loadAssignedPeople()
    {
        $this->assignedPeople = AssignedPerson::active()->ordered()->get();
    }
    
    public function loadProjects()
    {
        $this->projects = Project::active()->ordered()->get();
    }
    
    public function openCreateModal($stageId = null)
    {
        $this->resetForm();
        $this->stageId = $stageId ?? ($this->stages[0]['id'] ?? null);
        $this->showCreateModal = true;
    }
    
    public function openEditModal($taskId)
    {
        try {
            $task = Task::with(['client', 'project'])->findOrFail($taskId);
            
            $this->taskId = $task->id;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->taskPriority = $task->priority;
            $this->taskStatus = $task->status;
            $this->taskType = $task->type;
            $this->dueDate = $task->due_date ? $task->due_date->format('Y-m-d') : '';
            $this->stageId = $task->stage_id;
            $this->assignedToId = $task->assigned_to_id;
            $this->clientId = $task->client_id;
            $this->projectId = $task->project_id;
            
            $this->showEditModal = true;
        } catch (\Exception $e) {
            Log::error('Error opening edit modal: ' . $e->getMessage());
            session()->flash('error', 'Error loading task details');
        }
    }
    
    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->taskId = null;
        $this->title = '';
        $this->description = '';
        $this->taskPriority = 'medium';
        $this->taskStatus = 'active';
        $this->taskType = '';
        $this->dueDate = '';
        $this->stageId = null;
        $this->assignedToId = null;
        $this->clientId = null;
        $this->projectId = null;
        $this->resetErrorBag();
    }
    
    public function createTask()
    {
        $this->validate();
        
        try {
            Task::create([
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->taskPriority,
                'status' => $this->taskStatus,
                'type' => $this->taskType ?: null,
                'due_date' => $this->dueDate ?: null,
                'stage_id' => $this->stageId,
                'user_id' => Auth::id() ?: 1,
                'assigned_to_id' => $this->assignedToId ?: null,
                'client_id' => $this->clientId ?: null,
                'project_id' => $this->projectId ?: null,
            ]);
            
            $this->loadBoardData();
            $this->loadStatistics();
            $this->closeModals();
            
            session()->flash('success', 'Task created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            session()->flash('error', 'Error creating task: ' . $e->getMessage());
        }
    }
    
    public function updateTask()
    {
        $this->validate();
        
        try {
            $task = Task::findOrFail($this->taskId);
            $oldStageId = $task->stage_id;
            
            $task->update([
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->taskPriority,
                'status' => $this->taskStatus,
                'type' => $this->taskType ?: null,
                'due_date' => $this->dueDate ?: null,
                'stage_id' => $this->stageId,
                'assigned_to_id' => $this->assignedToId ?: null,
                'client_id' => $this->clientId ?: null,
                'project_id' => $this->projectId ?: null,
            ]);
            
            if ($oldStageId != $this->stageId) {
                $task->updateStage($this->stageId, Auth::id() ?: 1, 'Updated via edit form');
            }
            
            $this->loadBoardData();
            $this->loadStatistics();
            $this->closeModals();
            
            session()->flash('success', 'Task updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            session()->flash('error', 'Error updating task: ' . $e->getMessage());
        }
    }
    
    public function deleteTask($taskId)
    {
        try {
            $task = Task::findOrFail($taskId);
            $task->delete();
            
            $this->loadBoardData();
            $this->loadStatistics();
            $this->closeModals();
            
            session()->flash('success', 'Task deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting task: ' . $e->getMessage());
            session()->flash('error', 'Error deleting task');
        }
    }

    public function render()
    {
        return view('livewire.task-tracker', [
            'stages' => $this->stages,
            'tasks' => $this->tasks,
            'statistics' => $this->statistics,
        ])->layout('components.layouts.task-tracker');
    }
}
