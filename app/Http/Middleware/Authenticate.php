<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // For web routes, you can still redirect to the login route
            // For example: return route('login');
            return null;
        }

        return response()->json(['error' => 'Unauthenticated'], 401);
    }
}
