<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Place;
use App\Models\PlaceSchedule;

class FixPlaceSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedules:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica y corrige el sistema de horarios: ejecuta la migración si falta y agrega horarios a lugares sin horarios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Verificación y Corrección del Sistema de Horarios ===');
        $this->newLine();

        // Paso 1: Verificar si existe la tabla
        $this->info(' Verificando tabla place_schedules...');
        $tableExists = Schema::hasTable('place_schedules');

        if (!$tableExists) {
            $this->error('    La tabla no existe.');
            $this->info('   Intentando ejecutar migración...');
            
            try {
                // Intentar ejecutar la migración específica
                $exitCode = $this->call('migrate', [
                    '--path' => 'database/migrations/2026_01_12_162646_create_place_schedules_table.php',
                    '--force' => true
                ]);
                
                // Verificar nuevamente si la tabla existe
                $tableExists = Schema::hasTable('place_schedules');
                
                if ($tableExists) {
                    $this->info('    Migración ejecutada correctamente.');
                    $this->newLine();
                } else {
                    throw new \Exception('La migración se ejecutó pero la tabla no se creó.');
                }
            } catch (\Exception $e) {
                $this->warn('     Error al ejecutar migración: ' . $e->getMessage());
                $this->info('   Intentando crear la tabla directamente...');
                
                // Crear la tabla directamente si la migración falla
                try {
                    Schema::create('place_schedules', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->id();
                        $table->unsignedBigInteger('place_id');
                        $table->enum('dia_semana', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
                        $table->time('hora_inicio');
                        $table->time('hora_fin');
                        $table->boolean('activo')->default(true);
                        $table->timestamps();

                        $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
                        $table->index(['place_id', 'dia_semana']);
                    });
                    
                    $this->info('    Tabla creada directamente.');
                    $this->newLine();
                    $tableExists = true;
                } catch (\Exception $e2) {
                    $this->error('    Error al crear la tabla: ' . $e2->getMessage());
                    $this->newLine();
                    $this->error('    Por favor, verifica:');
                    $this->line('      1. Que la tabla "places" existe');
                    $this->line('      2. Que tienes permisos para crear tablas');
                    $this->line('      3. Que la base de datos está configurada correctamente');
                    $this->newLine();
                    return 1;
                }
            }
        } else {
            $this->info('    La tabla existe.');
            $this->newLine();
        }

        // Paso 2: Verificar cuántos lugares tienen horarios
        $this->info(' Verificando lugares con horarios...');
        $placesWithSchedules = Place::whereHas('schedules')->count();
        $totalPlaces = Place::count();
        $placesWithoutSchedules = $totalPlaces - $placesWithSchedules;

        $this->line("    Total de lugares: {$totalPlaces}");
        $this->line("    Lugares con horarios: {$placesWithSchedules}");
        $this->line("     Lugares sin horarios: {$placesWithoutSchedules}");
        $this->newLine();

        // Paso 3: Agregar horarios a lugares que no los tienen
        if ($placesWithoutSchedules > 0) {
            $this->info(' Agregando horarios de ejemplo a lugares sin horarios...');
            
            $places = Place::whereDoesntHave('schedules')->get();
            
            $bar = $this->output->createProgressBar($places->count());
            $bar->start();
            
            foreach ($places as $place) {
                // Horarios de lunes a viernes: 08:00 - 18:00
                $schedules = [
                    ['dia_semana' => 'lunes', 'hora_inicio' => '08:00', 'hora_fin' => '18:00'],
                    ['dia_semana' => 'martes', 'hora_inicio' => '08:00', 'hora_fin' => '18:00'],
                    ['dia_semana' => 'miercoles', 'hora_inicio' => '08:00', 'hora_fin' => '18:00'],
                    ['dia_semana' => 'jueves', 'hora_inicio' => '08:00', 'hora_fin' => '18:00'],
                    ['dia_semana' => 'viernes', 'hora_inicio' => '08:00', 'hora_fin' => '18:00'],
                    ['dia_semana' => 'sabado', 'hora_inicio' => '08:00', 'hora_fin' => '16:00'],
                    ['dia_semana' => 'domingo', 'hora_inicio' => '09:00', 'hora_fin' => '15:00'],
                ];
                
                foreach ($schedules as $schedule) {
                    PlaceSchedule::create([
                        'place_id' => $place->id,
                        'dia_semana' => $schedule['dia_semana'],
                        'hora_inicio' => $schedule['hora_inicio'],
                        'hora_fin' => $schedule['hora_fin'],
                        'activo' => true,
                    ]);
                }
                
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine(2);
            $this->info("    Se agregaron horarios a {$placesWithoutSchedules} lugares.");
            $this->newLine();
        } else {
            $this->info('  Todos los lugares ya tienen horarios configurados.');
            $this->newLine();
        }

        // Paso 4: Resumen final
        $this->info('=== Resumen Final ===');
        $totalSchedules = PlaceSchedule::where('activo', true)->count();
        $this->line(" Total de horarios activos: {$totalSchedules}");
        $this->line(" Lugares con horarios: " . Place::whereHas('schedules')->count() . " / {$totalPlaces}");
        $this->newLine();

        $this->info(' Sistema de horarios verificado y corregido.');
        $this->info(' Los horarios pueden ser modificados desde el panel de administración.');
        $this->newLine();

        return 0;
    }
}
