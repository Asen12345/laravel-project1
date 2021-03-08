<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if ($guard == "admin" && Auth::guard($guard)->check()) {
            return redirect('/admin/dashboard');
        }
        if ($guard == "user" && Auth::guard($guard)->check()) {
            return redirect('/mortgage-rates-banks');
        }

        return $next($request);
    }
}
