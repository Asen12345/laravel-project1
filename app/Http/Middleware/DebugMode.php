<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DebugMode
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
        $mode = \App\Eloquent\DebugMode::first();
        if ($mode->debug == true) {
            return abort(503);
        }
        return $next($request);
    }
}
