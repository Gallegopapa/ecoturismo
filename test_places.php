<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$places = \App\Models\Place::all();
foreach ($places as $place) {
    if (stripos($place->name, 'Bonita') !== false || stripos($place->name, 'Mariposario') !== false) {
        echo "ID: " . $place->id . "\n";
        echo "Name: '" . $place->name . "'\n";
        echo "Image: '" . $place->image . "'\n";
        echo "Location: '" . $place->location . "'\n";
    }
}
