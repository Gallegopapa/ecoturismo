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

    // US-PROF-02: Actualizar Perfil
    Route::post('/profile', [\App\Http\Controllers\API\ProfileController::class, 'update']); // POST para FormData con imagen
    Route::put('/profile', [\App\Http\Controllers\API\ProfileController::class, 'update']); // PUT para JSON sin imagen

    // US-PROF-03: Cambiar Contraseña
    Route::put('/profile/password', [\App\Http\Controllers\API\ProfileController::class, 'changePassword']);

    // US-PROF-04: Eliminar Cuenta
    Route::delete('/profile', [\App\Http\Controllers\API\ProfileController::class, 'destroy']); // Eliminar cuenta

    // ============================================
    // RUTAS DE EMPRESA (US-COMP-01)
    // ============================================
    Route::prefix('company')->group(function () {
        Route::get('/places', [\App\Http\Controllers\API\CompanyController::class, 'getPlaces']);
        Route::get('/places/{place}', [\App\Http\Controllers\API\CompanyController::class, 'getPlace']);
        Route::put('/places/{place}', [\App\Http\Controllers\API\CompanyController::class, 'updatePlace']);
        
        // Schedules CRUD (US-COMP-03)
        Route::get('/places/{place}/schedules', [\App\Http\Controllers\API\CompanyController::class, 'getSchedules']);
        Route::post('/places/{place}/schedules', [\App\Http\Controllers\API\CompanyController::class, 'storeSchedule']);
        Route::put('/places/{place}/schedules/{schedule}', [\App\Http\Controllers\API\CompanyController::class, 'updateSchedule']);
        Route::delete('/places/{place}/schedules/{schedule}', [\App\Http\Controllers\API\CompanyController::class, 'destroySchedule']);
        
        // Reservations Management (US-COMP-04)
        Route::get('/reservations', [\App\Http\Controllers\API\CompanyController::class, 'getReservations']);
        Route::post('/reservations/{id}/accept', [\App\Http\Controllers\API\CompanyController::class, 'acceptReservation']);
        Route::post('/reservations/{id}/reject', [\App\Http\Controllers\API\CompanyController::class, 'rejectReservation']);
        Route::post('/reservations/{id}/reopen', [\App\Http\Controllers\API\CompanyController::class, 'reopenReservation']);
        Route::get('/rejection-reasons', [\App\Http\Controllers\API\CompanyController::class, 'getRejectionReasons']);
        
        Route::get('/reservations/stats', [\App\Http\Controllers\API\CompanyController::class, 'getReservationStats']);
        Route::get('/reservations/place/{place}/stats', [\App\Http\Controllers\API\CompanyController::class, 'getPlaceReservationStats']);
    });
});