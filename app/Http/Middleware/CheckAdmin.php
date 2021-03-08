<?php

namespace App\Http\Middleware;

use Closure;
use Gate;
use Illuminate\Http\Request;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Gate::allows('is-admin', auth()->user())){
            return $next($request);
        } else {
            abort('403');
        }
    }
}
