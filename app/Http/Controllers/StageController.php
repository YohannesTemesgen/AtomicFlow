<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $stages = Stage::active()->ordered()->get();

            return response()->json([
                'success' => true,
                'data' => $stages
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching stages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stages'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|unique:stages,slug|max:255',
                'position' => 'required|integer|min:0',
                'color' => 'required|string|max:7',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $stage = Stage::create($validated);

            Log::info('Stage created', ['stage_id' => $stage->id]);

            return response()->json([
                'success' => true,
                'message' => 'Stage created successfully',
                'data' => $stage
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating stage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating stage'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Stage $stage)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $stage->load(['leads.client', 'leads.user'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching stage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stage'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stage $stage)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'slug' => 'sometimes|required|string|unique:stages,slug,' . $stage->id . '|max:255',
                'position' => 'sometimes|required|integer|min:0',
                'color' => 'sometimes|required|string|max:7',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $stage->update($validated);

            Log::info('Stage updated', ['stage_id' => $stage->id]);

            return response()->json([
                'success' => true,
                'message' => 'Stage updated successfully',
                'data' => $stage
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating stage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating stage'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stage $stage)
    {
        try {
            // Check if stage has leads
            if ($stage->leads()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete stage with existing leads'
                ], 422);
            }

            $stageId = $stage->id;
            $stage->delete();

            Log::info('Stage deleted', ['stage_id' => $stageId]);

            return response()->json([
                'success' => true,
                'message' => 'Stage deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting stage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting stage'
            ], 500);
        }
    }
}