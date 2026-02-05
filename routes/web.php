<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\KanbanBoard;
use App\Livewire\SettingsManager;
use App\Livewire\TaskTracker;
use App\Livewire\TaskTrackerSettings;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\BoardTypeManager;
use App\Livewire\Admin\BoardFieldManager;
use App\Livewire\Admin\BoardStageManager;
use App\Livewire\Admin\BoardManager;
use App\Livewire\Admin\KanbanBoardView;
use App\Livewire\Admin\TenantSettings;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('landing');
})->name('home');

Route::view('dashboard', 'dashboard')->name('dashboard');

// Kanban Board Routes (Lead Pipeline)
Route::get('/kanban', KanbanBoard::class)->name('kanban.board');

// App Settings Route (Client Categories, etc.)
Route::get('/app-settings', SettingsManager::class)->name('app.settings');

// Task Tracker Routes
Route::get('/task-tracker', TaskTracker::class)->name('task.tracker');
Route::get('/task-tracker/settings', TaskTrackerSettings::class)->name('task.tracker.settings');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Admin Dashboard Routes
    Route::prefix('admin')->group(function () {
        Route::get('/', AdminDashboard::class)->name('admin.dashboard');
        
        // Board Types Management
        Route::get('/board-types', BoardTypeManager::class)->name('admin.board-types');
        Route::get('/board-types/{boardTypeId}/fields', BoardFieldManager::class)->name('admin.board-type.fields');
        Route::get('/board-types/{boardTypeId}/stages', BoardStageManager::class)->name('admin.board-type.stages');
        
        // Boards Management
        Route::get('/boards', BoardManager::class)->name('admin.boards');
        Route::get('/boards/{boardId}', KanbanBoardView::class)->name('admin.board.view');
        
        // Settings
        Route::get('/settings', TenantSettings::class)->name('admin.settings');

        // Profile
        Volt::route('/profile', 'admin.profile')->name('admin.profile');
    });
});
