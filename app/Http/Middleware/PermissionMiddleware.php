<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = $request->user();
        if (!$user) abort(401);

        // si envías varios, lo normal es "cualquiera"
        $ok = $user->canAny($permissions);

        if (!$ok) abort(403);

        return $next($request);
    }
}
