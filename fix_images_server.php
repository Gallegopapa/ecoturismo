<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "ARREGLAR IMÁGENES EN SERVIDOR\n";
echo "========================================\n\n";

// Mapeo de lugares a imágenes en /imagenes/
$mapeoImagenes = [
    // Paraísos Acuáticos
    'Lago De La Pradera' => '/imagenes/Lago.jpeg',
    'La Laguna Del Otún' => '/imagenes/laguna.jpg',
    'Laguna Del Otún' => '/imagenes/laguna.jpg',
    'Chorros De Don Lolo' => '/imagenes/lolo-2.jpg',
    'Termales de Santa Rosa' => '/imagenes/termaales.jpg',
    'Parque Acuático Consota' => '/imagenes/consota.jpg',
    'Balneario Los Farallones' => '/imagenes/farallones.jpeg',
    'Cascada Los Frailes' => '/imagenes/frailes3.jpg',
    'Río San José' => '/imagenes/sanjose3.jpg',
    'Rio San Jose' => '/imagenes/sanjose3.jpg',
    'Alto Del Nudo' => '/imagenes/nudo.jpg',
    'Alto Del Toro' => '/imagenes/toro.jpg',
    
    // Montaña
    'Alto De Los Andes' => '/imagenes/nudo3.jpg',
    'Cuchilla San Juan' => '/imagenes/cuchillasanjuan2.webp',
    'Páramo De Letras' => '/imagenes/paisaje5.jpg',
    'Reserva Natural Tatamá' => '/imagenes/tatama.jpg',
    'Nevado De Santa Isabel' => '/imagenes/nudo4.jpg',
    
    // Bosque Húmedo
    'Bosque Nublado De Filandia' => '/imagenes/frailes7.webp',
    'Sendero Otún Quimbaya' => '/imagenes/frailes8.webp',
    'Parque Nacional Natural De Los Nevados' => '/imagenes/consota-4.webp',
    'Reserva Natural San Jorge' => '/imagenes/paisaje4.jpg',
    'Bosque Seco Tropical' => '/imagenes/paisaje3.jpg',
    
    // Parques y Más
    'Bioparque Mariposario Bonita Farm' => '/imagenes/ukumari.jpg',
    'Recinto del Pensamiento' => '/imagenes/jardin4.jpg',
    'Jardín Botánico Lankester' => '/imagenes/jardinmarsella4.jpg',
    'Zoológico Matecaña' => '/imagenes/lolo-4.webp',
    'Parque Arví' => '/imagenes/parquecafe-3.jpg',
    'Ecoparque Ukumarí' => '/imagenes/ukumari1.jpg',
    'Bioflora En Finca Turística Los Rosales' => '/imagenes/parquecafe.jpg',
    'Parque Bioflora En Finca Turística Los Rosales' => '/imagenes/parquecafe.jpg',
    'Eco Hotel Paraiso Real' => '/imagenes/paisaje4.jpg',
];

$updated = 0;
$errors = 0;
$unchanged = 0;

// Obtener todos los lugares
$places = \DB::table('places')->select('id', 'name', 'image')->orderBy('id')->get();

echo "Procesando " . count($places) . " lugares...\n\n";

foreach ($places as $place) {
    // Buscar imagen en el mapeo
    $imagenNueva = $mapeoImagenes[$place->name] ?? null;
    
    if (!$imagenNueva) {
        $unchanged++;
        continue;
    }
    
    // Verificar que la imagen nueva existe
    if (!file_exists(public_path($imagenNueva))) {
        echo "✗ ID {$place->id}: {$place->name}\n";
        echo "  └─ Imagen no encontrada: $imagenNueva\n";
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
        echo "  └─ De: {$place->image}\n";
        echo "  └─ A: $imagenNueva\n";
        $updated++;
    } catch (\Exception $e) {
        echo "✗ ID {$place->id}: {$place->name}\n";
        echo "  └─ Error: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "RESUMEN:\n";
echo "Total de lugares procesados: " . count($places) . "\n";
echo "Actualizadas: $updated\n";
echo "Sin cambios: $unchanged\n";
echo "Errores: $errors\n";
echo "\n";

if ($updated > 0) {
    echo "✓ Ahora ejecuta en el servidor:\n";
    echo "  - php artisan config:clear\n";
    echo "  - php artisan cache:clear\n";
    echo "  - php artisan view:clear\n";
}

echo "\n";
