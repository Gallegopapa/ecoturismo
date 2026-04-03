<?php

use Illuminate\Support\Facades\Route;

// ============================================
// RUTAS DE REACT APP (Frontend rendering)
// ============================================

Route::get('/', function () {
    return view('app');
})->name('pagcentral');

Route::get('/login', function() {
    return view('app');
})->name('login');

Route::get('/register', function() {
    return view('app');
})->name('register');

Route::get('/pagLogueados', function() {
    return view('app');
})->name('pagLogueados');

// ============================================
// FALLBACK: Todas las demás rutas GET sirven la app React
// ============================================
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');