<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Client::with(['user', 'leads'])
                ->where('user_id', Auth::id());

            // Search functionality
            if ($request->has('search')) {
                $query->search($request->search);
            }

            // Filter by category
            if ($request->has('category')) {
                $query->filterByCategory($request->category);
            }

            // Filter by location
            if ($request->has('location')) {
                $query->filterByLocation($request->location);
            }

            $clients = $query->latest()->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $clients
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching clients: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching clients'
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
                'email' => 'required|email|unique:clients,email',
                'phone' => 'nullable|string|max:20',
                'location' => 'nullable|string|max:255',
                'project_category' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);

            $client = Client::create([
                ...$validated,
                'user_id' => Auth::id(),
            ]);

            Log::info('Client created', ['client_id' => $client->id, 'user_id' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully',
                'data' => $client->load(['user', 'leads'])
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating client: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating client'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        try {
            // Ensure the client belongs to the authenticated user
            if ($client->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $client->load(['user', 'leads.stage', 'leads.stageHistory'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching client: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching client'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        try {
            // Ensure the client belongs to the authenticated user
            if ($client->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:clients,email,' . $client->id,
                'phone' => 'nullable|string|max:20',
                'location' => 'nullable|string|max:255',
                'project_category' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);

            $client->update($validated);

            Log::info('Client updated', ['client_id' => $client->id, 'user_id' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully',
                'data' => $client->fresh(['user', 'leads.stage'])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating client: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating client'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        try {
            // Ensure the client belongs to the authenticated user
            if ($client->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            $clientId = $client->id;
            $client->delete();

            Log::info('Client deleted', ['client_id' => $clientId, 'user_id' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => 'Client deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting client: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting client'
            ], 500);
        }
    }
}
