<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AuthenticationController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'same:password'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3, // Libririan
            'user_status_id' => 1, // Active
        ]);

        return response()->json(['message' => 'User created succesfully!', 'user' => $user]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'User logged in succesfully!', 'user' => $user, 'token' => $token]);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'User logged out succesfully!']);
    }
}
