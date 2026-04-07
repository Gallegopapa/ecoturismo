<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ecohotel = App\Models\Ecohotel::orderBy('id', 'desc')->first();
file_put_contents('test_latest.json', json_encode($ecohotel ? $ecohotel->toArray() : ['message' => 'No ecohotels found']));
echo "Doneeee\n";
