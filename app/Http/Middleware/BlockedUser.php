<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockedUser
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
        if (auth()->check()) {
            if (auth()->user()->block == true){
                auth()->logout();
                return redirect()->route('front.home')->withErrors([
                    'error' => 'Ваш аккаунт заблокирован.'
                ]);
            } else {
                return $next($request);
            }
        } else {
            return $next($request);
        }

    }
}
