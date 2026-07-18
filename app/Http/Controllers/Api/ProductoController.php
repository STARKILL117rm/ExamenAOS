<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of all productos.
     *
     * GET /api/productos
     */
    public function index(): JsonResponse
    {
        $productos = Producto::with('categoria')->get();

        return response()->json([
            'mensaje' => 'Lista de productos',
            'productos' => $productos,
        ], 200);
    }

    /**
     * Display the specified producto.
     *
     * GET /api/productos/{id}
     */
    public function show(int $id): JsonResponse
    {
        $producto = Producto::with('categoria')->find($id);

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

    /**
     * Store a newly created producto.
     *
     * POST /api/productos
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
        ]);

        $producto = Producto::create($validated);
        $producto->load('categoria');

        return response()->json([
            'mensaje' => 'Producto creado exitosamente',
            'producto' => $producto,
        ], 201);
    }
}
