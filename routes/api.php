<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ProjectController; // <-- 1. Import the Project Controller

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

// Public Authentication Routes
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

// --- PROTECTED ROUTES (Requires Bearer Token) ---
Route::middleware('auth:sanctum')->group(function(){
    // Basic User Management (Accessible by anyone authenticated? Usually only Admins/HR)
    // NOTE: You might want to add role middleware here too, e.g., 'role:1,2'
    Route::get('/get-users', [UserController::class, 'getUsers']);
    Route::post('/add-user', [UserController::class, 'addUser']);
    Route::put('/edit-user/{id}', [UserController::class, 'editUser']);
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
});

// --- ROLE-BASED PROJECT ROUTES (New Section) ---
// Project Creation is restricted to CEO/Admin (Role 1) and HR (Role 2)
// NOTE: Assuming your role_id for CEO/Admin is 1 and HR is 2. Adjust if different.
Route::middleware('auth:sanctum', 'role:1,2')->group(function () {
    // POST /api/projects: Route to create a new project
    Route::post('/projects', [ProjectController::class, 'store']);
    
    // You'll likely want a GET route here too, maybe restricted to these roles
    // Route::get('/projects', [ProjectController::class, 'index']);
});

// --- EXAMPLE: Team Leader/Manager Route ---
// A route group for tasks visible only to Team Leaders/Managers (Role 3)
// Route::middleware('auth:sanctum', 'role:3')->group(function () {
//     Route::get('/tasks-to-assign', [TaskController::class, 'getAssignableTasks']);
// });