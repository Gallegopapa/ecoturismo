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
    Route::delete('/reviews/{review}', [\App\Http\Controllers\API\ReviewController::class, 'destroy']);
});
