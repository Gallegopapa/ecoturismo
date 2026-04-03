<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

// ============================================
// RUTAS PÚBLICAS (sin autenticación)
// ============================================

// US-AUTH-01: Registro de Usuarios
Route::post('/register', [AuthController::class, 'register']);

// US-AUTH-02: Login de Usuarios
Route::post('/login', [AuthController::class, 'login']);

// ============================================
// RUTAS PROTEGIDAS (requieren autenticación con Sanctum)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    // Espacio para rutas protegidas en el futuro
});