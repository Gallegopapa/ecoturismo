<?php

/**
 * Script para verificar el estado de las migraciones
 * Ejecutar desde la raíz del proyecto: php scripts/check_migrations.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Verificación de Migraciones ===\n\n";

// Verificar si existe la tabla place_schedules
$tableExists = Schema::hasTable('place_schedules');

if ($tableExists) {
    echo " La tabla 'place_schedules' existe.\n\n";
    
    // Contar registros
    $count = DB::table('place_schedules')->count();
    echo " Registros en place_schedules: {$count}\n\n";
    
    if ($count > 0) {
        echo " Primeros 5 registros:\n";
        $schedules = DB::table('place_schedules')
            ->join('places', 'place_schedules.place_id', '=', 'places.id')
            ->select('place_schedules.*', 'places.name as place_name')
            ->limit(5)
            ->get();
        
        foreach ($schedules as $schedule) {
            echo "  - Lugar: {$schedule->place_name} | Día: {$schedule->dia_semana} | Horario: {$schedule->hora_inicio} - {$schedule->hora_fin} | Activo: " . ($schedule->activo ? 'Sí' : 'No') . "\n";
        }
    } else {
        echo "  La tabla existe pero está vacía. Necesitas agregar horarios a los lugares.\n";
    }
    
    // Verificar estructura de la tabla
    echo "\n Estructura de la tabla:\n";
    $columns = DB::select("DESCRIBE place_schedules");
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
    }
} else {
    echo " La tabla 'place_schedules' NO existe.\n";
    echo " Necesitas ejecutar: php artisan migrate\n";
}

// Verificar lugares sin horarios
echo "\n=== Lugares sin horarios ===\n";
$placesWithoutSchedules = DB::table('places')
    ->leftJoin('place_schedules', 'places.id', '=', 'place_schedules.place_id')
    ->whereNull('place_schedules.id')
    ->select('places.id', 'places.name')
    ->get();

if ($placesWithoutSchedules->count() > 0) {
    echo "  Lugares sin horarios configurados: {$placesWithoutSchedules->count()}\n";
    foreach ($placesWithoutSchedules as $place) {
        echo "  - ID: {$place->id} | Nombre: {$place->name}\n";
    }
} else {
    echo " Todos los lugares tienen al menos un horario configurado.\n";
}

echo "\n=== Fin de verificación ===\n";
