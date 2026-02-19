<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,staff')   ← allows either role
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // First check: is the user logged in at all?
        if (! $request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Second check: does the user's role match any of the allowed roles?
        if (! in_array($request->user()->role, $roles)) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to access this resource.',
                'your_role'     => $request->user()->role,
                'required_roles' => $roles,
            ], 403);
        }

        return $next($request);
    }
}