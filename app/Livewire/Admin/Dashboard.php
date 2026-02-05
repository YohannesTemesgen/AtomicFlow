<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\BoardType;
use App\Models\Board;
use App\Models\BoardCard;
use App\Services\TenantService;

class Dashboard extends Component
{
    public $tenant;
    public $stats = [];

    public function mount()
    {
        $this->tenant = TenantService::getCurrentTenant();
        $this->loadStats();
    }

    public function loadStats()
    {
        if (!$this->tenant) {
            return;
        }

        $this->stats = [
            'board_types' => BoardType::where('tenant_id', $this->tenant->id)->count(),
            'boards' => Board::where('tenant_id', $this->tenant->id)->count(),
            'total_cards' => BoardCard::whereHas('board', fn($q) => $q->where('tenant_id', $this->tenant->id))->count(),
            'active_boards' => Board::where('tenant_id', $this->tenant->id)->where('is_active', true)->count(),
        ];
    }

    public function render()
    {
        $boardTypes = $this->tenant 
            ? BoardType::where('tenant_id', $this->tenant->id)->with('boards')->get() 
            : collect();

        return view('livewire.admin.dashboard', [
            'boardTypes' => $boardTypes,
        ])->layout('components.layouts.admin');
    }
}
