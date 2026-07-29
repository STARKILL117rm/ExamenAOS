<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
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

    /**
     * Devuelve el inventario consolidado uniendo Categorías (PostgreSQL) 
     * con sus Productos (MongoDB).
     */
    public function inventarioCompleto(): JsonResponse
    {
        // 1. Obtener categorías desde PostgreSQL
        $categorias = Categoria::all();

        // 2. Mapear e incorporar productos desde MongoDB
        $resultado = $categorias->map(function ($categoria) {
            $productos = Producto::where('categoria_id', (int)$categoria->id)->get();

            return [
                'categoria_id'     => $categoria->id,
                'nombre_categoria' => $categoria->nombre,
                'descripcion'      => $categoria->descripcion,
                'total_productos'  => $productos->count(),
                'productos'        => $productos,
            ];
        });

        return response()->json([
            'mensaje' => 'Consolidado híbrido de inventario (PostgreSQL + MongoDB)',
            'data'    => $resultado,
        ], 200);
    }
}