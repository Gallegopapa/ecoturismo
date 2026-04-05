<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$mapeo = [
    "Lago De La Pradera" => "/imagenes/Lago.jpeg",
    "La Laguna Del Otún" => "/imagenes/laguna.jpg",
    "Laguna Del Otún" => "/imagenes/laguna.jpg",
    "Chorros De Don Lolo" => "/imagenes/lolo-2.jpg",
    "Termales de Santa Rosa" => "/imagenes/termaales.jpg",
    "Parque Acuático Consota" => "/imagenes/consota.jpg",
    "Balneario Los Farallones" => "/imagenes/farallones.jpeg",
    "Cascada Los Frailes" => "/imagenes/frailes3.jpg",
    "Río San José" => "/imagenes/sanjose3.jpg",
    "Rio San Jose" => "/imagenes/sanjose3.jpg",
    "Alto Del Nudo" => "/imagenes/nudo.jpg",
    "Alto Del Toro" => "/imagenes/toro.jpg",
    "La Divisa De Don Juan" => "/imagenes/divisa3.jpeg",
    "Cerro Batero" => "/imagenes/batero.jpg",
    "Reserva Forestal La Nona" => "/imagenes/lanona5.jpg",
    "Reserva Natural Cerro Gobia" => "/imagenes/gobia.jpg",
    "Kaukitá Bosque Reserva" => "/imagenes/kaukita3.jpg",
    "Kaukita Bosque Reserva" => "/imagenes/kaukita3.jpg",
    "Reserva Natural DMI Agualinda" => "/imagenes/distritomanejo8.jpg",
    "Parque Nacional Natural Tatamá" => "/imagenes/tatama.jpg",
    "Parque Nacional Natural Tatama" => "/imagenes/tatama.jpg",
    "Parque Las Araucarias" => "/imagenes/araucarias.jpg",
    "Parque Regional Natural Cuchilla de San Juan" => "/imagenes/cuchilla.jpg",
    "Parque Natural Regional Santa Emilia" => "/imagenes/santaemilia2.jpg",
    "Jardín Botánico UTP" => "/imagenes/jardin.jpeg",
    "Jardin Botanico UTP" => "/imagenes/jardin.jpeg",
    "Jardín Botánico De Marsella" => "/imagenes/jardinmarsella2.jpg",
    "Jardin Botanico De Marsella" => "/imagenes/jardinmarsella2.jpg",
    "Piedras marcadas" => "/imagenes/piedras5.jpg",
    "Piedras Marcadas" => "/imagenes/piedras5.jpg",
    "Barbas Bremen" => "/imagenes/paisaje5.jpg",
    "Santuario Otún Quimbaya" => "/imagenes/paisaje2.jpg",
    "Santuario Otun Quimbaya" => "/imagenes/paisaje2.jpg",
    "Bioparque Mariposario Bonita Farm" => "/imagenes/ukumari.jpg",
    "Parque Bioflora En Finca Turística Los Rosales" => "/imagenes/parquecafe.jpg",
    "Parque Bioflora En Finca Turistica Los Rosales" => "/imagenes/parquecafe.jpg",
    "Eco Hotel Paraiso Real" => "/imagenes/paisaje4.jpg",
    "Termales de San Vicente" => "/imagenes/termales.jpg",
    "Voladero El Zarzo" => "/imagenes/mirador5.jpg",
];

$places = \DB::table('places')->get();
$fixed = 0;

foreach ($places as $place) {
    if (isset($mapeo[$place->name])) {
        $realImage = $mapeo[$place->name];
        if ($place->image !== $realImage) {
            \DB::table('places')->where('id', $place->id)->update(['image' => $realImage]);
            echo "Arreglado: {$place->name} => {$realImage}\n";
            $fixed++;
        }
    } else {
        // En un caso que no cruce como "Lago de la Pradera" vs "Lago De La Pradera" (case sensitive)
        // intentaremos un cruce insensible a mayúsculas
        foreach ($mapeo as $key => $image) {
            if (strtolower(trim($place->name)) === strtolower(trim($key))) {
                if ($place->image !== $image) {
                    \DB::table('places')->where('id', $place->id)->update(['image' => $image]);
                    echo "Arreglado (CaseInsensitive): {$place->name} => {$image}\n";
                    $fixed++;
                }
                break;
            }
        }
    }
}

echo "Se arreglaron/actualizaron $fixed lugares desenredando sus imágenes.\n";
