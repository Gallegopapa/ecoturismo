<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Place;
use App\Models\PlaceSchedule;

class AddSampleSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedules:add-sample';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agregar horarios de ejemplo a todos los lugares existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Agregando horarios de ejemplo a los lugares...');

        $places = Place::all();
        
        if ($places->isEmpty()) {
            $this->warn('No hay lugares en la base de datos. Crea algunos lugares primero.');
            return;
        }

        $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        $horarios = [
            ['inicio' => '08:00', 'fin' => '12:00'],
            ['inicio' => '14:00', 'fin' => '18:00'],
        ];

        $totalHorarios = 0;

        foreach ($places as $place) {
            // Verificar si ya tiene horarios
            if ($place->schedules()->count() > 0) {
                $this->line("    {$place->name} ya tiene horarios configurados. Omitiendo...");
                continue;
            }

            // Agregar horarios de lunes a sábado
            foreach ($diasSemana as $dia) {
                foreach ($horarios as $horario) {
                    PlaceSchedule::create([
                        'place_id' => $place->id,
                        'dia_semana' => $dia,
                        'hora_inicio' => $horario['inicio'],
                        'hora_fin' => $horario['fin'],
                        'activo' => true,
                    ]);
                    $totalHorarios++;
                }
            }

            $this->info("   Horarios agregados a: {$place->name}");
        }

        $this->info("\n Proceso completado. Se agregaron {$totalHorarios} horarios en total.");
        $this->info("Los lugares ahora tienen horarios de 8:00 AM - 12:00 PM y 2:00 PM - 6:00 PM de lunes a sábado.");
    }
}
