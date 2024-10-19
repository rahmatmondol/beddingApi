<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ResponseTrait;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApi extends  Middleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return response()->json(['message'=> 'unauthenticated'],401);
        }
    }
    protected function unauthenticated($request, array $guards)
    {
        throw new HttpResponseException(
            $this->responseError(null, 'Unauthenticated access.')
        );
    }
}
