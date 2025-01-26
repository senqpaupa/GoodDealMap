<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestDebugMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Входящий запрос:', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'data' => $request->all(),
            'headers' => $request->headers->all(),
            'files' => $request->allFiles()
        ]);

        $response = $next($request);

        // Добавляем CORS заголовки
        $response->headers->set('Access-Control-Allow-Origin', $request->header('Origin'));
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
} 