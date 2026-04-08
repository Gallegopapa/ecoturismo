<?php

/**
 * Script para migrar URLs absolutas de imágenes a rutas relativas
 * Esto hace que el sistema sea independiente de APP_URL
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=======================================================\n";
echo "MIGRANDO URLs ABSOLUTAS A RUTAS RELATIVAS\n";
echo "=======================================================\n\n";

// Obtener todos los lugares con imágenes
$places = \App\Models\Place::whereNotNull('image')->get();

$total = $places->count();
$migrated = 0;
$alreadyRelative = 0;

foreach ($places as $place) {
    $originalImage = $place->getRawOriginal('image');
    
    if (empty($originalImage)) {
        continue;
    }
    
    // Si ya es una ruta relativa, no hacer nada
    if (strpos($originalImage, '/imagenes/') === 0 || strpos($originalImage, '/storage/') === 0) {
        $alreadyRelative++;
        echo "✓ ID {$place->id} ya tiene ruta relativa: {$originalImage}\n";
        continue;
    }
    
    // Si es una URL absoluta, extraer solo la ruta
    if (preg_match('/^https?:\/\/[^\/]+(.*)$/', $originalImage, $matches)) {
        $relativePath = $matches[1];
        
        // Actualizar directamente en la base de datos (sin pasar por el accessor)
        \DB::table('places')
            ->where('id', $place->id)
            ->update(['image' => $relativePath]);
        
        $migrated++;
        echo "→ ID {$place->id} | {$place->name}\n";
        echo "  DE: {$originalImage}\n";
        echo "  A:  {$relativePath}\n\n";
        continue;
    }
    
    // Si no tiene barra inicial, agregarla
    if (strpos($originalImage, 'imagenes/') === 0 || strpos($originalImage, 'storage/') === 0) {
        $relativePath = '/' . $originalImage;
        
        \DB::table('places')
            ->where('id', $place->id)
            ->update(['image' => $relativePath]);
        
        $migrated++;
        echo "→ ID {$place->id} | {$place->name}\n";
        echo "  DE: {$originalImage}\n";
        echo "  A:  {$relativePath}\n\n";
        continue;
    }
    
    $alreadyRelative++;
    echo "✓ ID {$place->id} formato válido: {$originalImage}\n";
}

echo "\n=======================================================\n";
echo "RESUMEN DE MIGRACIÓN:\n";
echo "=======================================================\n";
echo "Total de lugares procesados: {$total}\n";
echo "Migrados a rutas relativas: {$migrated}\n";
echo "Ya eran rutas relativas: {$alreadyRelative}\n";
echo "\n✓ Migración completada exitosamente\n";
echo "\nAhora ejecuta en el servidor:\n";
echo "  1. php migrate_images_to_relative.php\n";
echo "  2. php artisan config:clear\n";
echo "  3. php artisan optimize:clear\n";
echo "=======================================================\n";
