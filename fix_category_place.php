<?php
// Script para poblar category_place según la lógica del seeder
use Illuminate\Support\Facades\DB;
use App\Models\Place;
use App\Models\Category;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$categorias = Category::all();
$lugares = Place::all();

$asociaciones = 0;
foreach ($lugares as $lugar) {
    $nombre = strtolower($lugar->name);
    $categoriasAsociar = [];
    foreach ($categorias as $categoria) {
        if (
            ($categoria->slug == 'parques-y-mas' && preg_match('/parque|jard[ií]n|bot[aá]nico/', $nombre)) ||
            ($categoria->slug == 'paraisos-acuaticos' && preg_match('/lago|laguna|r[ií]o|cascada|balneario|termales|acu[aá]tico/', $nombre)) ||
            ($categoria->slug == 'lugares-montanosos' && preg_match('/alto|cerro|reserva|mirador|divisa/', $nombre))
        ) {
            $categoriasAsociar[] = $categoria->id;
        }
    }
    if (!empty($categoriasAsociar)) {
        $lugar->categories()->sync($categoriasAsociar);
        $asociaciones++;
        echo "Lugar #{$lugar->id} ({$lugar->name}) asociado a categorías: [".implode(',', $categoriasAsociar)."]\n";
    }
}
echo "\nTotal asociaciones realizadas: $asociaciones\n";
