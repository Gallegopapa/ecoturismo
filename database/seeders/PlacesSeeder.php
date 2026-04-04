<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Place;
use App\Models\Category;

class PlacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear o obtener categorías
        $categoriaParques = Category::firstOrCreate(
            ['slug' => 'parques-y-mas'],
            [
                'name' => 'Parques y Más',
                'description' => 'Parques naturales, regionales y jardines botánicos',
                'icon' => ''
            ]
        );

        $categoriaAcuaticos = Category::firstOrCreate(
            ['slug' => 'paraisos-acuaticos'],
            [
                'name' => 'Paraísos Acuáticos',
                'description' => 'Lagos, lagunas, ríos, cascadas y balnearios',
                'icon' => ''
            ]
        );

        $categoriaMontanosos = Category::firstOrCreate(
            ['slug' => 'lugares-montanosos'],
            [
                'name' => 'Lugares Montañosos',
                'description' => 'Cerros, altos, reservas naturales y miradores',
                'icon' => ''
            ]
        );

        // Solo crear categorías, los lugares se insertan en PlaceSeeder
        $this->command->info('Categorías creadas exitosamente. Ejecuta PlaceSeeder para poblar los lugares.');
    }
}
