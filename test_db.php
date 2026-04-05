<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ecohotels = App\Models\Ecohotel::orderBy('id', 'desc')->take(3)->get(['id', 'name', 'image', 'created_at', 'updated_at']);
file_put_contents('test_db.json', json_encode($ecohotels->toArray(), JSON_PRETTY_PRINT));
echo "Done\n";
