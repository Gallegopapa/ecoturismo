<?php
// Script para reparar la relación entre lugares y categorías
// Asocia lugares a categorías según el campo categories en la tabla places (si existe)
// o deja preparado para asociar manualmente si no hay datos

use Illuminate\Support\Facades\DB;
use App\Models\Place;
use App\Models\Category;

require __DIR__.'/vendor/autoload.php';

// Cargar el framework
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Iniciar contexto de consola
$kernel->bootstrap();

// --- INICIO DEL SCRIPT ---
echo "\n--- Reparando relaciones lugares <-> categorías ---\n";

$allPlaces = Place::all();
$allCategories = Category::all();

echo "\n--- LISTA DE LUGARES ---\n";
foreach ($allPlaces as $p) {
    echo "ID: {$p->id} | Nombre: {$p->name}\n";
}

echo "\n--- LISTA DE CATEGORÍAS ---\n";
foreach ($allCategories as $c) {
    echo "ID: {$c->id} | Nombre: {$c->name}\n";
}
// Mapea manualmente los IDs de lugares a los IDs de categorías correspondientes
$lugaresCategorias = [
    1 => [1],   // Parque Nacional Natural Tatamá - Parques y Más
    2 => [1],
    3 => [3],
    4 => [3],
    5 => [1],
    6 => [1],
    7 => [2],
    8 => [2],
    9 => [2],
    10 => [2],
    11 => [2],
    12 => [2],
    13 => [2],
    14 => [2],
    15 => [3],
    16 => [3],
    17 => [3],
    18 => [3],
    19 => [3],
    20 => [3],
    21 => [1],
    22 => [1],
    23 => [3],
    24 => [2],
    26 => [1],
    27 => [1],
    28 => [1],
    29 => [1],
    30 => [3],
    31 => [3],
];

$reparadas = 0;
$sinCategorias = 0;
foreach ($lugaresCategorias as $placeId => $catIds) {
    echo "Procesando lugar ID: $placeId\n";
    $place = Place::find($placeId);
    if (!$place) {
        echo "Lugar con ID $placeId no encontrado.\n";
        continue;
    }
    if (count($catIds) > 0) {
        $place->categories()->sync($catIds);
        $reparadas++;
        echo "Lugar #{$place->id} ({$place->name}) asociado a categorías: [".implode(',', $catIds)."]\n";
    } else {
        $sinCategorias++;
        echo "Lugar #{$place->id} ({$place->name}) sin categorías detectadas.\n";
    }
}
echo "\nRelaciones reparadas: $reparadas\nLugares sin categorías: $sinCategorias\n";

echo "\n--- Fin del script ---\n";
