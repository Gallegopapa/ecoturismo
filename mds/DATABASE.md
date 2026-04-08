# Estructura de Base de Datos - Risaralda EcoTurismo

Documentación completa de la estructura de la base de datos del proyecto.

## 📊 Diagrama de Relaciones

```
usuarios
  ├── reservations (1:N)
  ├── reviews (1:N)
  ├── favorites (1:N)
  └── contacts (1:N, nullable)

places
  ├── reservations (1:N)
  ├── reviews (1:N)
  ├── favorites (1:N)
  └── categories (N:M) → category_place

categories
  └── places (N:M) → category_place

reservations
  ├── usuarios (N:1)
  ├── places (N:1)
  └── payments (1:1, opcional)

payments
  └── reservations (N:1)
```

## 📋 Tablas

### usuarios

Tabla principal de usuarios del sistema.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Clave primaria |
| name | string(255) | Nombre de usuario (único) |
| email | string(255) | Email (único, nullable) |
| password | string(255) | Contraseña hasheada |
| telefono | string(20) | Teléfono (nullable) |
| foto_perfil | string(255) | Ruta de foto de perfil (nullable) |
| is_admin | boolean | Es administrador (default: false) |
| fecha_registro | datetime | Fecha de registro |

**Índices:**
- PRIMARY KEY: `id`
- UNIQUE: `name`
- UNIQUE: `email`

**Relaciones:**
- `hasMany` Reservations
- `hasMany` Reviews
- `hasMany` Favorites
- `hasMany` Contacts

### places

Tabla de lugares turísticos.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Clave primaria |
| name | string(255) | Nombre del lugar |
| description | text | Descripción del lugar |
| location | string(255) | Ubicación (ciudad, departamento) |
| image | string(255) | Ruta de imagen principal |
| latitude | decimal(10,8) | Latitud (nullable) |
| longitude | decimal(11,8) | Longitud (nullable) |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Índices:**
- PRIMARY KEY: `id`

**Relaciones:**
- `hasMany` Reservations
- `hasMany` Reviews
- `hasMany` Favorites
- `belongsToMany` Categories (through category_place)

### categories

Tabla de categorías de lugares.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Clave primaria |
| name | string(255) | Nombre de la categoría |
| description | text | Descripción (nullable) |
| slug | string(255) | Slug único para URLs |
| icon | string(50) | Icono/emoji (nullable) |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Índices:**
- PRIMARY KEY: `id`
- UNIQUE: `slug`

**Relaciones:**
- `belongsToMany` Places (through category_place)

### category_place

Tabla pivot para relación muchos-a-muchos entre categorías y lugares.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Clave primaria |
| category_id | bigint | FK a categories |
| place_id | bigint | FK a places |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Índices:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `category_id` → `categories.id`
- FOREIGN KEY: `place_id` → `places.id`
- UNIQUE: `(category_id, place_id)`

### reservations

Tabla de reservas de visitas.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Clave primaria |
| user_id | bigint | FK a usuarios |
| place_id | bigint | FK a places |
| fecha | date | Fecha de la reserva (nullable) |
| fecha_reserva | datetime | Fecha en que se hizo la reserva |
| fecha_visita | date | Fecha programada de visita |
| hora_visita | string(5) | Hora de visita (formato HH:mm) |
| personas | integer | Número de personas |
| telefono_contacto | string(20) | Teléfono de contacto |
| comentarios | text | Comentarios adicionales (nullable) |
| precio_total | decimal(10,2) | Precio total (nullable) |
| estado | string(50) | Estado (pendiente, confirmada, cancelada) |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Índices:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `user_id` → `usuarios.id`
- FOREIGN KEY: `place_id` → `places.id`

**Relaciones:**
- `belongsTo` Usuario
- `belongsTo` Place
- `hasOne` Payment

### reviews

Tabla de reseñas y calificaciones.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Clave primaria |
| user_id | bigint | FK a usuarios |
| place_id | bigint | FK a places |
| rating | integer | Calificación (1-5) |
| comment | text | Comentario (nullable) |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Índices:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `user_id` → `usuarios.id`
- FOREIGN KEY: `place_id` → `places.id`
- UNIQUE: `(user_id, place_id)` - Un usuario solo puede reseñar un lugar una vez

**Relaciones:**
- `belongsTo` Usuario
- `belongsTo` Place

### favorites

Tabla de favoritos de usuarios.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Clave primaria |
| user_id | bigint | FK a usuarios |
| place_id | bigint | FK a places |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Índices:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `user_id` → `usuarios.id`
- FOREIGN KEY: `place_id` → `places.id`
- UNIQUE: `(user_id, place_id)` - Un usuario solo puede tener un lugar una vez en favoritos

**Relaciones:**
- `belongsTo` Usuario
- `belongsTo` Place

### contacts

Tabla de mensajes de contacto.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Clave primaria |
| name | string(255) | Nombre del contacto |
| email | string(255) | Email del contacto |
| phone | string(20) | Teléfono del contacto |
| message | text | Mensaje |
| user_id | bigint | FK a usuarios (nullable) |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Índices:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `user_id` → `usuarios.id` (ON DELETE SET NULL)

**Relaciones:**
- `belongsTo` Usuario (opcional)

### payments

Tabla de pagos (BOCETO - no completamente funcional).

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Clave primaria |
| reservation_id | bigint | FK a reservations |
| amount | decimal(10,2) | Monto del pago |
| payment_method | string(50) | Método de pago |
| status | string(50) | Estado del pago |
| transaction_id | string(255) | ID de transacción (nullable) |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Índices:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `reservation_id` → `reservations.id`

**Relaciones:**
- `belongsTo` Reservation

### personal_access_tokens

Tabla de tokens de Laravel Sanctum para autenticación API.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Clave primaria |
| tokenable_type | string(255) | Tipo de modelo (App\Models\Usuarios) |
| tokenable_id | bigint | ID del usuario |
| name | string(255) | Nombre del token |
| token | string(64) | Token hasheado (único) |
| abilities | text | Permisos (JSON) |
| last_used_at | timestamp | Último uso (nullable) |
| expires_at | timestamp | Fecha de expiración (nullable) |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Índices:**
- PRIMARY KEY: `id`
- UNIQUE: `token`
- INDEX: `(tokenable_type, tokenable_id)`

## 🔄 Migraciones

Las migraciones están en `database/migrations/` y se ejecutan en este orden:

1. `0001_01_01_000001_create_cache_table.php`
2. `0001_01_01_000002_create_jobs_table.php`
3. `2025_11_28_002913_create_personal_access_tokens_table.php`
4. `2025_11_28_010341_places.php`
5. `2025_11_28_010855_create_usuarios_table.php`
6. `2025_11_28_011501_create_reservations_table.php`
7. `2025_11_29_194211_improve_reservations_table.php`
8. `2025_11_29_194217_create_reviews_table.php`
9. `2025_11_29_194224_create_categories_table.php`
10. `2025_11_29_194231_create_category_place_table.php`
11. `2025_11_29_194238_create_favorites_table.php`
12. `2025_11_29_194244_create_payments_table.php`
13. `2025_12_14_150127_add_telefono_to_usuarios_table.php`
14. `2025_12_14_162856_add_latitude_longitude_to_places_table.php`
15. `2025_12_14_183743_add_foto_perfil_to_usuarios_table.php`
16. `2025_12_16_041711_create_contacts_table.php`

## 📝 Seeders

### PlacesSeeder

Crea lugares de ejemplo con categorías asociadas.

```bash
php artisan db:seed --class=PlacesSeeder
```

## 🔍 Consultas Útiles

### Obtener lugares con categorías

```php
Place::with('categories')->get();
```

### Obtener reservas de un usuario con lugar

```php
Reservation::where('user_id', $userId)
    ->with('place')
    ->get();
```

### Obtener promedio de calificaciones de un lugar

```php
Review::where('place_id', $placeId)
    ->avg('rating');
```

### Obtener contactos con usuario (si existe)

```php
Contact::with('usuario')->get();
```

## 🛠 Mantenimiento

### Backup

```bash
# SQLite
cp database/database.sqlite database/backup_$(date +%Y%m%d).sqlite

# MySQL
mysqldump -u root -p ecoturismo > backup_$(date +%Y%m%d).sql
```

### Limpiar tokens expirados

```php
// En tinker
DB::table('personal_access_tokens')
    ->where('expires_at', '<', now())
    ->delete();
```

### Optimizar base de datos

```bash
# SQLite
php artisan db:optimize

# MySQL
php artisan db:optimize
```

---

**Última actualización**: Diciembre 2025

