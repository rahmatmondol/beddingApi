<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return $request->expectsJson() ? abort(401) : redirect()->route('login');
        }

        if (strpos($request->url(), 'logout') !== false) {
            return $next($request);
        }
        
        $role = Auth::user()->roles->pluck('name')[0];

        // // Check if the user is a customer
        if ($role !== 'admin') {
            return abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
