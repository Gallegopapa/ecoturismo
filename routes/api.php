<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

// ============================================
// RUTAS PÚBLICAS (sin autenticación)
// ============================================

// US-AUTH-01: Registro de Usuarios
Route::post('/register', [AuthController::class, 'register']);

// ============================================
// RUTAS PROTEGIDAS (requieren autenticación con Sanctum)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    // Espacio para rutas protegidas en el futuro
});