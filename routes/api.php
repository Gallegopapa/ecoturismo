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

// US-PLCS-01: Explorar Lugares Ecoturísticos (Públicas)
Route::get('/places', [\App\Http\Controllers\API\PlaceController::class, 'index']);
Route::get('/places/options', [\App\Http\Controllers\API\PlaceController::class, 'options']);
Route::get('/categories', [\App\Http\Controllers\API\CategoryController::class, 'index']);
Route::get('/places/{place}', [\App\Http\Controllers\API\PlaceController::class, 'show']);

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

    // US-RES-01: Crear una Reserva
    Route::post('/reservations', [\App\Http\Controllers\API\ReservationController::class, 'store']);

    // US-REV-01: Crear una Reseña
    Route::post('/reviews', [\App\Http\Controllers\API\ReviewController::class, 'store']);

    // US-REV-02: Editar Reseña
    Route::put('/reviews/{review}', [\App\Http\Controllers\API\ReviewController::class, 'update']);

    // US-REV-03: Eliminar Reseña
    Route::delete('/reviews/{review}', [\App\Http\Controllers\API\ReviewController::class, 'destroy']);

    // US-RES-02: Ver Mis Reservas
    Route::get('/reservations/my', [\App\Http\Controllers\API\ReservationController::class, 'myReservations']);

    // US-RES-03: Cancelar Reserva
    Route::delete('/reservations/{reservation}', [\App\Http\Controllers\API\ReservationController::class, 'destroy']);

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

// US-PLCS-01: Explorar Lugares
Route::get('/places', [\App\Http\Controllers\API\PlaceController::class, 'index']);
Route::get('/places/options', [\App\Http\Controllers\API\PlaceController::class, 'options']);

// US-PLCS-02: Ver Detalle de Lugar
Route::get('/places/{place}', [\App\Http\Controllers\API\PlaceController::class, 'show']);
Route::get('/places/{place}/available-schedules', [\App\Http\Controllers\API\PlaceController::class, 'getAvailableSchedules']);
Route::get('/places/{place}/schedules', [\App\Http\Controllers\API\PlaceScheduleController::class, 'index']);
Route::get('/places/{id}/reviews', function($id, \Illuminate\Http\Request $request) {
    return app(\App\Http\Controllers\API\ReviewController::class)->index($request, 'place', $id);
});

// US-PLCS-03: Explorar Ecohoteles
Route::get('/ecohotels', [\App\Http\Controllers\API\EcohotelController::class, 'index']);
Route::get('/ecohotels/{ecohotel}', [\App\Http\Controllers\API\EcohotelController::class, 'show']);
Route::get('/ecohotels/{id}/reviews', function($id, \Illuminate\Http\Request $request) {
    return app(\App\Http\Controllers\API\ReviewController::class)->index($request, 'ecohotel', $id);
});