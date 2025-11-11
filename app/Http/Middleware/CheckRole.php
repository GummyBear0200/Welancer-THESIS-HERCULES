<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  array $roles The list of allowed role IDs from the route definition (e.g., '1', '2')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check 1: Is the user authenticated?
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Get the role_id of the currently authenticated user
        $userRoleId = auth()->user()->role_id;

        // Check 2: Is the user's role ID in the list of allowed roles?
        // Note: The $roles argument is now correctly passed from the route middleware call (e.g., '1', '2')
        if (!in_array($userRoleId, $roles)) {
            // Role ID 3 (Team Leader) is NOT in [1, 2], so access should be denied.
            return response()->json(['message' => 'Access Denied. Insufficient privileges.'], 403);
        }

        return $next($request);
    
    }
}