<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(): JsonResponse
    {
        $productos = Producto::all();

        return response()->json([
            'mensaje' => 'Lista de productos (desde MongoDB)',
            'productos' => $productos,
        ], 200);
    }

    public function show(string $id): JsonResponse
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'mensaje' => 'Producto no encontrado',
            ], 404);
        }

        return response()->json([
            'mensaje' => 'Detalle del producto',
            'producto' => $producto,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:pgsql.categorias,id',
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
        ]);

        $producto = Producto::create($validated);

        return response()->json([
            'mensaje' => 'Producto creado exitosamente en MongoDB',
            'producto' => $producto,
        ], 201);
    }
}