<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de las categorías
        $electronica = Categoria::where('nombre', 'Electrónica')->first()->id;
        $ropa        = Categoria::where('nombre', 'Ropa')->first()->id;
        $alimentos   = Categoria::where('nombre', 'Alimentos')->first()->id;
        $hogar       = Categoria::where('nombre', 'Hogar')->first()->id;
        $deportes    = Categoria::where('nombre', 'Deportes')->first()->id;

        $productos = [
            [
                'categoria_id' => $electronica,
                'nombre'       => 'Laptop HP Pavilion 15',
                'descripcion'  => 'Laptop con procesador Intel Core i5, 8GB RAM, 256GB SSD.',
                'precio'       => 12999.99,
                'stock'        => 15,
            ],
            [
                'categoria_id' => $electronica,
                'nombre'       => 'Audífonos Bluetooth Sony WH-1000XM5',
                'descripcion'  => 'Audífonos inalámbricos con cancelación de ruido activa.',
                'precio'       => 5499.50,
                'stock'        => 30,
            ],
            [
                'categoria_id' => $ropa,
                'nombre'       => 'Camiseta Nike Dri-FIT',
                'descripcion'  => 'Camiseta deportiva de secado rápido, talla M.',
                'precio'       => 599.00,
                'stock'        => 50,
            ],
            [
                'categoria_id' => $ropa,
                'nombre'       => 'Jeans Levi\'s 501 Original',
                'descripcion'  => 'Jeans clásicos de corte recto, color azul índigo.',
                'precio'       => 1299.00,
                'stock'        => 25,
            ],
            [
                'categoria_id' => $alimentos,
                'nombre'       => 'Café Molido Veracruz 1kg',
                'descripcion'  => 'Café de altura 100% arábica, tostado medio.',
                'precio'       => 189.90,
                'stock'        => 100,
            ],
            [
                'categoria_id' => $alimentos,
                'nombre'       => 'Aceite de Oliva Extra Virgen 500ml',
                'descripcion'  => 'Aceite de oliva importado de España, prensado en frío.',
                'precio'       => 149.50,
                'stock'        => 40,
            ],
            [
                'categoria_id' => $hogar,
                'nombre'       => 'Lámpara LED de Escritorio',
                'descripcion'  => 'Lámpara regulable con 3 niveles de brillo y puerto USB.',
                'precio'       => 459.00,
                'stock'        => 35,
            ],
            [
                'categoria_id' => $hogar,
                'nombre'       => 'Juego de Sábanas Queen Size',
                'descripcion'  => 'Sábanas de algodón egipcio 400 hilos, color blanco.',
                'precio'       => 899.00,
                'stock'        => 20,
            ],
            [
                'categoria_id' => $deportes,
                'nombre'       => 'Balón de Fútbol Adidas Copa',
                'descripcion'  => 'Balón oficial tamaño 5, cosido a máquina.',
                'precio'       => 349.99,
                'stock'        => 60,
            ],
            [
                'categoria_id' => $deportes,
                'nombre'       => 'Mancuernas Ajustables 20kg',
                'descripcion'  => 'Par de mancuernas con discos ajustables de 1kg a 20kg.',
                'precio'       => 1599.00,
                'stock'        => 10,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
