<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$places = DB::table('places')
    ->where('id', '>=', 23)
    ->where('id', '<=', 30)
    ->select('id', 'name', 'image')
    ->get();

echo "Lugares encontrados:\n\n";

foreach ($places as $place) {
    echo "ID: {$place->id}\n";
    echo "Nombre: {$place->name}\n";
    echo "Ruta imagen: {$place->image}\n";
    
    // Verificar si el archivo existe
    if ($place->image) {
        $fullPath = public_path($place->image);
        $storagePath = storage_path('app/public/' . str_replace('storage/', '', $place->image));
        
        echo "Ruta pública: {$fullPath}\n";
        echo "Existe en public: " . (file_exists($fullPath) ? "SÍ" : "NO") . "\n";
        echo "Ruta storage: {$storagePath}\n";
        echo "Existe en storage: " . (file_exists($storagePath) ? "SÍ" : "NO") . "\n";
    } else {
        echo "Sin imagen asignada\n";
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}
