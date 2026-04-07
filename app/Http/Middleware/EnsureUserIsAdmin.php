<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener usuario autenticado (funciona tanto para web como API)
        $user = Auth::user() ?? $request->user();

        if (!$user || !$user->is_admin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acceso denegado. Se requieren permisos de administrador.',
                ], 403);
            }

            abort(403, 'Acceso denegado. Se requieren permisos de administrador.');
        }

        return $next($request);
    }
}

