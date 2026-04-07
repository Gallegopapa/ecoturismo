<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleCors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $configuredOrigins = array_filter(array_map('trim', explode(',', (string) env('CORS_ALLOWED_ORIGINS', ''))));

        // Orígenes permitidos (desarrollo + configurables por entorno)
        $allowedOrigins = array_unique(array_filter([
            ...$configuredOrigins,
            rtrim((string) env('FRONTEND_URL', ''), '/'),
            rtrim((string) config('app.url', ''), '/'),
            'http://localhost:8000',
            'http://127.0.0.1:8000',
            'http://localhost:5173',
            'http://127.0.0.1:5173',
            'http://localhost:3000',
            'http://127.0.0.1:3000',
        ]));

        $origin = $request->headers->get('Origin');

        // Si el origen está permitido, usarlo; si no, usar APP_URL/FRONTEND_URL como fallback
        $fallbackOrigin = rtrim((string) env('FRONTEND_URL', config('app.url', 'http://localhost:8000')), '/');
        $allowedOrigin = in_array($origin, $allowedOrigins, true) ? $origin : $fallbackOrigin;

        // Manejar preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $allowedOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept, X-Requested-With')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400');
        }

        $response = $next($request);

        // Compatible con Response y BinaryFileResponse
        $response->headers->set('Access-Control-Allow-Origin', $allowedOrigin);
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}

