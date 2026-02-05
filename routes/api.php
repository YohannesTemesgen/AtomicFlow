<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\KanbanBoardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    // Client management routes
    Route::apiResource('clients', ClientController::class);
    Route::get('clients/search/{search}', [ClientController::class, 'search']);

    // Lead management routes
    Route::apiResource('leads', LeadController::class);
    Route::put('leads/{lead}/stage', [LeadController::class, 'updateStage']);
    Route::get('leads/search/{search}', [LeadController::class, 'search']);

    // Stage management routes
    Route::apiResource('stages', StageController::class);
    Route::get('stages/active', [StageController::class, 'active']);

    // Kanban board routes
    Route::get('kanban/board', [KanbanBoardController::class, 'index']);
    Route::put('kanban/leads/{lead}/stage', [KanbanBoardController::class, 'updateLeadStage']);
    Route::get('kanban/statistics', [KanbanBoardController::class, 'statistics']);

    // User profile route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Public routes (if needed)
Route::get('stages', [StageController::class, 'index'])->middleware('auth:sanctum');