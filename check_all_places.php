<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$places = \DB::table('places')->select('id', 'name', 'image')->orderBy('id')->get();

echo "Total de lugares: " . count($places) . "\n\n";

foreach ($places as $place) {
    echo "ID: {$place->id} | Nombre: {$place->name} | Imagen: " . ($place->image ?? 'NULL') . "\n";
}
