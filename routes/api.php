<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EcohotelController;

// ============================================
// RUTAS PÚBLICAS (sin autenticación)
// ============================================

// US-AUTH-01: Registro de Usuarios
Route::post('/register', [AuthController::class, 'register']);

// US-PLCS-03: Explorar Ecohoteles (Público)
Route::get('/ecohotels', [EcohotelController::class, 'index']);
Route::get('/ecohotels/{id}', [EcohotelController::class, 'show']);

// ============================================
// RUTAS PROTEGIDAS (requieren autenticación con Sanctum)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    // US-ADMN-02: Gestión de Ecohoteles (Admin CRUD)
    Route::get('/admin/ecohotels', [EcohotelController::class, 'index']); // Misma lógica que index público pero servida para admin
    Route::post('/admin/ecohotels', [EcohotelController::class, 'store']);
    Route::post('/admin/ecohotels/{ecohotel}', [EcohotelController::class, 'update']); // Usamos POST para multipart/form-data con _method=PUT
    Route::delete('/admin/ecohotels/{id}', [EcohotelController::class, 'destroy']);
    
    // Espacio para rutas protegidas en el futuro
});

