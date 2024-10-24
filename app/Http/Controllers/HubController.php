<?php

namespace App\Http\Controllers;

use App\Models\Hub;
use App\Models\User;
use Illuminate\Http\Request;

class HubController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Hub::query();

            if ($request->has('owner_id')) {
                $owner_id = $request->query('owner_id');

                // Check if owner_id exists in users table then query
                if (User::find($owner_id)) {
                    $query->where('owner_id', $owner_id);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "User with id of $owner_id doesn't exist. Make sure that the id provided in your owner_id query parameter is a valid user."
                    ], 400);
                }
            }

            $hubs = $query->paginate(10);

            return response()->json([
                'status' => true,
                'message' => "Hubs retrieved successfully.",
                'hubs' => $hubs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Hub $hub)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => "Hub $hub->id is found.",
                'hub' => $hub
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => "required|string|max:256",
                'owner_id' => "required|uuid|exists:users,id"
            ]);

            $hub = Hub::create($validatedData);

            return response()->json([
                'status' => true,
                'message' => "Hub $request->name created successfully.",
                'hub' => $hub
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Hub $hub)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:256'
            ]);

            $old_name = $hub->name;

            $hub->update($validatedData);

            return response()->json([
                'status' => true,
                'message' => "Hub $hub->id updated successfully.",
                'hub' => $hub,
                'changes' => ['old_name' => $old_name, 'new_name' => $hub->name]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Hub $hub)
    {
        try {
            $hub_name = $hub->name;

            $hub->delete();

            return response()->json([
                'status' => true,
                'message' => "Hub $hub_name deleted successfully.",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
