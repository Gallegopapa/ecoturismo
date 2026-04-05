<?php
require 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\Usuarios;

echo "=== VERIFICANDO ACCESO AL DASHBOARD ===\n\n";

// Obtener el usuario empresa
$user = Usuarios::where('tipo_usuario', 'empresa')->first();

if (!$user) {
    echo " No existe usuario tipo 'empresa'\n";
    exit(1);
}

echo " Usuario empresa encontrado: {$user->email}\n";
echo "   ID: {$user->id}\n";
echo "   Tipo: {$user->tipo_usuario}\n\n";

// Verificar que tenga lugares asignados
$places = $user->placesManaged()->get();
echo " Lugares asignados: " . count($places) . "\n";
foreach ($places as $place) {
    echo "   - {$place->name} (ID: {$place->id})\n";
}

echo "\n";

// Verificar reservas de esa empresa
$reservations = \DB::table('company_reservations')
    ->where('company_user_id', $user->id)
    ->get();

echo " Reservas en company_reservations:\n";
foreach ($reservations as $res) {
    echo "   - Reservation ID: {$res->reservation_id}, Estado: {$res->estado}\n";
}

// Ahora simular lo que haría el controlador
echo "\n=== SIMULANDO LÓGICA DEL CONTROLADOR ===\n\n";

$placesManaged = $user->placesManaged()->get();
$placeIds = $placesManaged->pluck('id')->toArray();

echo "IDs de lugares: " . json_encode($placeIds) . "\n\n";

// Obtener todas las reservas de esos lugares
$companyReservations = \App\Models\CompanyReservation::whereIn('place_id', $placeIds)
    ->with('reservation')
    ->get();

echo " CompanyReservations encontradas: " . count($companyReservations) . "\n";
foreach ($companyReservations as $compRes) {
    echo "   - ID: {$compRes->id}, Estado: {$compRes->estado}, Reservation ID: {$compRes->reservation_id}\n";
}

// Contar por estado
$stats = \App\Models\CompanyReservation::whereIn('place_id', $placeIds)
    ->selectRaw('estado, COUNT(*) as count')
    ->groupBy('estado')
    ->get();

echo "\n Estadísticas:\n";
foreach ($stats as $stat) {
    echo "   - {$stat->estado}: {$stat->count}\n";
}

echo "\n Verificación completada exitosamente\n";
