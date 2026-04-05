<?php

namespace Database\Seeders;

use App\Models\Usuarios;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            PlaceSeeder::class,
            UsuarioSeeder::class, // Poblar todos los usuarios
            PlaceScheduleSeeder::class,
            RejectionReasonsSeeder::class,
            EcohotelSeeder::class, // Poblar los ecohoteles
            // PlacesSeeder::class, // Descomentar si PlacesSeeder se usa en lugar/adición a PlaceSeeder
        ]);
    }
}