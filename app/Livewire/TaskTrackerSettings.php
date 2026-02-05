<?php

namespace App\Livewire;

use App\Models\TaskStage;
use App\Models\AssignedPerson;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TaskTrackerSettings extends Component
{
    // Active tab
    public $activeTab = 'stages';
    
    // Stage management
    public $stages = [];
    public $showStageModal = false;
    public $editingStageId = null;
    public $stageName = '';
    public $stageColor = '#3b82f6';
    public $stageDescription = '';
    public $stageIsActive = true;
    
    // Assigned People management
    public $assignedPeople = [];
    public $showAssignedPersonModal = false;
    public $editingAssignedPersonId = null;
    public $assignedPersonName = '';
    public $assignedPersonEmail = '';
    public $assignedPersonPhone = '';
    public $assignedPersonRole = '';
    public $assignedPersonIsActive = true;
    
    // Task Types management
    public $taskTypes = ['Bug', 'Feature', 'Enhancement', 'Documentation', 'Research', 'Testing'];
    
    // Project management
    public $projects = [];
    public $showProjectModal = false;
    public $editingProjectId = null;
    public $projectName = '';
    public $projectDescription = '';
    public $projectColor = '#8b5cf6';
    public $projectStatus = 'active';
    public $projectStartDate = '';
    public $projectEndDate = '';
    public $projectIsActive = true;
    
    // Delete confirmation
    public $showDeleteModal = false;
    public $deletingId = null;
    public $deletingType = null;

    public function mount()
    {
        $this->loadStages();
        $this->loadAssignedPeople();
        $this->loadProjects();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // ==================== STAGE MANAGEMENT ====================
    
    public function loadStages()
    {
        $this->stages = TaskStage::ordered()->get()->toArray();
    }

    public function openStageModal($stageId = null)
    {
        $this->resetStageForm();
        
        if ($stageId) {
            $stage = TaskStage::find($stageId);
            if ($stage) {
                $this->editingStageId = $stage->id;
                $this->stageName = $stage->name;
                $this->stageColor = $stage->color ?? '#3b82f6';
                $this->stageDescription = $stage->description ?? '';
                $this->stageIsActive = $stage->is_active;
            }
        }
        
        $this->showStageModal = true;
    }

    public function closeStageModal()
    {
        $this->showStageModal = false;
        $this->resetStageForm();
    }

    public function resetStageForm()
    {
        $this->editingStageId = null;
        $this->stageName = '';
        $this->stageColor = '#3b82f6';
        $this->stageDescription = '';
        $this->stageIsActive = true;
        $this->resetErrorBag();
    }

    public function saveStage()
    {
        $this->validate([
            'stageName' => 'required|string|max:255',
            'stageColor' => 'required|string|max:7',
            'stageDescription' => 'nullable|string|max:500',
            'stageIsActive' => 'boolean',
        ]);
        
        try {
            $data = [
                'name' => $this->stageName,
                'slug' => Str::slug($this->stageName),
                'color' => $this->stageColor,
                'description' => $this->stageDescription ?: null,
                'is_active' => $this->stageIsActive,
            ];
            
            if ($this->editingStageId) {
                $stage = TaskStage::findOrFail($this->editingStageId);
                $stage->update($data);
                session()->flash('success', 'Stage updated successfully!');
            } else {
                $maxPosition = TaskStage::max('position') ?? 0;
                $data['position'] = $maxPosition + 1;
                TaskStage::create($data);
                session()->flash('success', 'Stage created successfully!');
            }
            
            $this->loadStages();
            $this->closeStageModal();
        } catch (\Exception $e) {
            Log::error('Error saving task stage: ' . $e->getMessage());
            session()->flash('error', 'Error saving stage: ' . $e->getMessage());
        }
    }

    public function confirmDeleteStage($stageId)
    {
        $this->deletingId = $stageId;
        $this->deletingType = 'stage';
        $this->showDeleteModal = true;
    }

    public function deleteStage()
    {
        try {
            $stage = TaskStage::findOrFail($this->deletingId);
            $stage->delete();
            
            $this->loadStages();
            $this->closeDeleteModal();
            
            session()->flash('success', 'Stage deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting task stage: ' . $e->getMessage());
            session()->flash('error', 'Error deleting stage');
        }
    }

    public function toggleStageStatus($stageId)
    {
        try {
            $stage = TaskStage::findOrFail($stageId);
            $stage->update(['is_active' => !$stage->is_active]);
            $this->loadStages();
            
            session()->flash('success', 'Stage status updated!');
        } catch (\Exception $e) {
            Log::error('Error toggling task stage status: ' . $e->getMessage());
            session()->flash('error', 'Error updating stage status');
        }
    }

    // ==================== ASSIGNED PEOPLE MANAGEMENT ====================
    
    public function loadAssignedPeople()
    {
        $this->assignedPeople = AssignedPerson::ordered()->get()->toArray();
    }

    public function openAssignedPersonModal($personId = null)
    {
        $this->resetAssignedPersonForm();
        
        if ($personId) {
            $person = AssignedPerson::find($personId);
            if ($person) {
                $this->editingAssignedPersonId = $person->id;
                $this->assignedPersonName = $person->name;
                $this->assignedPersonEmail = $person->email ?? '';
                $this->assignedPersonPhone = $person->phone ?? '';
                $this->assignedPersonRole = $person->role ?? '';
                $this->assignedPersonIsActive = $person->is_active;
            }
        }
        
        $this->showAssignedPersonModal = true;
    }

    public function closeAssignedPersonModal()
    {
        $this->showAssignedPersonModal = false;
        $this->resetAssignedPersonForm();
    }

    public function resetAssignedPersonForm()
    {
        $this->editingAssignedPersonId = null;
        $this->assignedPersonName = '';
        $this->assignedPersonEmail = '';
        $this->assignedPersonPhone = '';
        $this->assignedPersonRole = '';
        $this->assignedPersonIsActive = true;
        $this->resetErrorBag();
    }

    public function saveAssignedPerson()
    {
        $this->validate([
            'assignedPersonName' => 'required|string|max:255',
            'assignedPersonEmail' => 'nullable|email|max:255',
            'assignedPersonPhone' => 'nullable|string|max:50',
            'assignedPersonRole' => 'nullable|string|max:255',
            'assignedPersonIsActive' => 'boolean',
        ]);
        
        try {
            $data = [
                'name' => $this->assignedPersonName,
                'email' => $this->assignedPersonEmail ?: null,
                'phone' => $this->assignedPersonPhone ?: null,
                'role' => $this->assignedPersonRole ?: null,
                'is_active' => $this->assignedPersonIsActive,
            ];
            
            if ($this->editingAssignedPersonId) {
                $person = AssignedPerson::findOrFail($this->editingAssignedPersonId);
                $person->update($data);
                session()->flash('success', 'Assigned person updated successfully!');
            } else {
                $maxOrder = AssignedPerson::max('sort_order') ?? 0;
                $data['sort_order'] = $maxOrder + 1;
                AssignedPerson::create($data);
                session()->flash('success', 'Assigned person created successfully!');
            }
            
            $this->loadAssignedPeople();
            $this->closeAssignedPersonModal();
        } catch (\Exception $e) {
            Log::error('Error saving assigned person: ' . $e->getMessage());
            session()->flash('error', 'Error saving assigned person: ' . $e->getMessage());
        }
    }

    public function confirmDeleteAssignedPerson($personId)
    {
        $this->deletingId = $personId;
        $this->deletingType = 'assigned_person';
        $this->showDeleteModal = true;
    }

    public function deleteAssignedPerson()
    {
        try {
            $person = AssignedPerson::findOrFail($this->deletingId);
            $person->delete();
            
            $this->loadAssignedPeople();
            $this->closeDeleteModal();
            
            session()->flash('success', 'Assigned person deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting assigned person: ' . $e->getMessage());
            session()->flash('error', 'Error deleting assigned person');
        }
    }

    public function toggleAssignedPersonStatus($personId)
    {
        try {
            $person = AssignedPerson::findOrFail($personId);
            $person->update(['is_active' => !$person->is_active]);
            $this->loadAssignedPeople();
            
            session()->flash('success', 'Assigned person status updated!');
        } catch (\Exception $e) {
            Log::error('Error toggling assigned person status: ' . $e->getMessage());
            session()->flash('error', 'Error updating assigned person status');
        }
    }

    // ==================== DELETE MODAL ====================
    
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->deletingType = null;
    }

    public function confirmDelete()
    {
        if ($this->deletingType === 'stage') {
            $this->deleteStage();
        } elseif ($this->deletingType === 'assigned_person') {
            $this->deleteAssignedPerson();
        } elseif ($this->deletingType === 'project') {
            $this->deleteProject();
        }
    }

    // ==================== PROJECT MANAGEMENT ====================
    
    public function loadProjects()
    {
        $this->projects = Project::ordered()->get()->toArray();
    }

    public function openProjectModal($projectId = null)
    {
        $this->resetProjectForm();
        
        if ($projectId) {
            $project = Project::find($projectId);
            if ($project) {
                $this->editingProjectId = $project->id;
                $this->projectName = $project->name;
                $this->projectDescription = $project->description ?? '';
                $this->projectColor = $project->color ?? '#8b5cf6';
                $this->projectStatus = $project->status ?? 'active';
                $this->projectStartDate = $project->start_date ? $project->start_date->format('Y-m-d') : '';
                $this->projectEndDate = $project->end_date ? $project->end_date->format('Y-m-d') : '';
                $this->projectIsActive = $project->is_active;
            }
        }
        
        $this->showProjectModal = true;
    }

    public function closeProjectModal()
    {
        $this->showProjectModal = false;
        $this->resetProjectForm();
    }

    public function resetProjectForm()
    {
        $this->editingProjectId = null;
        $this->projectName = '';
        $this->projectDescription = '';
        $this->projectColor = '#8b5cf6';
        $this->projectStatus = 'active';
        $this->projectStartDate = '';
        $this->projectEndDate = '';
        $this->projectIsActive = true;
        $this->resetErrorBag();
    }

    public function saveProject()
    {
        $this->validate([
            'projectName' => 'required|string|max:255',
            'projectDescription' => 'nullable|string|max:1000',
            'projectColor' => 'required|string|max:7',
            'projectStatus' => 'required|in:active,on_hold,completed,archived',
            'projectStartDate' => 'nullable|date',
            'projectEndDate' => 'nullable|date',
            'projectIsActive' => 'boolean',
        ]);
        
        try {
            $data = [
                'name' => $this->projectName,
                'description' => $this->projectDescription ?: null,
                'color' => $this->projectColor,
                'status' => $this->projectStatus,
                'start_date' => $this->projectStartDate ?: null,
                'end_date' => $this->projectEndDate ?: null,
                'is_active' => $this->projectIsActive,
            ];
            
            if ($this->editingProjectId) {
                $project = Project::findOrFail($this->editingProjectId);
                $project->update($data);
                session()->flash('success', 'Project updated successfully!');
            } else {
                $maxOrder = Project::max('sort_order') ?? 0;
                $data['sort_order'] = $maxOrder + 1;
                Project::create($data);
                session()->flash('success', 'Project created successfully!');
            }
            
            $this->loadProjects();
            $this->closeProjectModal();
        } catch (\Exception $e) {
            Log::error('Error saving project: ' . $e->getMessage());
            session()->flash('error', 'Error saving project: ' . $e->getMessage());
        }
    }

    public function confirmDeleteProject($projectId)
    {
        $this->deletingId = $projectId;
        $this->deletingType = 'project';
        $this->showDeleteModal = true;
    }

    public function deleteProject()
    {
        try {
            $project = Project::findOrFail($this->deletingId);
            $project->delete();
            
            $this->loadProjects();
            $this->closeDeleteModal();
            
            session()->flash('success', 'Project deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting project: ' . $e->getMessage());
            session()->flash('error', 'Error deleting project');
        }
    }

    public function toggleProjectStatus($projectId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $project->update(['is_active' => !$project->is_active]);
            $this->loadProjects();
            
            session()->flash('success', 'Project status updated!');
        } catch (\Exception $e) {
            Log::error('Error toggling project status: ' . $e->getMessage());
            session()->flash('error', 'Error updating project status');
        }
    }

    public function render()
    {
        return view('livewire.task-tracker-settings')
            ->layout('components.layouts.task-tracker', ['title' => 'Task Tracker Settings']);
    }
}
