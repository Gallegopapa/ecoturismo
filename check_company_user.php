<?php
require 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Usuarios;

echo "=== VERIFICANDO USUARIO EMPRESA ===\n\n";

// Usuarios con tipo_usuario = 'empresa'
$empresaUsers = Usuarios::where('tipo_usuario', 'empresa')->get();
echo "Usuarios tipo 'empresa':\n";
foreach ($empresaUsers as $user) {
    echo "  - ID: {$user->id}, Email: {$user->email}, Tipo: {$user->tipo_usuario}\n";
}

echo "\n=== VERIFICANDO ASIGNACIÓN A LUGARES ===\n\n";

// Verificar tabla place_company_users
$assignments = DB::table('place_company_users')->get();
echo "Asignaciones en place_company_users:\n";
foreach ($assignments as $assign) {
    echo "  - Place ID: {$assign->place_id}, User ID: {$assign->company_user_id}, Rol: {$assign->rol}\n";
}

echo "\n=== VERIFICANDO RESERVAS PENDIENTES ===\n\n";

// Verificar company_reservations
$companyRes = DB::table('company_reservations')->get();
echo "Company Reservations:\n";
foreach ($companyRes as $res) {
    echo "  - Reservation ID: {$res->reservation_id}, Company User ID: {$res->company_user_id}, Estado: {$res->estado}\n";
}

echo "\n=== VERIFICANDO COMPANY_USER RELATIONSHIPS ===\n\n";

if (!$empresaUsers->isEmpty()) {
    $user = $empresaUsers->first();
    echo "Lugares manejados por usuario ID {$user->id}:\n";
    $places = $user->placesManaged()->get();
    foreach ($places as $place) {
        echo "  - Place ID: {$place->id}, Name: {$place->name}\n";
    }
}

echo "\n Verificación completada\n";
