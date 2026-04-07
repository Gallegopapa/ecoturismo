# Guía de Instalación y Configuración - Risaralda EcoTurismo

Esta guía te ayudará a instalar y configurar el proyecto desde cero.

## 📋 Requisitos Previos

### Software Necesario

- **PHP 8.2 o superior**
  - Extensiones requeridas: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- **Composer** (gestor de dependencias PHP)
- **Node.js 18+ y npm** (o yarn)
- **Git**
- **Base de datos**: SQLite (incluido) o MySQL/PostgreSQL

### Verificar Instalación

```bash
# Verificar PHP
php -v  # Debe ser 8.2 o superior

# Verificar Composer
composer --version

# Verificar Node.js
node -v  # Debe ser 18 o superior
npm -v
```

## 🚀 Instalación Paso a Paso

### 1. Clonar el Repositorio

```bash
git clone <url-del-repositorio>
cd Laravel_Ecoturismo
```

### 2. Instalar Dependencias de PHP

```bash
composer install
```

Esto instalará todas las dependencias de Laravel definidas en `composer.json`.

### 3. Instalar Dependencias de Node.js

```bash
npm install
```

Esto instalará React, Vite y todas las dependencias del frontend.

### 4. Configurar el Entorno

```bash
# Copiar archivo de entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 5. Configurar Base de Datos

#### Opción A: SQLite (Recomendado para desarrollo)

1. Crear archivo de base de datos:
```bash
touch database/database.sqlite
```

2. En `.env`, asegúrate de tener:
```env
DB_CONNECTION=sqlite
# Comentar o eliminar las otras líneas de DB_*
```

#### Opción B: MySQL

1. Crear base de datos:
```sql
CREATE DATABASE ecoturismo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. En `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecoturismo
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

#### Opción C: PostgreSQL

1. Crear base de datos:
```sql
CREATE DATABASE ecoturismo;
```

2. En `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ecoturismo
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
```

### 6. Ejecutar Migraciones

```bash
php artisan migrate
```

Esto creará todas las tablas necesarias en la base de datos.

### 7. (Opcional) Poblar Base de Datos

```bash
php artisan db:seed
```

Esto creará datos de ejemplo (lugares, categorías, etc.).

### 8. Crear Enlace Simbólico para Storage

```bash
php artisan storage:link
```

Esto permite acceder a las imágenes almacenadas desde la web.

## ⚙️ Configuración Adicional

### Variables de Entorno Importantes

Edita el archivo `.env` y configura:

```env
# Aplicación
APP_NAME="Risaralda EcoTurismo"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de datos (ya configurada en paso 5)

# Sanctum (para autenticación API)
SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173

# Sesión
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### Configurar Vite

El archivo `vite.config.js` ya está configurado, pero verifica que coincida con tu `APP_URL`:

```javascript
server: {
    hmr: {
        host: 'localhost',
        port: 5173
    }
}
```

## 🎯 Ejecutar el Proyecto

### Desarrollo

Necesitas ejecutar dos servidores simultáneamente:

#### Terminal 1 - Servidor Laravel
```bash
php artisan serve
```
Esto iniciará el servidor en `http://localhost:8000`

#### Terminal 2 - Servidor Vite (React)
```bash
npm run dev
```
Esto iniciará el servidor de desarrollo en `http://localhost:5173`

#### O usar el comando combinado:
```bash
npm run serve
```

Luego accede a: **http://localhost:8000**

### Producción

1. Compilar assets:
```bash
npm run build
```

2. Optimizar Laravel:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

3. Configurar servidor web (Apache/Nginx) para apuntar a `public/`

## 👤 Crear Usuario Administrador

### Opción 1: Desde Tinker

```bash
php artisan tinker
```

Luego ejecuta:
```php
$user = new App\Models\Usuarios();
$user->name = 'admin';
$user->email = 'admin@example.com';
$user->password = Hash::make('password123');
$user->is_admin = true;
$user->fecha_registro = now();
$user->save();
```

### Opción 2: Desde Migración/Seeder

Crea un seeder:

```bash
php artisan make:seeder AdminUserSeeder
```

Edita `database/seeders/AdminUserSeeder.php`:
```php
public function run()
{
    \App\Models\Usuarios::create([
        'name' => 'admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('password123'),
        'is_admin' => true,
        'fecha_registro' => now(),
    ]);
}
```

Ejecuta:
```bash
php artisan db:seed --class=AdminUserSeeder
```

## 🔧 Solución de Problemas Comunes

### Error: "Class not found"

```bash
composer dump-autoload
```

### Error: "Vite manifest not found"

```bash
npm run build
# O en desarrollo:
npm run dev
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"

- Verifica que la base de datos esté corriendo
- Revisa las credenciales en `.env`
- Para SQLite, verifica que el archivo exista y tenga permisos

### Error: "419 Page Expired" o CSRF

- Limpia la caché:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Error: "Token mismatch"

- Verifica que `SANCTUM_STATEFUL_DOMAINS` en `.env` incluya tu dominio
- Limpia el localStorage del navegador

### Puerto 8000 o 5173 ya en uso

**Cambiar puerto de Laravel:**
```bash
php artisan serve --port=8001
```

**Cambiar puerto de Vite:**
Edita `vite.config.js`:
```javascript
server: {
    port: 5174
}
```

## 📁 Estructura de Carpetas Importantes

```
Laravel_Ecoturismo/
├── app/                    # Código de la aplicación
│   ├── Http/Controllers/   # Controladores
│   └── Models/             # Modelos
├── database/
│   ├── migrations/         # Migraciones
│   └── seeders/           # Seeders
├── public/                 # Archivos públicos
│   └── storage/           # Enlaces simbólicos
├── resources/
│   └── js/react/          # Aplicación React
├── routes/
│   ├── api.php            # Rutas API
│   └── web.php            # Rutas web
├── storage/
│   └── app/public/        # Archivos subidos
└── .env                   # Variables de entorno
```

## 🔐 Permisos (Linux/Mac)

Si tienes problemas con permisos de escritura:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📦 Comandos Útiles

### Laravel

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Crear migración
php artisan make:migration create_nombre_tabla

# Crear modelo
php artisan make:model NombreModelo

# Crear controlador
php artisan make:controller NombreController

# Ver rutas
php artisan route:list

# Tinker (consola interactiva)
php artisan tinker
```

### Node.js

```bash
# Instalar dependencias
npm install

# Desarrollo
npm run dev

# Producción
npm run build

# Verificar dependencias
npm audit
```

## 🧪 Testing

```bash
# Ejecutar tests
php artisan test

# Con cobertura
php artisan test --coverage
```

## 📝 Notas Adicionales

1. **Desarrollo**: Usa `APP_DEBUG=true` solo en desarrollo
2. **Producción**: Siempre usa `APP_DEBUG=false` en producción
3. **Base de datos**: SQLite es perfecto para desarrollo, pero considera MySQL/PostgreSQL para producción
4. **Imágenes**: Asegúrate de que `storage/app/public` tenga permisos de escritura
5. **Tokens**: Los tokens de Sanctum expiran después de 30 días por defecto

## 🆘 Obtener Ayuda

- Revisa los logs en `storage/logs/laravel.log`
- Consulta la [documentación de Laravel](https://laravel.com/docs)
- Consulta la [documentación de React](https://react.dev)

---

**Última actualización**: Diciembre 2025

