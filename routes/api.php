<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas para el API REST del Sistema de Inventario SOA.
| Prefijo automático: /api
|
*/

Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);
Route::post('/productos', [ProductoController::class, 'store']);
Route::get('/inventario-completo', [ProductoController::class, 'inventarioCompleto']);