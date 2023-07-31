<?php

namespace App\Http\Middleware;

use App\Models\SearchLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogSearch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->filled('keyword')) {
            $searchKeyword = $request->input('keyword');

            // If you have an authenticated user, get their ID
            $userId = Auth::id(); // If the user is not authenticated, $userId will be null

            // Create a new SearchLog instance
            $searchLog = new SearchLog();
            $searchLog->keyword = $searchKeyword;
            $searchLog->user_id = $userId;
            $searchLog->save();
        }

        return $response;
    }
}
