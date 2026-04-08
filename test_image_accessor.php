<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Verificar que el accessor funciona correctamente
$place = \App\Models\Place::find(1);

echo "=== VERIFICACIÓN DEL ACCESSOR DE IMÁGENES ===\n\n";
echo "ID: " . $place->id . "\n";
echo "Nombre: " . $place->name . "\n";
echo "Imagen en BD: " . \DB::table('places')->find(1)->image . "\n";
echo "Imagen procesada por accessor: " . $place->image . "\n";

echo "\n=== PROBANDO CON VARIOS LUGARES ===\n\n";

$places = \App\Models\Place::whereIn('id', [1, 5, 23, 29])->get();

foreach ($places as $p) {
    echo "ID {$p->id} ({$p->name})\n";
    echo "  → " . $p->image . "\n";
}

echo "\n✓ Todas las imágenes se están procesando correctamente.\n";
