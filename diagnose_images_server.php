<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "DIAGNÓSTICO DE IMÁGENES EN SERVIDOR\n";
echo "========================================\n\n";

// 1. Verificar configuración de APP_URL
echo " CONFIGURACIÓN DEL SERVIDOR\n";
echo "   APP_URL: " . config('app.url') . "\n";
echo "   APP_ENV: " . config('app.env') . "\n";
echo "   APP_DEBUG: " . (config('app.debug') ? 'true' : 'false') . "\n\n";

// 2. Verificar symlink
echo " VERIFICAR SYMLINK\n";
$publicSymlink = public_path('storage');
echo "   public/storage existe: " . (is_link($publicSymlink) ? "✓ SÍ (symlink)" : (is_dir($publicSymlink) ? "✓ SÍ (carpeta)" : "✗ NO")) . "\n";
if (is_link($publicSymlink)) {
    echo "   Apunta a: " . readlink($publicSymlink) . "\n";
}
echo "\n";

// 3. Verificar directorios de almacenamiento
echo " DIRECTORIOS DE ALMACENAMIENTO\n";
$storagePlaces = storage_path('app/public/places');
$publicImagenes = public_path('imagenes');

echo "   storage/app/public/places existe: " . (is_dir($storagePlaces) ? "✓ SÍ" : "✗ NO") . "\n";
if (is_dir($storagePlaces)) {
    $files = count(glob($storagePlaces . '/*'));
    echo "   Archivos en storage/places: $files\n";
}

echo "   public/imagenes existe: " . (is_dir($publicImagenes) ? "✓ SÍ" : "✗ NO") . "\n";
if (is_dir($publicImagenes)) {
    $files = count(glob($publicImagenes . '/*'));
    echo "   Archivos en public/imagenes: $files\n";
}
echo "\n";

// 4. Verificar imágenes de lugares en BD
echo " LUGARES SIN IMAGEN O CON IMAGEN ROTA\n";
echo str_repeat("-", 80) . "\n";

$places = \DB::table('places')->select('id', 'name', 'image')->orderBy('id')->get();

$problemas = [];
$ok = [];

foreach ($places as $place) {
    if (!$place->image) {
        $problemas[] = [
            'id' => $place->id,
            'name' => $place->name,
            'problema' => 'SIN IMAGEN',
            'ruta_bd' => null
        ];
        continue;
    }

    $imagePath = $place->image;
    
    // Verificar dónde existe el archivo
    $existeEnPublic = file_exists(public_path($imagePath));
    $existeEnStorage = $imagePath !== '/imagenes/' && file_exists(storage_path('app/public/' . str_replace('/storage/', '', $imagePath)));
    
    if (!$existeEnPublic && !$existeEnStorage) {
        $problemas[] = [
            'id' => $place->id,
            'name' => $place->name,
            'problema' => 'RUTA INVÁLIDA',
            'ruta_bd' => $imagePath
        ];
    } else {
        $ok[] = $place->id;
    }
}

if (count($problemas) > 0) {
    echo " ENCONTRADOS PROBLEMAS:\n\n";
    foreach ($problemas as $p) {
        echo "ID {$p['id']}: {$p['name']}\n";
        echo "  └─ Problema: {$p['problema']}\n";
        if ($p['ruta_bd']) {
            echo "  └─ Ruta en BD: {$p['ruta_bd']}\n";
        }
        echo "\n";
    }
} else {
    echo "✓ TODAS LAS IMÁGENES ESTÁN OK\n";
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "RESUMEN:\n";
echo "Total de lugares: " . count($places) . "\n";
echo "Imágenes OK: " . count($ok) . "\n";
echo "Imágenes con problema: " . count($problemas) . "\n";
if (count($places) > 0) {
    echo "Porcentaje OK: " . round((count($ok) / count($places)) * 100, 1) . "%\n";
}
echo "\n";

// 5. Recomendaciones
echo " RECOMENDACIONES\n";
echo str_repeat("-", 80) . "\n";

if (count($problemas) > 0) {
    echo "✓ Ejecuta: php artisan storage:link\n";
    echo "✓ Ejecuta: php migrate_images_to_relative.php\n";
    echo "✓ Ejecuta: php artisan config:clear && php artisan cache:clear\n";
} else {
    echo "✓ No hay problemas detectados\n";
}

echo "\n";
