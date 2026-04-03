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

// US-AUTH-04: Recuperación de Contraseña
Route::post('/password/forgot', [\App\Http\Controllers\API\PasswordResetController::class, 'sendResetLink']);
Route::post('/password/reset', [\App\Http\Controllers\API\PasswordResetController::class, 'resetPassword']);

// US-PROF-01: Ver Perfil (Públicas)
Route::get('/profile/photo/stream', [\App\Http\Controllers\API\ProfileController::class, 'photoByQuery']);
Route::get('/profile/photo/{filename}', [\App\Http\Controllers\API\ProfileController::class, 'photo'])->where('filename', '.*');

// ============================================
// RUTAS PROTEGIDAS (requieren autenticación con Sanctum)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    
    // US-AUTH-03: Logout de Usuarios
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);

    // US-AUTH-05: Token Verification
    Route::get('/user', [AuthController::class, 'me']);
    Route::get('/verify-token', [AuthController::class, 'verifyToken']);

    // US-PROF-01: Perfil de usuario
    Route::get('/profile', [\App\Http\Controllers\API\ProfileController::class, 'show']);
    Route::post('/profile', [\App\Http\Controllers\API\ProfileController::class, 'update']); // POST para FormData con imagen
    Route::put('/profile', [\App\Http\Controllers\API\ProfileController::class, 'update']); // PUT para JSON sin imagen
    Route::put('/profile/password', [\App\Http\Controllers\API\ProfileController::class, 'changePassword']);
    Route::delete('/profile', [\App\Http\Controllers\API\ProfileController::class, 'destroy']); // Eliminar cuenta

});