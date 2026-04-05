<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  $roles
     * @return Response|JsonResponse
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = $request->user();

        if (!$user || !$user->is_active) {
            return response()->json(['message' => 'Unauthorized. User not active.'], 401);
        }

        $allowed = array_map('trim', explode(',', $roles));

        if (!in_array($user->role, $allowed, true)) {
            return response()->json(['message' => 'Forbidden. Insufficient permissions.'], 403);
        }

        return $next($request);
    }
}
