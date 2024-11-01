<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            // Redirect to login page if not authenticated
            return redirect()->route('auth-login');
        }

        $role = Auth::user()->roles->pluck('name')[0];

        // // Check if the user is a customer
        if ($role === 'customer') {
            return $next($request);
        }elseif ($role === 'provider') {
            return $next($request);
        }elseif ($role === 'admin') {
            return $next($request);
        }else {
            return abort(403, 'Unauthorized access.');
        }

    }
}

