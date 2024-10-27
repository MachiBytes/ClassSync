<?php

namespace App\Http\Controllers;

use App\Helpers\VerificationEmailDesigner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validate incoming request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8',
                'confirm_password' => 'required|string',
                'phone_number' => 'nullable|string|max:15', // Adjust max length as needed
                'birthdate' => 'nullable|date'
            ]);

            // Confirm the password
            if ($request->password != $request->confirm_password) {
                return response()->json([
                    'status' => false,
                    'message' => 'The password does not match.',
                ], 422);  // Unprocessable entity.
            }

            unset($validatedData['confirm_password']);

            // Hash the password
            $validatedData['password'] = Hash::make($validatedData['password']);

            DB::beginTransaction();

            // Create user
            $user = User::create($validatedData);
            
            VerificationEmailDesigner::sendVerificationEmail($user);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User created successfully.',
                'user' => $user
            ], 201); // 201 for created status
        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback the transaction if something goes wrong
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required'
            ]);

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email or Password does not match with our record.'
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            $token = $user->login();

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully.',
                'token' => $token
            ],);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function verifyEmail(Request $request)
    {
        try {
            $userId = $request->query('user_id');
            $authenticator = $request->query('authenticator');

            DB::beginTransaction();
            $user = User::find($userId);

            if ($authenticator != $user->password) {
                return response()->json([
                    'status' => false,
                ], 401);
            }

            $user->email_verified_at = now();
            $user->save();
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Email verified successfully.',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
