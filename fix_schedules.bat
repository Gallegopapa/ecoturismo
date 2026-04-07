@echo off
echo ================================================
echo   Solucionando Sistema de Horarios
echo ================================================
echo.

REM Verificar si PHP está disponible
where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP no se encuentra en el PATH
    echo.
    echo Por favor ejecuta manualmente:
    echo   1. php artisan migrate --path=database/migrations/2026_01_12_162646_create_place_schedules_table.php
    echo   2. php artisan db:seed --class=PlaceScheduleSeeder
    echo.
    pause
    exit /b 1
)

echo [1/2] Ejecutando migracion de place_schedules...
php artisan migrate --path=database/migrations/2026_01_12_162646_create_place_schedules_table.php
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Error al ejecutar la migracion
    pause
    exit /b 1
)

echo.
echo [2/2] Agregando horarios a lugares existentes...
php artisan db:seed --class=PlaceScheduleSeeder
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Error al ejecutar el seeder
    pause
    exit /b 1
)

echo.
echo ================================================
echo   ¡Proceso completado exitosamente!
echo ================================================
echo.
echo Los horarios han sido configurados correctamente.
echo Puedes modificar los horarios desde el panel de administracion.
echo.
pause
