<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\PlaceAdminController;
use App\Http\Controllers\Admin\ReservationAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CompanyDashboardController;

// ============================================
// AUTENTICACIÓN (Estas rutas deben ir primero)
// ============================================
// Las rutas GET /login y /registro son manejadas por React Router
// Agregamos la ruta GET con nombre para que el middleware de autenticación funcione
Route::get('/login', function() {
    return view('app');
})->name('login');

Route::get('/register', function() {
    return view('app');
})->name('register');

// Compatibilidad: mantener endpoints POST web para formularios Blade antiguos
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/registro', [RegisterController::class, 'store'])->name('registro.store');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::middleware('auth')->group(function () {
    // Rutas de usuarios
    Route::get('/reservas', [ReservationController::class, 'index'])->name('web.reservations.index');
    Route::get('/reservas/crear/{place}', [ReservationController::class, 'create'])->name('web.reservations.create');
    Route::post('/reservas', [ReservationController::class, 'store'])->name('web.reservations.store');
    Route::delete('/reservas/{reservation}', [ReservationController::class, 'destroy'])->name('web.reservations.destroy');
    Route::get('/favoritos', function() {
        $favorites = \App\Models\Favorite::where('user_id', Auth::id())
            ->with('place')
            ->get();
        return view('favorites.index', compact('favorites'));
    })->name('favorites.index');
    
    Route::post('/favoritos', function(\Illuminate\Http\Request $request) {
        $data = $request->validate([
            'place_id' => 'required|exists:places,id',
        ]);
        
        $existing = \App\Models\Favorite::where('user_id', Auth::id())
            ->where('place_id', $data['place_id'])
            ->first();
            
        if ($existing) {
            return back()->with('error', 'Este lugar ya está en tus favoritos');
        }
        
        \App\Models\Favorite::create([
            'user_id' => Auth::id(),
            'place_id' => $data['place_id'],
        ]);
        
        return back()->with('status', 'Agregado a favoritos');
    })->name('favorites.store');
    
    Route::delete('/favoritos/{place}', function(\App\Models\Place $place) {
        \App\Models\Favorite::where('user_id', Auth::id())
            ->where('place_id', $place->id)
            ->delete();
        return back()->with('status', 'Eliminado de favoritos');
    })->name('favorites.destroy');
    
    // Rutas de comentarios/reseñas
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Rutas de admin (requieren autenticación web)
    Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/places', [PlaceAdminController::class, 'index'])->name('places.index');
        Route::post('/places', [PlaceAdminController::class, 'store'])->name('places.store');
        Route::post('/places/upload', [PlaceAdminController::class, 'upload'])->name('places.upload');
        Route::put('/places/{place}', [PlaceAdminController::class, 'update'])->name('places.update');
        Route::delete('/places/{place}', [PlaceAdminController::class, 'destroy'])->name('places.destroy');
        
        Route::get('/reservations', [ReservationAdminController::class, 'index'])->name('reservations.index');
        Route::put('/reservations/{reservation}', [ReservationAdminController::class, 'update'])->name('reservations.update');
        Route::delete('/reservations/{reservation}', [ReservationAdminController::class, 'destroy'])->name('reservations.destroy');
        
        Route::get('/categories', [CategoryAdminController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryAdminController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryAdminController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryAdminController::class, 'destroy'])->name('categories.destroy');
    });
});

// ============================================
// RUTA DE ADMIN PANEL (React - sin middleware web)
// ============================================
// Esta ruta es manejada completamente por React Router
// La autenticación se maneja con tokens (Sanctum) en el frontend
Route::get('/admin/panel', function() {
    return view('app');
});

// ============================================
// RUTAS DE PERFIL
// ============================================
Route::middleware('auth')->group(function () {
    Route::get('/configuracion', [ProfileController::class, 'show'])->name('configuracion');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // ============================================
    // COMPANY/EMPRESA DASHBOARD ROUTES (server-side)
    // ============================================
    Route::post('/empresa/reservas/{id}/aceptar', [CompanyDashboardController::class, 'accept'])->name('company.accept');
    Route::post('/empresa/reservas/{id}/rechazar', [CompanyDashboardController::class, 'reject'])->name('company.reject');
});

// ============================================
// ENVÍO DE MENSAJES
// ============================================
Route::post('/mensajes', [MessageController::class, 'store'])->name('mensajes');

// ============================================
// RUTA PRINCIPAL - REACT APP
// ============================================
Route::get('/', function () {
    return view('app');
})->name('pagcentral');

// ============================================
// COMPANY/EMPRESA DASHBOARD (React)
// ============================================
Route::get('/empresa/dashboard', function () {
    return redirect('/company/dashboard');
})->name('company.dashboard');

// ============================================
// FALLBACK: Todas las demás rutas GET sirven la app React
// ============================================
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');