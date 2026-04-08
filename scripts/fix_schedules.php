<?php

/**
 * Script para verificar y corregir el sistema de horarios
 * 
 * Este script:
 * 1. Verifica si la tabla place_schedules existe
 * 2. Ejecuta la migración si falta
 * 3. Agrega horarios de ejemplo a lugares sin horarios
 * 
 * Ejecutar desde la raíz: php scripts/fix_schedules.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Place;
use App\Models\PlaceSchedule;

echo "=== Verificación y Corrección del Sistema de Horarios ===\n\n";

// Paso 1: Verificar si existe la tabla
echo " Verificando tabla place_schedules...\n";
$tableExists = Schema::hasTable('place_schedules');

if (!$tableExists) {
    echo "    La tabla no existe. Ejecutando migración...\n";
    try {
        Artisan::call('migrate', ['--path' => 'database/migrations/2026_01_12_162646_create_place_schedules_table.php']);
        echo "    Migración ejecutada correctamente.\n\n";
        $tableExists = true;
    } catch (\Exception $e) {
        echo "    Error al ejecutar migración: " . $e->getMessage() . "\n";
        echo "    Intenta ejecutar manualmente: php artisan migrate\n";
        exit(1);
    }
} else {
    echo "    La tabla existe.\n\n";
}

// Paso 2: Verificar cuántos lugares tienen horarios
echo " Verificando lugares con horarios...\n";
$placesWithSchedules = Place::whereHas('schedules')->count();
$totalPlaces = Place::count();
$placesWithoutSchedules = $totalPlaces - $placesWithSchedules;

echo "    Total de lugares: {$totalPlaces}\n";
echo "    Lugares con horarios: {$placesWithSchedules}\n";
echo "     Lugares sin horarios: {$placesWithoutSchedules}\n\n";

// Paso 3: Agregar horarios a lugares que no los tienen
if ($placesWithoutSchedules > 0) {
    echo " Agregando horarios de ejemplo a lugares sin horarios...\n";
    
    $places = Place::whereDoesntHave('schedules')->get();
    
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
        
        echo "    Horarios agregados a: {$place->name}\n";
    }
    
    echo "\n    Se agregaron horarios a {$placesWithoutSchedules} lugares.\n\n";
} else {
    echo "  Todos los lugares ya tienen horarios configurados.\n\n";
}

// Paso 4: Resumen final
echo "=== Resumen Final ===\n";
$totalSchedules = PlaceSchedule::where('activo', true)->count();
echo " Total de horarios activos: {$totalSchedules}\n";
echo " Lugares con horarios: " . Place::whereHas('schedules')->count() . " / {$totalPlaces}\n\n";

echo " Sistema de horarios verificado y corregido.\n";
echo " Los horarios pueden ser modificados desde el panel de administración.\n\n";
