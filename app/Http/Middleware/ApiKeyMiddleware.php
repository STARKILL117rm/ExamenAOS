<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');
        $validKey = env('INTERNAL_API_KEY', 'SecretTokenSOA2026');

        if (!$apiKey || $apiKey !== $validKey) {
            return response()->json([
                'error' => 'No autorizado',
                'mensaje' => 'API Key no válida o ausente en el encabezado X-API-KEY'
            ], 401);
        }

        return $next($request);
    }
}