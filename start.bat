@echo off
echo ================================================
echo   Risaralda EcoTurismo - Servidor de Desarrollo
echo ================================================
echo.

REM Verificar si las dependencias están instaladas
if not exist "vendor\" (
    echo [ERROR] No se encontraron las dependencias de PHP
    echo Ejecutando: composer install
    echo.
    composer install
    echo.
)

if not exist "node_modules\" (
    echo [ERROR] No se encontraron las dependencias de Node.js
    echo Ejecutando: npm install
    echo.
    npm install
    echo.
)

REM Verificar si existe .env
if not exist ".env" (
    echo [AVISO] No se encontró el archivo .env
    echo Copiando .env.example a .env
    copy .env.example .env
    echo.
    echo Generando clave de aplicación...
    php artisan key:generate
    echo.
)

echo ================================================
echo   Iniciando servidores...
echo ================================================
echo.
echo [INFO] Laravel Backend: http://localhost:8000
echo [INFO] Vite Frontend: http://localhost:5173
echo.
echo Presiona Ctrl+C para detener los servidores
echo ================================================
echo.

REM Iniciar ambos servidores
npm run serve
