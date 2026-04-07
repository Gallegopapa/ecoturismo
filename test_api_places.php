<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simular un request a /api/places
$controller = new App\Http\Controllers\API\PlaceController();

// Crear un request mock
$request = \Illuminate\Http\Request::create('/api/places', 'GET');

echo "Testing /api/places endpoint...\n";
echo "==========================================\n\n";

$places = App\Models\Place::with(['categories', 'reviews.usuario:id,name,foto_perfil'])
    ->orderBy('name', 'asc')
    ->get();

echo "Total places returned: " . count($places) . "\n\n";

if (count($places) > 0) {
    echo "First 3 places:\n";
    foreach ($places->take(3) as $place) {
        echo "\n- {$place->name}";
        echo "\n  ID: {$place->id}";
        echo "\n  Categories: " . $place->categories->pluck('name')->join(', ');
    }
    
    // Check JSON encoding
    echo "\n\nJSON encoding test:\n";
    $json = json_encode($places->toArray());
    echo "JSON length: " . strlen($json) . " bytes\n";
    echo "First 200 chars:\n";
    echo substr($json, 0, 200) . "\n";
} else {
    echo "No places found!\n";
}

echo "\n";
