<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Place;
use App\Models\PlaceSchedule;
use Illuminate\Support\Facades\DB;

class PlaceScheduleSeeder extends Seeder
{
    /**
     * Seeder para agregar horarios de ejemplo a los lugares existentes
     * 
     * Horarios por defecto:
     * - Lunes a Viernes: 08:00 - 18:00
     * - Sábado: 08:00 - 16:00
     * - Domingo: 09:00 - 15:00
     */
    public function run(): void
    {
        // Obtener todos los lugares que no tienen horarios
        $places = Place::whereDoesntHave('schedules')->get();

        if ($places->isEmpty()) {
            $this->command->info('Todos los lugares ya tienen horarios configurados.');
            return;
        }

        $this->command->info("Agregando horarios a {$places->count()} lugares...");

        foreach ($places as $place) {
            // Horarios de lunes a viernes: 08:00 - 18:00
            PlaceSchedule::create([
                'place_id' => $place->id,
                'dia_semana' => 'lunes',
                'hora_inicio' => '08:00',
                'hora_fin' => '18:00',
                'activo' => true,
            ]);

            PlaceSchedule::create([
                'place_id' => $place->id,
                'dia_semana' => 'martes',
                'hora_inicio' => '08:00',
                'hora_fin' => '18:00',
                'activo' => true,
            ]);

            PlaceSchedule::create([
                'place_id' => $place->id,
                'dia_semana' => 'miercoles',
                'hora_inicio' => '08:00',
                'hora_fin' => '18:00',
                'activo' => true,
            ]);

            PlaceSchedule::create([
                'place_id' => $place->id,
                'dia_semana' => 'jueves',
                'hora_inicio' => '08:00',
                'hora_fin' => '18:00',
                'activo' => true,
            ]);

            PlaceSchedule::create([
                'place_id' => $place->id,
                'dia_semana' => 'viernes',
                'hora_inicio' => '08:00',
                'hora_fin' => '18:00',
                'activo' => true,
            ]);

            // Sábado: 08:00 - 16:00
            PlaceSchedule::create([
                'place_id' => $place->id,
                'dia_semana' => 'sabado',
                'hora_inicio' => '08:00',
                'hora_fin' => '16:00',
                'activo' => true,
            ]);

            // Domingo: 09:00 - 15:00
            PlaceSchedule::create([
                'place_id' => $place->id,
                'dia_semana' => 'domingo',
                'hora_inicio' => '09:00',
                'hora_fin' => '15:00',
                'activo' => true,
            ]);

            $this->command->info(" Horarios agregados a: {$place->name}");
        }

        $this->command->info("\n Seeder completado. Se agregaron horarios a {$places->count()} lugares.");
    }
}
