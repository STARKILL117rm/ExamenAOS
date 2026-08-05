<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Middleware\ApiKeyMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas para el API REST del Sistema de Inventario SOA.
| Prefijo automático: /api
|
*/

// Rutas públicas de productos (MongoDB)
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);
Route::post('/productos', [ProductoController::class, 'store']);

// Ruta protegida por API Key para la comunicación inter-servicios
Route::middleware([ApiKeyMiddleware::class])->group(function () {
    Route::get('/inventario-completo', [ProductoController::class, 'inventarioCompleto']);
});