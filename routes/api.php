<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PlaceController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\AdminPlaceController;
use App\Http\Controllers\API\AdminUserController;
use App\Http\Controllers\API\PlaceScheduleController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\CompanyReservationController;
use App\Http\Controllers\API\RejectionReasonController;
use App\Http\Controllers\API\CompanyPlaceController;
use App\Http\Controllers\API\CompanyPlaceScheduleController;
use App\Http\Controllers\API\EcohotelController;

// ============================================
// RUTAS PÃšBLICAS (sin autenticaciÃ³n)
// ============================================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/password/forgot', [PasswordResetController::class, 'sendResetLink']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);
Route::get('/profile/photo/stream', [ProfileController::class, 'photoByQuery']);
Route::get('/profile/photo/{filename}', [ProfileController::class, 'photo'])->where('filename', '.*');


// Rutas pÃºblicas de lugares
Route::get('/places', [PlaceController::class, 'index']);
Route::get('/places/options', [PlaceController::class, 'options']);
Route::get('/places/{place}', [PlaceController::class, 'show']);
Route::get('/places/{place}/available-schedules', [PlaceController::class, 'getAvailableSchedules']);
Route::get('/places/{place}/schedules', [PlaceScheduleController::class, 'index']);

// Rutas pÃºblicas de ecohoteles
Route::get('/ecohotels', [EcohotelController::class, 'index']);
Route::get('/ecohotels/{ecohotel}', [EcohotelController::class, 'show']);

// Rutas pÃºblicas de categorÃ­as
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

// Rutas pÃºblicas de reseÃ±as
Route::get('/reviews/all', [ReviewController::class, 'all']);
Route::get('/places/{id}/reviews', function($id, \Illuminate\Http\Request $request) {
    return app(\App\Http\Controllers\API\ReviewController::class)->index($request, 'place', $id);
});
Route::get('/ecohotels/{id}/reviews', function($id, \Illuminate\Http\Request $request) {
    return app(\App\Http\Controllers\API\ReviewController::class)->index($request, 'ecohotel', $id);
});

// Rutas pÃºblicas de razones de rechazo
Route::get('/rejection-reasons', [RejectionReasonController::class, 'index']);

// EnvÃ­o de mensajes (pÃºblico, pero puede incluir user_id si estÃ¡ autenticado)
Route::post('/messages', [MessageController::class, 'store']);

// EnvÃ­o de contactos (pÃºblico, pero puede incluir user_id si estÃ¡ autenticado)
Route::post('/contacts', [ContactController::class, 'store']);

// ============================================
// RUTAS PROTEGIDAS (requieren autenticaciÃ³n con Sanctum)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    // AutenticaciÃ³n
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::get('/verify-token', [AuthController::class, 'verifyToken']);
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']); // POST para FormData con imagen
    Route::put('/profile', [ProfileController::class, 'update']); // PUT para JSON sin imagen
    Route::put('/profile/password', [ProfileController::class, 'changePassword']);
    Route::delete('/profile', [ProfileController::class, 'destroy']); // Eliminar cuenta
    
    // Rutas de lugares (CRUD completo - solo admin)
    Route::post('/places', [PlaceController::class, 'store'])->middleware('admin');
    Route::put('/places/{place}', [PlaceController::class, 'update'])->middleware('admin');
    Route::delete('/places/{place}', [PlaceController::class, 'destroy'])->middleware('admin');
    
    // Rutas de categorÃ­as (CRUD completo - solo admin)
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('admin');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('admin');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('admin');
    
    // Rutas de reservas
    Route::get('/reservations/my', [ReservationController::class, 'myReservations']);
    Route::apiResource('reservations', ReservationController::class);
    
    // Rutas de comentarios/reseÃ±as
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    
    // Rutas de favoritos
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::get('/favorites/check/{placeId}', [FavoriteController::class, 'check']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{placeId}', [FavoriteController::class, 'destroy']);
    
    // Rutas de pagos (BOCETO)
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    
    // Rutas de contactos (solo lectura para admin)
    Route::get('/contacts', [ContactController::class, 'index'])->middleware('admin');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->middleware('admin');
    
    // ============================================
    // RUTAS DE EMPRESA (para usuarios tipo empresa)
    // ============================================
    Route::prefix('company')->group(function () {
        // Lugares gestionados por la empresa
        Route::get('/places', [CompanyPlaceController::class, 'index']);

        Route::get('/places/{place}', [CompanyPlaceController::class, 'show']);
        Route::post('/places/{place}', [CompanyPlaceController::class, 'update']); // POST para FormData con _method=PUT
        Route::put('/places/{place}', [CompanyPlaceController::class, 'update']);
        Route::delete('/places/{place}', [CompanyPlaceController::class, 'destroy']);

        Route::get('/places/{place}/schedules', [CompanyPlaceScheduleController::class, 'index']);
        Route::post('/places/{place}/schedules', [CompanyPlaceScheduleController::class, 'store']);
        Route::put('/places/{place}/schedules/{schedule}', [CompanyPlaceScheduleController::class, 'update']);
        Route::delete('/places/{place}/schedules/{schedule}', [CompanyPlaceScheduleController::class, 'destroy']);

        // Gestión de reservas desde la perspectiva de la empresa
        Route::get('/reservations', [CompanyReservationController::class, 'index']);
        Route::get('/reservations/stats', [CompanyReservationController::class, 'statsSummary']);
        Route::get('/reservations/{companyReservation}', [CompanyReservationController::class, 'show']);
        Route::post('/reservations/{companyReservation}/accept', [CompanyReservationController::class, 'accept']);
        Route::post('/reservations/{companyReservation}/reject', [CompanyReservationController::class, 'reject']);
        Route::post('/reservations/{companyReservation}/reopen', [CompanyReservationController::class, 'reopen']);
        Route::get('/reservations/place/{placeId}/stats', [CompanyReservationController::class, 'stats']);
    });
    
    // ============================================
    // RUTAS DE ADMIN API (requieren autenticaciÃ³n + admin)
    // ============================================
    Route::prefix('admin')->middleware('admin')->group(function () {
        // Rutas de lugares para admin
        Route::get('/places', [AdminPlaceController::class, 'index']);
        Route::get('/places/{place}', [AdminPlaceController::class, 'show']);
        Route::post('/places', [AdminPlaceController::class, 'store']);
        Route::post('/places/{place}', [AdminPlaceController::class, 'update']); // POST para FormData con _method=PUT
        Route::put('/places/{place}', [AdminPlaceController::class, 'update']); // Mantener PUT tambiÃ©n por compatibilidad
        Route::delete('/places/{place}', [AdminPlaceController::class, 'destroy']);
        
        // Rutas de ecohoteles para admin
        Route::apiResource('ecohotels', EcohotelController::class);
        
        // Rutas de usuarios para admin (mejorado con tipo_usuario y lugares)
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::get('/users/{user}', [AdminUserController::class, 'show']);
        Route::post('/users', [AdminUserController::class, 'store']);
        Route::put('/users/{user}', [AdminUserController::class, 'update']);
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy']);

        // Rutas de reservas para admin
        Route::get('/reservations', [ReservationController::class, 'all']);

        // Rutas de razones de rechazo
        Route::get('/rejection-reasons', [RejectionReasonController::class, 'index']);
        Route::post('/rejection-reasons', [RejectionReasonController::class, 'store']);
        Route::get('/rejection-reasons/{reason}', [RejectionReasonController::class, 'show']);
        Route::put('/rejection-reasons/{reason}', [RejectionReasonController::class, 'update']);
        Route::delete('/rejection-reasons/{reason}', [RejectionReasonController::class, 'destroy']);

        // Rutas de horarios para lugares
        Route::get('/places/{place}/schedules', [PlaceScheduleController::class, 'index']);
        Route::post('/places/{place}/schedules', [PlaceScheduleController::class, 'store']);
        Route::put('/places/{place}/schedules/{schedule}', [PlaceScheduleController::class, 'update']);
        Route::delete('/places/{place}/schedules/{schedule}', [PlaceScheduleController::class, 'destroy']);
    });
});