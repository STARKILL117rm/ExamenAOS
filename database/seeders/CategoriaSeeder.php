<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Electrónica',
                'descripcion' => 'Dispositivos electrónicos, gadgets y accesorios tecnológicos.',
            ],
            [
                'nombre' => 'Ropa',
                'descripcion' => 'Prendas de vestir para hombre, mujer y niños.',
            ],
            [
                'nombre' => 'Alimentos',
                'descripcion' => 'Productos alimenticios y bebidas.',
            ],
            [
                'nombre' => 'Hogar',
                'descripcion' => 'Artículos para el hogar, muebles y decoración.',
            ],
            [
                'nombre' => 'Deportes',
                'descripcion' => 'Equipamiento y accesorios deportivos.',
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
