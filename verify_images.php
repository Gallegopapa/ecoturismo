<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$places = \DB::table('places')->select('id', 'name', 'image')->orderBy('id')->get();

echo "Verificando que todas las imágenes existan:\n\n";

$total = 0;
$existentes = 0;
$faltantes = 0;

foreach ($places as $place) {
    $total++;
    if ($place->image) {
        // La imagen es una ruta relativa, verificar en public/
        $imagePath = public_path($place->image);
        $exists = file_exists($imagePath);
        
        if ($exists) {
            echo "✓ ID {$place->id} | {$place->name}\n";
            echo "  └─ {$place->image}\n";
            $existentes++;
        } else {
            echo "✗ ID {$place->id} | {$place->name}\n";
            echo "  └─ {$place->image} (NO ENCONTRADA)\n";
            echo "     Buscó en: {$imagePath}\n";
            $faltantes++;
        }
    } else {
        echo "⚠ ID {$place->id} | {$place->name} - SIN IMAGEN\n";
        $faltantes++;
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "RESUMEN:\n";
echo "Total de lugares: {$total}\n";
echo "Imágenes existentes: {$existentes}\n";
echo "Imágenes faltantes: {$faltantes}\n";
echo "Porcentaje conseguido: " . round(($existentes/$total)*100, 1) . "%\n";
