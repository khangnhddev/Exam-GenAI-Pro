<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!$request->user() || !$request->user()->hasPermissionTo($permission, 'sanctum')) {
            return response()->json([
                'message' => 'Unauthorized access',
                'error' => 'You do not have the required permissions'
            ], 403);
        }

        return $next($request);
    }
}