<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        try {
            $query = User::query();

            $users = $query->paginate(10);

            return response()->json([
                'status' => true,
                'message' => "Users retrieved successfully.",
                'users' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => "User $user->id is found.",
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user_name = $user->name;

            $user->delete();

            return response()->json([
                'status' => true,
                'message' => "User $user_name deleted successfully.",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
