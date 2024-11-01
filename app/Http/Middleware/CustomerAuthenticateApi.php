<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CustomerAuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('customers')->check()) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'status' => 401,
            'message' => 'Unauthorized as customer'
        ], 401);
    }
}
 