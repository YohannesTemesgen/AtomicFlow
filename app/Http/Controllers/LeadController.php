<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Lead::with(['client', 'stage', 'user', 'stageHistory'])
                ->where('user_id', Auth::id());

            // Search functionality
            if ($request->has('search')) {
                $query->search($request->search);
            }

            // Filter by stage
            if ($request->has('stage_id')) {
                $query->byStage($request->stage_id);
            }

            // Filter by priority
            if ($request->has('priority')) {
                $query->byPriority($request->priority);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by due date
            if ($request->has('due_soon')) {
                $query->dueSoon();
            }

            if ($request->has('overdue')) {
                $query->overdue();
            }

            $leads = $query->latest()->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $leads
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching leads: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching leads'
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
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'value' => 'nullable|numeric|min:0',
                'priority' => 'in:low,medium,high',
                'status' => 'in:active,won,lost',
                'expected_close_date' => 'nullable|date|after:today',
                'client_id' => 'required|exists:clients,id',
                'stage_id' => 'required|exists:stages,id',
            ]);

            // Ensure client belongs to authenticated user
            $client = \App\Models\Client::findOrFail($validated['client_id']);
            if ($client->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            $lead = Lead::create([
                ...$validated,
                'user_id' => Auth::id(),
            ]);

            // Create initial stage history
            $lead->stageHistory()->create([
                'from_stage_id' => null,
                'to_stage_id' => $validated['stage_id'],
                'user_id' => Auth::id(),
                'notes' => 'Lead created',
                'transitioned_at' => now(),
            ]);

            Log::info('Lead created', ['lead_id' => $lead->id, 'user_id' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully',
                'data' => $lead->load(['client', 'stage', 'user', 'stageHistory'])
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating lead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating lead'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        try {
            // Ensure the lead belongs to the authenticated user
            if ($lead->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $lead->load(['client', 'stage', 'user', 'stageHistory.user', 'stageHistory.fromStage', 'stageHistory.toStage'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching lead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching lead'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        try {
            // Ensure the lead belongs to the authenticated user
            if ($lead->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead not found'
                ], 404);
            }

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'value' => 'nullable|numeric|min:0',
                'priority' => 'sometimes|in:low,medium,high',
                'status' => 'sometimes|in:active,won,lost',
                'expected_close_date' => 'nullable|date|after:today',
                'client_id' => 'sometimes|required|exists:clients,id',
                'stage_id' => 'sometimes|required|exists:stages,id',
            ]);

            // If client_id is being updated, ensure it belongs to authenticated user
            if (isset($validated['client_id'])) {
                $client = \App\Models\Client::findOrFail($validated['client_id']);
                if ($client->user_id !== Auth::id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Client not found'
                    ], 404);
                }
            }

            $lead->update($validated);

            Log::info('Lead updated', ['lead_id' => $lead->id, 'user_id' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => 'Lead updated successfully',
                'data' => $lead->fresh(['client', 'stage', 'user', 'stageHistory'])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating lead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating lead'
            ], 500);
        }
    }

    /**
     * Update lead stage with history tracking.
     */
    public function updateStage(Request $request, Lead $lead)
    {
        try {
            // Ensure the lead belongs to the authenticated user
            if ($lead->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead not found'
                ], 404);
            }

            $validated = $request->validate([
                'stage_id' => 'required|exists:stages,id',
                'notes' => 'nullable|string',
            ]);

            $lead->updateStage($validated['stage_id'], Auth::id(), $validated['notes'] ?? null);

            Log::info('Lead stage updated', [
                'lead_id' => $lead->id,
                'stage_id' => $validated['stage_id'],
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lead stage updated successfully',
                'data' => $lead->fresh(['stage', 'stageHistory.user', 'stageHistory.fromStage', 'stageHistory.toStage'])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating lead stage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating lead stage'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        try {
            // Ensure the lead belongs to the authenticated user
            if ($lead->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead not found'
                ], 404);
            }

            $leadId = $lead->id;
            $lead->delete();

            Log::info('Lead deleted', ['lead_id' => $leadId, 'user_id' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => 'Lead deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting lead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting lead'
            ], 500);
        }
    }
}