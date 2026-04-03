<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Reservation;
use App\Observers\ReservationObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Evita el error "Specified key was too long" en MySQL antiguos
        // Reduce la longitud por defecto de los string para índices a 191
        Schema::defaultStringLength(191);

        // Registrar observers
        Reservation::observe(ReservationObserver::class);
    }
}

