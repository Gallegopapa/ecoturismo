<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "FIX DEFINITIVO: ASIGNAR IMÁGENES LOCALES\n";
echo "========================================\n\n";

// MAPEO DEFINITIVO: Todos los lugares con imágenes que EXISTEN en /imagenes/
$mapeoImagenesLocales = [
    'Parque Nacional Natural Tatamá' => '/imagenes/tatama.jpg',
    'Parque Las Araucarias' => '/imagenes/araucarias.jpg',
    'Parque Regional Natural Cuchilla de San Juan' => '/imagenes/cuchillasanjuan2.webp',
    'Parque Natural Regional Santa Emilia' => '/imagenes/santaemilia2.jpg',
    'Jardín Botánico UTP' => '/imagenes/jardin.jpeg',
    'Jardín Botánico De Marsella' => '/imagenes/jardinmarsella2.jpg',
    'Lago De La Pradera' => '/imagenes/Lago.jpeg',
    'La Laguna Del Otún' => '/imagenes/laguna.jpg',
    'Chorros De Don Lolo' => '/imagenes/lolo-2.jpg',
    'Termales de Santa Rosa' => '/imagenes/termaales.jpg',
    'Parque Acuático Consota' => '/imagenes/consota.jpg',
    'Balneario Los Farallones' => '/imagenes/farallones.jpeg',
    'Cascada Los Frailes' => '/imagenes/frailes3.jpg',
    'Río San José' => '/imagenes/sanjose3.jpg',
    'Alto Del Nudo' => '/imagenes/nudo.jpg',
    'Alto Del Toro' => '/imagenes/toro.jpg',
    'La Divisa De Don Juan' => '/imagenes/divisa.jpg',
    'Cerro Batero' => '/imagenes/batero.jpg',
    'Reserva Forestal La Nona' => '/imagenes/lanona.jpg',
    'Reserva Natural Cerro Gobia' => '/imagenes/gobia.jpg',
    'Kaukitá Bosque Reserva' => '/imagenes/kaukita.jpeg',
    'Reserva Natural DMI Agualinda' => '/imagenes/paisaje2.jpg',
    'Voladero El Zarzo' => '/imagenes/mirador5.jpg',
    'Termales de San Vicente' => '/imagenes/termales.jpg',
    'Eco Hotel Paraiso Real' => '/imagenes/paisaje4.jpg',
    'Parque Bioflora En Finca Turística Los Rosales' => '/imagenes/parquecafe.jpg',
    'Bioparque Mariposario Bonita Farm' => '/imagenes/ukumari.jpg',
    'Santuario Otún Quimbaya' => '/imagenes/frailes8.webp',
    'Barbas Bremen' => '/imagenes/frailes7.webp',
    'Piedras marcadas' => '/imagenes/piedras3.jpg',
];

$updated = 0;
$errors = 0;
$unchanged = 0;
$notfound = 0;

// Obtener todos los lugares
$places = \DB::table('places')->select('id', 'name', 'image')->get();

echo "Procesando " . count($places) . " lugares...\n";
echo "Asignando imágenes locales a todos...\n\n";

foreach ($places as $place) {
    // Buscar imagen en el mapeo
    $imagenNueva = $mapeoImagenesLocales[$place->name] ?? null;
    
    if (!$imagenNueva) {
        echo "  ID {$place->id}: {$place->name} - NO ENCONTRADO EN MAPEO\n";
        $notfound++;
        continue;
    }
    
    // Verificar que la imagen existe
    $publicPath = public_path($imagenNueva);
    if (!file_exists($publicPath)) {
        echo "✗ ID {$place->id}: {$place->name}\n";
        echo "   └─ Imagen NO existe: {$imagenNueva}\n";
        $errors++;
        continue;
    }
    
    // Si ya tiene esta imagen, saltar
    if ($place->image === $imagenNueva) {
        $unchanged++;
        continue;
    }
    
    // Actualizar
    try {
        \DB::table('places')
            ->where('id', $place->id)
            ->update(['image' => $imagenNueva, 'updated_at' => now()]);
        
        echo "✓ ID {$place->id}: {$place->name}\n";
        if ($place->image && strlen($place->image) > 0) {
            echo "   De: {$place->image}\n";
        }
        echo "   A:  {$imagenNueva}\n";
        $updated++;
    } catch (\Exception $e) {
        echo "✗ ID {$place->id}: {$place->name}\n";
        echo "   Error: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "RESUMEN:\n";
echo "Total de lugares: " . count($places) . "\n";
echo "Actualizadas: $updated\n";
echo "Sin cambios: $unchanged\n";
echo "Errores (imagen no existe): $errors\n";
echo "No encontrados en mapeo: $notfound\n";
echo "Total correctas: " . ($updated + $unchanged) . "\n";
echo "\n";

if ($updated > 0 || $errors > 0) {
    echo " PRÓXIMOS PASOS:\n";
    if ($updated > 0) {
        echo "   ✓ {$updated} lugares actualizados\n";
    }
    if ($errors > 0) {
        echo "     {$errors} lugares con error (imagen no existe)\n";
    }
    if ($notfound > 0) {
        echo "     {$notfound} lugares sin mapeo\n";
    }
    echo "\n   1. Haz push: git add . && git commit -m 'Fix: Asignar imágenes locales a todos los lugares'\n";
    echo "   2. En servidor: git pull\n";
    echo "   3. Ejecuta script: php " . basename(__FILE__) . "\n";
    echo "   4. Limpia caché: php artisan config:clear && php artisan cache:clear\n";
    echo "   5. Verifica en: " . config('app.url') . "\n";
} else {
    echo " TODAS LAS IMÁGENES ESTÁN CORRECTAS\n";
}

echo "\n";
