<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\PlaceController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\EcohotelController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\PlaceScheduleController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ============================================
// RUTAS PÚBLICAS (sin autenticación)
// ============================================

// Autenticación y Recuperación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [PasswordResetController::class, 'sendResetLink']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);

// Perfil e Imágenes
Route::get('/profile/photo/stream', [ProfileController::class, 'photoByQuery']);
Route::get('/profile/photo/{filename}', [ProfileController::class, 'photo'])->where('filename', '.*');

// Exploración de Lugares (US-PLCS-01, US-PLCS-02)
Route::get('/places', [PlaceController::class, 'index']);
Route::get('/places/options', [PlaceController::class, 'options']);
Route::get('/places/{place}', [PlaceController::class, 'show']);
Route::get('/places/{place}/available-schedules', [PlaceController::class, 'getAvailableSchedules']);
Route::get('/places/{place}/schedules', [PlaceScheduleController::class, 'index']);
Route::get('/places/{id}/reviews', function($id, Request $request) {
    return app(ReviewController::class)->index($request, 'place', $id);
});

// Categorías
Route::get('/categories', [CategoryController::class, 'index']);

// Exploración de Ecohoteles (US-PLCS-03)
Route::get('/ecohotels', [EcohotelController::class, 'index']);
Route::get('/ecohotels/{ecohotel}', [EcohotelController::class, 'show']);
Route::get('/ecohotels/{id}/reviews', function($id, Request $request) {
    return app(ReviewController::class)->index($request, 'ecohotel', $id);
});

// ============================================
// RUTAS PROTEGIDAS (requieren autenticación con Sanctum)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    
    // Gestión de Sesión
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::get('/verify-token', [AuthController::class, 'verifyToken']);

    // Gestión del Perfil (US-PROF)
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']); // Multi-part
    Route::put('/profile', [ProfileController::class, 'update']);  // JSON
    Route::put('/profile/password', [ProfileController::class, 'changePassword']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // Reservas de Usuario (US-RES)
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations/my', [ReservationController::class, 'myReservations']);
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);

    // Reseñas de Usuario (US-REV)
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    // ============================================
    // MÓDULO DE EMPRESA (US-COMP)
    // ============================================
    Route::prefix('company')->group(function () {
        Route::get('/places', [CompanyController::class, 'getPlaces']);
        Route::get('/places/{place}', [CompanyController::class, 'getPlace']);
        Route::put('/places/{place}', [CompanyController::class, 'updatePlace']);
        
        // Horarios
        Route::get('/places/{place}/schedules', [CompanyController::class, 'getSchedules']);
        Route::post('/places/{place}/schedules', [CompanyController::class, 'storeSchedule']);
        Route::put('/places/{place}/schedules/{schedule}', [CompanyController::class, 'updateSchedule']);
        Route::delete('/places/{place}/schedules/{schedule}', [CompanyController::class, 'destroySchedule']);
        
        // Reservas
        Route::get('/reservations', [CompanyController::class, 'getReservations']);
        Route::post('/reservations/{id}/accept', [CompanyController::class, 'acceptReservation']);
        Route::post('/reservations/{id}/reject', [CompanyController::class, 'rejectReservation']);
        Route::post('/reservations/{id}/reopen', [CompanyController::class, 'reopenReservation']);
        Route::get('/rejection-reasons', [CompanyController::class, 'getRejectionReasons']);
        
        // Estadísticas
        Route::get('/reservations/stats', [CompanyController::class, 'getReservationStats']);
        Route::get('/reservations/place/{place}/stats', [CompanyController::class, 'getPlaceReservationStats']);
    });

    // ============================================
    // MÓDULO ADMINISTRATIVO (US-ADMN)
    // ============================================
    Route::prefix('admin')->group(function () {
        // Gestión de Usuarios (US-ADMN-03)
        Route::get('/users', [\App\Http\Controllers\API\AdminUserController::class, 'index']);
        Route::post('/users', [\App\Http\Controllers\API\AdminUserController::class, 'store']);
        Route::put('/users/{id}', [\App\Http\Controllers\API\AdminUserController::class, 'update']);
        Route::delete('/users/{id}', [\App\Http\Controllers\API\AdminUserController::class, 'destroy']);

        // Gestión de Lugares
        Route::apiResource('places', PlaceController::class);

        // Gestión de Reservas (US-ADMN-05)
        Route::get('/reservations', [ReservationController::class, 'all']);
        Route::post('/reservations', [ReservationController::class, 'store']);
        Route::put('/reservations/{reservation}', [ReservationController::class, 'update']);
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);
        
        // Gestión de Ecohoteles (US-ADMN-02)

        Route::get('/ecohotels', [EcohotelController::class, 'index']);
        Route::post('/ecohotels', [EcohotelController::class, 'store']);
        Route::post('/ecohotels/{ecohotel}', [EcohotelController::class, 'update']); // Spoofing para multipart
        Route::delete('/ecohotels/{id}', [EcohotelController::class, 'destroy']);
    });
});
