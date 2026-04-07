<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [PasswordResetController::class, 'sendResetLink']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);

// Rutas públicas de perfil (AC7 helper)
Route::get('/profile/photo', [ProfileController::class, 'photoByQuery']);
Route::get('/profile/photo/{filename}', [ProfileController::class, 'photo']);

// Rutas públicas de Lugares y Categorías
Route::get('/places/options', [\App\Http\Controllers\API\PlaceController::class, 'options']);
Route::get('/places', [\App\Http\Controllers\API\PlaceController::class, 'index']);
Route::get('/places/{place}', [\App\Http\Controllers\API\PlaceController::class, 'show']);
Route::get('/places/{place}/available-schedules', [\App\Http\Controllers\API\PlaceController::class, 'getAvailableSchedules']);
Route::get('/categories', [\App\Http\Controllers\API\CategoryController::class, 'index']);

// Rutas públicas de Ecohoteles
Route::get('/ecohotels', [\App\Http\Controllers\API\EcohotelController::class, 'index']);
Route::get('/ecohotels/{ecohotel}', [\App\Http\Controllers\API\EcohotelController::class, 'show']);

// Reseñas (Públicas)
Route::get('/{type}/{id}/reviews', [\App\Http\Controllers\API\ReviewController::class, 'index'])->where('type', 'place|ecohotel');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/verify-token', [AuthController::class, 'verifyToken']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'changePassword']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
    
    // Reservas de usuario
    Route::post('/reservations', [\App\Http\Controllers\API\ReservationController::class, 'store']);
    Route::get('/reservations/my', [\App\Http\Controllers\API\ReservationController::class, 'myReservations']);
    Route::delete('/reservations/{reservation}', [\App\Http\Controllers\API\ReservationController::class, 'destroy']);

    // Reseñas
    Route::post('/reviews', [\App\Http\Controllers\API\ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [\App\Http\Controllers\API\ReviewController::class, 'update']);
    // Favoritos
    Route::get('/favorites', [\App\Http\Controllers\API\FavoriteController::class, 'index']);
    Route::get('/favorites/check/{placeId}', [\App\Http\Controllers\API\FavoriteController::class, 'checkFavorite']);
    Route::post('/favorites', [\App\Http\Controllers\API\FavoriteController::class, 'store']);
    Route::delete('/favorites/{placeId}', [\App\Http\Controllers\API\FavoriteController::class, 'destroy']);

    // ============================================
    // RUTAS DE EMPRESA (para usuarios tipo empresa)
    // ============================================
    Route::prefix('company')->group(function () {
        // Lugares gestionados por la empresa
        Route::get('/places', [\App\Http\Controllers\API\CompanyPlaceController::class, 'index']);
        Route::get('/places/{place}', [\App\Http\Controllers\API\CompanyPlaceController::class, 'show']);
        Route::post('/places/{place}', [\App\Http\Controllers\API\CompanyPlaceController::class, 'update']);
        Route::put('/places/{place}', [\App\Http\Controllers\API\CompanyPlaceController::class, 'update']);
        Route::delete('/places/{place}', [\App\Http\Controllers\API\CompanyPlaceController::class, 'destroy']);

        Route::get('/places/{place}/schedules', [\App\Http\Controllers\API\CompanyPlaceScheduleController::class, 'index']);
        Route::post('/places/{place}/schedules', [\App\Http\Controllers\API\CompanyPlaceScheduleController::class, 'store']);
        Route::put('/places/{place}/schedules/{schedule}', [\App\Http\Controllers\API\CompanyPlaceScheduleController::class, 'update']);
        Route::delete('/places/{place}/schedules/{schedule}', [\App\Http\Controllers\API\CompanyPlaceScheduleController::class, 'destroy']);

        // Gestión de reservas desde la perspectiva de la empresa
        Route::get('/reservations', [\App\Http\Controllers\API\CompanyReservationController::class, 'index']);
        Route::get('/reservations/stats', [\App\Http\Controllers\API\CompanyReservationController::class, 'statsSummary']);
        Route::get('/reservations/{companyReservation}', [\App\Http\Controllers\API\CompanyReservationController::class, 'show']);
        Route::post('/reservations/{companyReservation}/accept', [\App\Http\Controllers\API\CompanyReservationController::class, 'accept']);
        Route::post('/reservations/{companyReservation}/reject', [\App\Http\Controllers\API\CompanyReservationController::class, 'reject']);
        Route::post('/reservations/{companyReservation}/reopen', [\App\Http\Controllers\API\CompanyReservationController::class, 'reopen']);
        Route::get('/reservations/place/{placeId}/stats', [\App\Http\Controllers\API\CompanyReservationController::class, 'stats']);
    });

    // ============================================
    // RUTAS DE ADMINISTRADOR
    // ============================================
    Route::prefix('admin')->middleware(\App\Http\Middleware\EnsureUserIsAdmin::class)->group(function () {
        // Rutas de lugares para admin
        Route::get('/places', [\App\Http\Controllers\API\PlaceController::class, 'index']);
        Route::get('/places/{place}', [\App\Http\Controllers\API\PlaceController::class, 'show']);
        Route::post('/places', [\App\Http\Controllers\API\PlaceController::class, 'store']);
        Route::post('/places/{place}', [\App\Http\Controllers\API\PlaceController::class, 'update']); // POST para FormData con _method=PUT
        Route::put('/places/{place}', [\App\Http\Controllers\API\PlaceController::class, 'update']);
        Route::delete('/places/{place}', [\App\Http\Controllers\API\PlaceController::class, 'destroy']);

        // Rutas de horarios para lugares (Admin)
        Route::get('/places/{place}/schedules', [\App\Http\Controllers\API\PlaceScheduleController::class, 'index']);
        Route::post('/places/{place}/schedules', [\App\Http\Controllers\API\PlaceScheduleController::class, 'store']);
        Route::put('/places/{place}/schedules/{schedule}', [\App\Http\Controllers\API\PlaceScheduleController::class, 'update']);
        Route::delete('/places/{place}/schedules/{schedule}', [\App\Http\Controllers\API\PlaceScheduleController::class, 'destroy']);

        // Rutas de ecohoteles para admin
        Route::get('/ecohotels', [\App\Http\Controllers\API\EcohotelController::class, 'index']);
        Route::get('/ecohotels/{ecohotel}', [\App\Http\Controllers\API\EcohotelController::class, 'show']);
        Route::post('/ecohotels', [\App\Http\Controllers\API\EcohotelController::class, 'store']);
        Route::post('/ecohotels/{ecohotel}', [\App\Http\Controllers\API\EcohotelController::class, 'update']);
        Route::put('/ecohotels/{ecohotel}', [\App\Http\Controllers\API\EcohotelController::class, 'update']);
        Route::delete('/ecohotels/{ecohotel}', [\App\Http\Controllers\API\EcohotelController::class, 'destroy']);
    });
});
