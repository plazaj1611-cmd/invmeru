<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $userRole = session('rol');
        if (!$userRole) {
            abort(403, 'Acceso denegado');
        }

        if ($userRole === 'admin') {
            return $next($request);
        }

        if ($userRole !== $role) {
            abort(403, 'Acceso denegado');
        }

        return $next($request);
    }
}
