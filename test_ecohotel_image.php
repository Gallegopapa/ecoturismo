<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ecohotel = App\Models\Ecohotel::first();
if ($ecohotel) {
    file_put_contents('test_ecohotel_image.json', json_encode($ecohotel->toArray(), JSON_PRETTY_PRINT));
} else {
    file_put_contents('test_ecohotel_image.json', 'No ecohotels found');
}
echo "Done\n";
