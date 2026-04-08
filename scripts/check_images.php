#!/usr/bin/env php
<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Consultar lugares
$places = \Illuminate\Support\Facades\DB::table('places')
    ->where('id', '>=', 23)
    ->where('id', '<=', 30)
    ->select('id', 'name', 'image')
    ->get();

echo "Lugares encontrados: " . count($places) . "\n\n";

foreach ($places as $place) {
    echo "ID: {$place->id}\n";
    echo "Nombre: {$place->name}\n";
    echo "Ruta imagen DB: " . ($place->image ?? 'NULL') . "\n";
    
    // Verificar si el archivo existe
    if ($place->image) {
        // Revisar en storage/app/public
        $storagePath = storage_path('app/public/' . str_replace('storage/', '', $place->image));
        echo "Existe en storage: " . (file_exists($storagePath) ? "SÍ ✓" : "NO ✗") . "\n";
        
        // Revisar en public/storage
        $publicPath = public_path($place->image);
        echo "Existe en public: " . (file_exists($publicPath) ? "SÍ ✓" : "NO ✗") . "\n";
    } else {
        echo "⚠ Sin imagen asignada\n";
    }
    
    echo "\n" . str_repeat("-", 60) . "\n\n";
}

echo "\nVerificar enlace simbólico:\n";
$symlinkPath = public_path('storage');
if (is_link($symlinkPath)) {
    echo "✓ El enlace simbólico existe en public/storage\n";
    echo "  Apunta a: " . readlink($symlinkPath) . "\n";
} else {
    echo "✗ NO existe el enlace simbólico en public/storage\n";
    echo "  Ejecuta: php artisan storage:link\n";
}
