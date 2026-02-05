<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\TenantService;

class TenantSettings extends Component
{
    public $tenant;
    
    public $form = [
        'name' => '',
        'theme_color' => '#10B981',
        'settings' => [],
    ];

    protected $rules = [
        'form.name' => 'required|string|max:255',
        'form.theme_color' => 'required|string|max:7',
    ];

    public function mount()
    {
        $this->tenant = TenantService::getCurrentTenant();
        if ($this->tenant) {
            $this->form = [
                'name' => $this->tenant->name,
                'theme_color' => $this->tenant->theme_color,
                'settings' => $this->tenant->settings ?? [],
            ];
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->tenant) {
            $this->tenant->update([
                'name' => $this->form['name'],
                'theme_color' => $this->form['theme_color'],
                'settings' => $this->form['settings'],
            ]);
            session()->flash('message', 'Settings saved successfully.');
        }
    }

    public function render()
    {
        return view('livewire.admin.tenant-settings')
            ->layout('components.layouts.admin');
    }
}
