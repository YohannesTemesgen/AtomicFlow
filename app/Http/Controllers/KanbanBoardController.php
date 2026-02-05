<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KanbanBoardController extends Controller
{
    /**
     * Get Kanban board data with leads grouped by stages.
     */
    public function index(Request $request)
    {
        try {
            // Get all active stages ordered by position
            $stages = Stage::active()->ordered()->get();

            // Get leads for the authenticated user with filters
            $leadsQuery = Lead::with(['client', 'stage', 'user', 'stageHistory'])
                ->where('user_id', Auth::id())
                ->where('status', 'active'); // Only show active leads on Kanban board

            // Apply search filter
            if ($request->has('search')) {
                $leadsQuery->search($request->search);
            }

            // Apply priority filter
            if ($request->has('priority')) {
                $leadsQuery->byPriority($request->priority);
            }

            // Apply due date filters
            if ($request->has('due_soon')) {
                $leadsQuery->dueSoon();
            }

            if ($request->has('overdue')) {
                $leadsQuery->overdue();
            }

            $leads = $leadsQuery->get();

            // Group leads by stage
            $boardData = $stages->map(function ($stage) use ($leads) {
                $stageLeads = $leads->where('stage_id', $stage->id)->map(function ($lead) {
                    return [
                        'id' => $lead->id,
                        'title' => $lead->title,
                        'description' => $lead->description,
                        'value' => $lead->value,
                        'priority' => $lead->priority,
                        'expected_close_date' => $lead->expected_close_date,
                        'client' => [
                            'id' => $lead->client->id,
                            'name' => $lead->client->name,
                            'email' => $lead->client->email,
                            'phone' => $lead->client->phone,
                        ],
                        'created_at' => $lead->created_at,
                        'updated_at' => $lead->updated_at,
                    ];
                });

                return [
                    'id' => $stage->id,
                    'name' => $stage->name,
                    'slug' => $stage->slug,
                    'color' => $stage->color,
                    'description' => $stage->description,
                    'position' => $stage->position,
                    'leads' => $stageLeads,
                    'lead_count' => $stageLeads->count(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'stages' => $boardData,
                    'total_leads' => $leads->count(),
                    'filters' => [
                        'search' => $request->search,
                        'priority' => $request->priority,
                        'due_soon' => $request->has('due_soon'),
                        'overdue' => $request->has('overdue'),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Kanban board data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching Kanban board data'
            ], 500);
        }
    }

    /**
     * Handle drag-and-drop stage update for a lead.
     */
    public function updateLeadStage(Request $request, Lead $lead)
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
                'notes' => 'nullable|string|max:500',
            ]);

            // Update lead stage with history tracking
            $lead->updateStage($validated['stage_id'], Auth::id(), $validated['notes'] ?? null);

            Log::info('Lead stage updated via Kanban drag-drop', [
                'lead_id' => $lead->id,
                'from_stage_id' => $lead->getOriginal('stage_id'),
                'to_stage_id' => $validated['stage_id'],
                'user_id' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lead moved successfully',
                'data' => [
                    'lead' => $lead->fresh(['stage', 'client']),
                    'stage_history' => $lead->stageHistory()->latest()->first()
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating lead stage via Kanban: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error moving lead'
            ], 500);
        }
    }

    /**
     * Get Kanban board statistics.
     */
    public function statistics()
    {
        try {
            $userId = Auth::id();

            // Get stage statistics
            $stageStats = Stage::active()
                ->ordered()
                ->withCount(['leads' => function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->where('status', 'active');
                }])
                ->get();

            // Get overall lead statistics
            $totalLeads = Lead::where('user_id', $userId)->count();
            $activeLeads = Lead::where('user_id', $userId)->where('status', 'active')->count();
            $wonLeads = Lead::where('user_id', $userId)->where('status', 'won')->count();
            $lostLeads = Lead::where('user_id', $userId)->where('status', 'lost')->count();

            // Get value statistics
            $totalValue = Lead::where('user_id', $userId)->sum('value') ?? 0;
            $wonValue = Lead::where('user_id', $userId)->where('status', 'won')->sum('value') ?? 0;

            // Get due date statistics
            $overdueLeads = Lead::where('user_id', $userId)
                ->where('status', 'active')
                ->where('expected_close_date', '<', now())
                ->count();

            $dueSoonLeads = Lead::where('user_id', $userId)
                ->where('status', 'active')
                ->where('expected_close_date', '<=', now()->addDays(7))
                ->where('expected_close_date', '>=', now())
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
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
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Kanban statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics'
            ], 500);
        }
    }
}