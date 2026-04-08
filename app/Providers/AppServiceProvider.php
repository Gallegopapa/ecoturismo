<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
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
        Schema::defaultStringLength(191);

        // Registrar observers
        Reservation::observe(ReservationObserver::class);

        // Crear symlink storage si no existe (necesario en producción Docker/Nixpacks)
        $this->ensureStorageLink();

        // Asegurar que el directorio de imágenes de ecohoteles exista
        $this->ensureImageDirectories();
    }

    /**
     * Crea el symlink public/storage → storage/app/public si no existe.
     */
    private function ensureStorageLink(): void
    {
        try {
            $link = public_path('storage');
            $target = storage_path('app/public');

            if (!file_exists($link) && !is_link($link)) {
                if (is_dir($target)) {
                    symlink($target, $link);
                }
            }
        } catch (\Exception $e) {
            // Silenciar si no hay permisos para crear symlinks
        }
    }

    /**
     * Crea los directorios de imágenes necesarios si no existen.
     */
    private function ensureImageDirectories(): void
    {
        try {
            $dirs = [
                public_path('imagenes/ecohotels'),
                storage_path('app/public/ecohotels'),
            ];
            foreach ($dirs as $dir) {
                if (!File::exists($dir)) {
                    File::makeDirectory($dir, 0755, true);
                }
            }
        } catch (\Exception $e) {
            // Silenciar si no hay permisos
        }
    }
}

