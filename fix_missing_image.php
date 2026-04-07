<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Actualizar la imagen faltante
\DB::table('places')
    ->where('id', 23)
    ->update(['image' => '/imagenes/parquecafe-3.jpg']);

echo "✓ ID 23 (Voladero El Zarzo) actualizada con imagen: /imagenes/parquecafe-3.jpg\n";
