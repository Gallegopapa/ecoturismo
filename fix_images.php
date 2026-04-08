<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Mapeo manual de lugares a imágenes disponibles
$mapping = [
    1 => '/imagenes/tatama.jpg', // Parque Nacional Natural Tatamá
    2 => '/imagenes/araucarias.jpg', // Parque Las Araucarias
    3 => '/imagenes/cuchillasanjuan2.webp', // Cuchilla de San Juan
    4 => '/imagenes/santaemilia2.jpg', // Santa Emilia
    5 => '/imagenes/jardin.jpeg', // Jardín Botánico UTP
    6 => '/imagenes/jardinmarsella2.jpg', // Jardín Botánico De Marsella
    7 => '/imagenes/lago-2.jpg', // Lago De La Pradera
    8 => '/imagenes/laguna.jpg', // La Laguna Del Otún
    9 => '/imagenes/lolo-2.jpg', // Chorros De Don Lolo
    10 => '/imagenes/termales.jpg', // Termales de Santa Rosa
    11 => '/imagenes/consota.jpg', // Parque Acuático Consota
    12 => '/imagenes/farallones.jpeg', // Balneario Los Farallones
    13 => '/imagenes/frailes.jpeg', // Cascada Los Frailes
    14 => '/imagenes/sanjose.webp', // Río San José
    15 => '/imagenes/nudo.jpg', // Alto Del Nudo
    16 => '/imagenes/toro.jpg', // Alto Del Toro
    17 => '/imagenes/divisa.jpg', // La Divisa De Don Juan
    18 => '/imagenes/batero.jpg', // Cerro Batero
    19 => '/imagenes/lanona.jpg', // Reserva Forestal La Nona
    20 => '/imagenes/gobia.jpg', // Reserva Natural Cerro Gobia
    21 => '/imagenes/kaukita.jpeg', // Kaukitá Bosque Reserva
    22 => '/imagenes/horizonte.jpg', // Reserva Natural DMI Agualinda
    23 => '/imagenes/parquepalm.jpg', // Voladero El Zarzo
    24 => '/imagenes/termales-2.jpg', // Termales de San Vicente
    27 => '/imagenes/parquecafe.jpg', // Parque Bioflora Los Rosales
    28 => '/imagenes/parquecafe-2.jpg', // Bioparque Mariposario
    29 => '/imagenes/consota-2.webp', // Santuario Otún Quimbaya
    30 => '/imagenes/frailes2.webp', // Barbas Bremen
    31 => '/imagenes/piedras.jpg', // Piedras marcadas
];

foreach ($mapping as $id => $imagePath) {
    \DB::table('places')
        ->where('id', $id)
        ->update(['image' => $imagePath]);
    
    echo "Actualizado ID {$id} con imagen: {$imagePath}\n";
}

echo "\n✓ Todas las imágenes han sido actualizadas\n";
