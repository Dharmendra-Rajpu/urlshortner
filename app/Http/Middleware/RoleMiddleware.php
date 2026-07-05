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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // User not logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // User role not allowed
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
