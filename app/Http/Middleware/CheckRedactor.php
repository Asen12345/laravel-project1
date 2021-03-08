<?php

namespace App\Http\Middleware;

use App\Eloquent\Permission;
use Closure;
use Illuminate\Http\Request;

class CheckRedactor
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $parameter
     * @return mixed
     */
    public function handle($request, Closure $next, $parameter)
    {
        $user = auth()->user();
        if (auth()->user()->role == 'admin') {
            return $next($request);
        }

        $permission = Permission::where('name_id', $parameter)->first();
        if ($user->permissions->contains('permission_id', $permission->id)) {
            return $next($request);
        } else {
            abort('403');
        }
    }
}
