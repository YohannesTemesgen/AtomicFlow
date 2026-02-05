<?php

namespace App\Livewire;

use App\Models\ClientCategory;
use App\Models\Stage;
use App\Models\Client;
use App\Models\AssignedPerson;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SettingsManager extends Component
{
    // Active tab
    public $activeTab = 'stages';
    
    // Category management
    public $categories = [];
    public $showCategoryModal = false;
    public $editingCategoryId = null;
    public $categoryName = '';
    public $categoryColor = '#3b82f6';
    public $categoryDescription = '';
    public $categoryIsActive = true;
    
    // Stage management
    public $stages = [];
    public $showStageModal = false;
    public $editingStageId = null;
    public $stageName = '';
    public $stageColor = '#3b82f6';
    public $stageDescription = '';
    public $stageIsActive = true;
    
    // Client management
    public $clients = [];
    public $showClientModal = false;
    public $editingClientId = null;
    public $clientName = '';
    public $clientEmail = '';
    public $clientPhone = '';
    public $clientLocation = '';
    public $clientCategory = '';
    public $clientNotes = '';
    
    // Assigned People management
    public $assignedPeople = [];
    public $showAssignedPersonModal = false;
    public $editingAssignedPersonId = null;
    public $assignedPersonName = '';
    public $assignedPersonEmail = '';
    public $assignedPersonPhone = '';
    public $assignedPersonRole = '';
    public $assignedPersonIsActive = true;
    
    // Delete confirmation
    public $showDeleteModal = false;
    public $deletingId = null;
    public $deletingType = null;

    public function mount()
    {
        $this->loadCategories();
        $this->loadStages();
        $this->loadClients();
        $this->loadAssignedPeople();
    }

    public function loadCategories()
    {
        $this->categories = ClientCategory::ordered()->get()->toArray();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Category CRUD operations
    public function openCategoryModal($categoryId = null)
    {
        $this->resetCategoryForm();
        
        if ($categoryId) {
            $category = ClientCategory::find($categoryId);
            if ($category) {
                $this->editingCategoryId = $category->id;
                $this->categoryName = $category->name;
                $this->categoryColor = $category->color;
                $this->categoryDescription = $category->description ?? '';
                $this->categoryIsActive = $category->is_active;
            }
        }
        
        $this->showCategoryModal = true;
    }

    public function closeCategoryModal()
    {
        $this->showCategoryModal = false;
        $this->resetCategoryForm();
    }

    public function resetCategoryForm()
    {
        $this->editingCategoryId = null;
        $this->categoryName = '';
        $this->categoryColor = '#3b82f6';
        $this->categoryDescription = '';
        $this->categoryIsActive = true;
        $this->resetErrorBag();
    }

    public function saveCategory()
    {
        $this->validate();
        
        try {
            $data = [
                'name' => $this->categoryName,
                'color' => $this->categoryColor,
                'description' => $this->categoryDescription ?: null,
                'is_active' => $this->categoryIsActive,
            ];
            
            if ($this->editingCategoryId) {
                $category = ClientCategory::findOrFail($this->editingCategoryId);
                $category->update($data);
                session()->flash('success', 'Category updated successfully!');
            } else {
                $maxOrder = ClientCategory::max('sort_order') ?? 0;
                $data['sort_order'] = $maxOrder + 1;
                ClientCategory::create($data);
                session()->flash('success', 'Category created successfully!');
            }
            
            $this->loadCategories();
            $this->closeCategoryModal();
        } catch (\Exception $e) {
            Log::error('Error saving category: ' . $e->getMessage());
            session()->flash('error', 'Error saving category: ' . $e->getMessage());
        }
    }

    public function confirmDeleteCategory($categoryId)
    {
        $this->deletingId = $categoryId;
        $this->deletingType = 'category';
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->deletingType = null;
    }

    public function deleteCategory()
    {
        try {
            $category = ClientCategory::findOrFail($this->deletingId);
            $category->delete();
            
            $this->loadCategories();
            $this->closeDeleteModal();
            
            session()->flash('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            session()->flash('error', 'Error deleting category');
        }
    }

    public function toggleCategoryStatus($categoryId)
    {
        try {
            $category = ClientCategory::findOrFail($categoryId);
            $category->update(['is_active' => !$category->is_active]);
            $this->loadCategories();
            
            session()->flash('success', 'Category status updated!');
        } catch (\Exception $e) {
            Log::error('Error toggling category status: ' . $e->getMessage());
            session()->flash('error', 'Error updating category status');
        }
    }

    public function updateCategoryOrder($orderedIds)
    {
        try {
            foreach ($orderedIds as $index => $id) {
                ClientCategory::where('id', $id)->update(['sort_order' => $index]);
            }
            $this->loadCategories();
        } catch (\Exception $e) {
            Log::error('Error updating category order: ' . $e->getMessage());
        }
    }

    // ==================== STAGE MANAGEMENT ====================
    
    public function loadStages()
    {
        $this->stages = Stage::ordered()->get()->toArray();
    }

    public function openStageModal($stageId = null)
    {
        $this->resetStageForm();
        
        if ($stageId) {
            $stage = Stage::find($stageId);
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
                $stage = Stage::findOrFail($this->editingStageId);
                $stage->update($data);
                session()->flash('success', 'Stage updated successfully!');
            } else {
                $maxPosition = Stage::max('position') ?? 0;
                $data['position'] = $maxPosition + 1;
                Stage::create($data);
                session()->flash('success', 'Stage created successfully!');
            }
            
            $this->loadStages();
            $this->closeStageModal();
        } catch (\Exception $e) {
            Log::error('Error saving stage: ' . $e->getMessage());
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
            $stage = Stage::findOrFail($this->deletingId);
            $stage->delete();
            
            $this->loadStages();
            $this->closeDeleteModal();
            
            session()->flash('success', 'Stage deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting stage: ' . $e->getMessage());
            session()->flash('error', 'Error deleting stage');
        }
    }

    public function toggleStageStatus($stageId)
    {
        try {
            $stage = Stage::findOrFail($stageId);
            $stage->update(['is_active' => !$stage->is_active]);
            $this->loadStages();
            
            session()->flash('success', 'Stage status updated!');
        } catch (\Exception $e) {
            Log::error('Error toggling stage status: ' . $e->getMessage());
            session()->flash('error', 'Error updating stage status');
        }
    }

    // ==================== CLIENT MANAGEMENT ====================
    
    public function loadClients()
    {
        $this->clients = Client::orderBy('name')->get()->toArray();
    }

    public function openClientModal($clientId = null)
    {
        $this->resetClientForm();
        
        if ($clientId) {
            $client = Client::find($clientId);
            if ($client) {
                $this->editingClientId = $client->id;
                $this->clientName = $client->name;
                $this->clientEmail = $client->email ?? '';
                $this->clientPhone = $client->phone ?? '';
                $this->clientLocation = $client->location ?? '';
                $this->clientCategory = $client->project_category ?? '';
                $this->clientNotes = $client->notes ?? '';
            }
        }
        
        $this->showClientModal = true;
    }

    public function closeClientModal()
    {
        $this->showClientModal = false;
        $this->resetClientForm();
    }

    public function resetClientForm()
    {
        $this->editingClientId = null;
        $this->clientName = '';
        $this->clientEmail = '';
        $this->clientPhone = '';
        $this->clientLocation = '';
        $this->clientCategory = '';
        $this->clientNotes = '';
        $this->resetErrorBag();
    }

    public function saveClient()
    {
        $this->validate([
            'clientName' => 'required|string|max:255',
            'clientEmail' => 'nullable|email|max:255',
            'clientPhone' => 'nullable|string|max:50',
            'clientLocation' => 'nullable|string|max:255',
            'clientCategory' => 'nullable|string|max:255',
            'clientNotes' => 'nullable|string',
        ]);
        
        try {
            $data = [
                'name' => $this->clientName,
                'email' => $this->clientEmail ?: null,
                'phone' => $this->clientPhone ?: null,
                'location' => $this->clientLocation ?: null,
                'project_category' => $this->clientCategory ?: null,
                'notes' => $this->clientNotes ?: null,
                'user_id' => Auth::id() ?? 1,
            ];
            
            if ($this->editingClientId) {
                $client = Client::findOrFail($this->editingClientId);
                $client->update($data);
                session()->flash('success', 'Client updated successfully!');
            } else {
                Client::create($data);
                session()->flash('success', 'Client created successfully!');
            }
            
            $this->loadClients();
            $this->closeClientModal();
        } catch (\Exception $e) {
            Log::error('Error saving client: ' . $e->getMessage());
            session()->flash('error', 'Error saving client: ' . $e->getMessage());
        }
    }

    public function confirmDeleteClient($clientId)
    {
        $this->deletingId = $clientId;
        $this->deletingType = 'client';
        $this->showDeleteModal = true;
    }

    public function deleteClient()
    {
        try {
            $client = Client::findOrFail($this->deletingId);
            $client->delete();
            
            $this->loadClients();
            $this->closeDeleteModal();
            
            session()->flash('success', 'Client deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting client: ' . $e->getMessage());
            session()->flash('error', 'Error deleting client');
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

    // ==================== GENERIC DELETE ====================
    
    public function confirmDelete()
    {
        if ($this->deletingType === 'category') {
            $this->deleteCategory();
        } elseif ($this->deletingType === 'stage') {
            $this->deleteStage();
        } elseif ($this->deletingType === 'client') {
            $this->deleteClient();
        } elseif ($this->deletingType === 'assigned_person') {
            $this->deleteAssignedPerson();
        }
    }

    public function render()
    {
        return view('livewire.settings-manager')
            ->layout('components.layouts.kanban', ['title' => 'Settings']);
    }
}
