#!/usr/bin/env php
<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo " SOLUCIONANDO PROBLEMA DE IMÁGENES\n";
echo str_repeat("=", 60) . "\n\n";

// Lugares con imágenes que no existen
$placesWithMissingImages = [23, 24, 26, 27, 28, 29];

foreach ($placesWithMissingImages as $id) {
    $place = \Illuminate\Support\Facades\DB::table('places')->where('id', $id)->first();
    
    if ($place && $place->image) {
        $imagePath = storage_path('app/public/' . str_replace('/storage/', '', $place->image));
        
        if (!file_exists($imagePath)) {
            echo "ID {$id}: {$place->name}\n";
            echo "   Imagen no existe: {$place->image}\n";
            echo "  ✓ Limpiando ruta de BD...\n";
            
            // Limpiar la ruta de imagen en la BD
            \Illuminate\Support\Facades\DB::table('places')
                ->where('id', $id)
                ->update(['image' => null]);
            
            echo "   Listo. Ahora debe subir una imagen nuevamente.\n\n";
        }
    }
}

echo str_repeat("=", 60) . "\n";
echo " PROCESO COMPLETADO\n\n";
echo "INSTRUCCIONES PARA TUS COMPAÑEROS:\n";
echo "1. Ir al panel de empresa\n";
echo "2. Editar cada lugar\n";
echo "3. Subir la imagen nuevamente\n";
echo "4. Guardar\n\n";
echo "Lugares que necesitan imagen:\n";
foreach ($placesWithMissingImages as $id) {
    $place = \Illuminate\Support\Facades\DB::table('places')->where('id', $id)->first();
    if ($place) {
        echo "  - ID {$id}: {$place->name}\n";
    }
}
