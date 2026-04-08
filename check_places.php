<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = App\Models\Place::count();
echo "Total de lugares: " . $count . PHP_EOL;

if ($count > 0) {
    echo "\nPrimeros 10 lugares:" . PHP_EOL;
    $places = App\Models\Place::with('categories')
        ->select('id', 'name', 'location', 'image')
        ->limit(10)
        ->get();
    
    foreach ($places as $p) {
        echo "\nID: {$p->id} - {$p->name}";
        echo "\n  Ubicación: {$p->location}";
        echo "\n  Imagen: {$p->image}";
        echo "\n  Categorías: " . $p->categories->pluck('name')->join(', ');
    }
} else {
    echo "No hay lugares en la base de datos." . PHP_EOL;
}

echo PHP_EOL;
