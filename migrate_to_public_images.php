<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "MIGRAR IMÁGENES A RUTAS ACCESIBLES\n";
echo "========================================\n\n";

// Mapeo completo de lugares a imágenes que existen en /imagenes/
$mapeoImagenesExistentes = [
    // Paraísos Acuáticos
    1 => '/imagenes/Lago.jpeg',                     // Lago De La Pradera
    2 => '/imagenes/laguna.jpg',                    // La Laguna Del Otún
    3 => '/imagenes/lolo-2.jpg',                    // Chorros De Don Lolo
    4 => '/imagenes/termaales.jpg',                 // Termales de Santa Rosa
    5 => '/imagenes/consota.jpg',                   // Parque Acuático Consota
    6 => '/imagenes/farallones.jpeg',               // Balneario Los Farallones
    7 => '/imagenes/frailes3.jpg',                  // Cascada Los Frailes
    8 => '/imagenes/sanjose3.jpg',                  // Río San José
    27 => '/imagenes/nudo.jpg',                     // Alto Del Nudo
    29 => '/imagenes/toro.jpg',                     // Alto Del Toro
    
    // Montaña
    9 => '/imagenes/nudo3.jpg',                     // Alto De Los Andes
    10 => '/imagenes/cuchillasanjuan2.webp',        // Cuchilla San Juan
    11 => '/imagenes/paisaje5.jpg',                 // Páramo De Letras
    12 => '/imagenes/tatama.jpg',                   // Reserva Natural Tatamá
    13 => '/imagenes/nudo4.jpg',                    // Nevado De Santa Isabel
    
    // Bosque Húmedo
    14 => '/imagenes/frailes7.webp',                // Bosque Nublado De Filandia
    15 => '/imagenes/frailes8.webp',                // Sendero Otún Quimbaya
    16 => '/imagenes/consota-4.webp',               // Parque Nacional Natural De Los Nevados
    17 => '/imagenes/paisaje4.jpg',                 // Reserva Natural San Jorge
    18 => '/imagenes/paisaje3.jpg',                 // Bosque Seco Tropical
    
    // Parques y Más
    19 => '/imagenes/jardin4.jpg',                  // Recinto del Pensamiento
    20 => '/imagenes/jardinmarsella4.jpg',          // Jardín Botánico Lankester
    21 => '/imagenes/lolo-4.webp',                  // Zoológico Matecaña
    22 => '/imagenes/parquecafe-3.jpg',             // Parque Arví
    23 => '/imagenes/ukumari1.jpg',                 // Ecoparque Ukumarí
    24 => '/imagenes/parquecafe.jpg',               // Bioflora En Finca Turística Los Rosales
    25 => '/imagenes/paisaje4.jpg',                 // Eco Hotel Paraiso Real
    28 => '/imagenes/ukumari.jpg',                  // Bioparque Mariposario Bonita Farm
];

$updated = 0;
$errors = 0;
$unchanged = 0;

// Obtener todos los lugares
$places = \DB::table('places')->select('id', 'name', 'image')->orderBy('id')->get();

echo "Procesando " . count($places) . " lugares...\n";
echo "Objetivo: Usar solo imágenes de /imagenes/ que existen\n\n";

foreach ($places as $place) {
    // Buscar imagen en el mapeo por ID
    if (!isset($mapeoImagenesExistentes[$place->id])) {
        $unchanged++;
        continue;
    }

    $imagenNueva = $mapeoImagenesExistentes[$place->id];
    
    // Verificar que la imagen nueva existe de verdad
    $publicPath = public_path($imagenNueva);
    if (!file_exists($publicPath)) {
        echo " ID {$place->id}: {$place->name}\n";
        echo "   └─ Imagen NO existe: {$imagenNueva} ({$publicPath})\n";
        $errors++;
        continue;
    }
    
    // Si la imagen ya es la correcta, saltar
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
        if ($place->image && $place->image !== $imagenNueva) {
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
echo "Errores: $errors\n";
echo "\n";

if ($updated > 0) {
    echo " PRÓXIMOS PASOS:\n";
    echo "   1. Sube los cambios: git add . && git commit -m 'Fix: Migrar imágenes a rutas /imagenes/'\n";
    echo "   2. En el servidor: git pull\n";
    echo "   3. Limpia caché: php artisan config:clear && php artisan cache:clear\n";
    echo "   4. Las imágenes deberían mostrarse inmediatamente\n";
} else {
    echo "✓ Las imágenes ya están correctamente configuradas\n";
}

echo "\n";
