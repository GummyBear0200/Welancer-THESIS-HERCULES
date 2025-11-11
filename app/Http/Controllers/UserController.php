<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Required for password hashing
use Illuminate\Support\Facades\Validator; // Required for manual validation

class UserController extends Controller
{
    /**
     * Retrieves a list of all users.
     * Accessible only by Admin (1) and HR (2) roles via middleware.
     */
    public function getUsers()
    {
        try {
            $users = User::select('id', 'first_name', 'last_name', 'email', 'role_id', 'user_status_id')
                         ->orderBy('role_id')
                         ->get();
            
            return response()->json([
                'message' => 'Users retrieved successfully.',
                'users' => $users
            ], 200);

        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to retrieve users.'], 500);
        }
    }

    /**
     * Creates a new user. 
     * Requires Admin (1) or HR (2) role.
     */
    public function addUser(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            // Middle name is optional
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            // Role ID must be a valid ID in your roles table (assuming roles 1-4 are valid)
            'role_id' => 'required|integer|exists:roles,id', 
            // Assuming 1 = Active, 2 = Inactive, etc.
            'user_status_id' => 'required|integer|exists:user_statuses,id', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error', 
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 2. Creation
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name ?? null, // Optional
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hashing the password is crucial
                'role_id' => $request->role_id,
                'user_status_id' => $request->user_status_id,
            ]);

            return response()->json([
                'message' => 'User created successfully.',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error adding user: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create user.'], 500);
        }
    }

    /**
     * Updates an existing user's details. 
     * Requires Admin (1) or HR (2) role.
     */
    public function editUser(Request $request, $id)
    {
        // 1. Find the user
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // 2. Validation (password is optional, unique email must ignore the current user)
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'sometimes|integer|exists:roles,id',
            'user_status_id' => 'sometimes|integer|exists:user_statuses,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error', 
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 3. Update fields
            $user->first_name = $request->input('first_name', $user->first_name);
            $user->middle_name = $request->input('middle_name', $user->middle_name);
            $user->last_name = $request->input('last_name', $user->last_name);
            $user->email = $request->input('email', $user->email);
            $user->role_id = $request->input('role_id', $user->role_id);
            $user->user_status_id = $request->input('user_status_id', $user->user_status_id);

            // Handle optional password update
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                'message' => 'User updated successfully.',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update user.'], 500);
        }
    }

    /**
     * Deletes a user from the system. 
     * Requires Admin (1) or HR (2) role.
     */
    public function deleteUser($id)
    {
        // Prevent users from deleting themselves (optional security measure)
        if (auth()->id() == $id) {
            return response()->json(['message' => 'You cannot delete your own account via this endpoint.'], 403);
        }
        
        // 1. Find the user
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        try {
            // 2. Deletion
            $user->delete();

            return response()->json(['message' => 'User deleted successfully.'], 200);

        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete user.'], 500);
        }
    }
}