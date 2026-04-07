# 🚀 SOLUCIÓN URGENTE: Imágenes Rotas en Servidor

## 📋 Problema Identificado

Las imágenes funcionan en local pero aparecen con X (rotas) en el servidor debido a:

1. **URLs absolutas con dominio incorrecto**: El accessor usaba `url()` que depende de `APP_URL`
2. **APP_URL mal configurado o cacheado** en el servidor
3. **Rutas guardadas con `asset()`** que genera URLs absolutas dependientes del entorno

## ✅ Solución Implementada

Se modificó el sistema para ser **completamente independiente de APP_URL**:

- ✅ Accessor de `Place.php` ahora devuelve **rutas relativas** (`/imagenes/...`)
- ✅ Controladores guardan **rutas relativas** (sin `asset()`)
- ✅ Script de migración para limpiar URLs absolutas existentes en BD

---

## 🔧 PASOS PARA DESPLEGAR EN SERVIDOR (URGENTE)

### 1️⃣ Subir archivos modificados al servidor

```bash
git add .
git commit -m "Fix: Migrar imágenes a rutas relativas independientes de APP_URL"
git push
```

### 2️⃣ En el servidor, hacer pull de los cambios

```bash
cd /ruta/del/proyecto
git pull origin main  # o la rama que uses
```

### 3️⃣ Ejecutar script de migración de imágenes

```bash
php migrate_images_to_relative.php
```

Este script convierte cualquier URL absoluta en la base de datos a ruta relativa.

### 4️⃣ Verificar/Actualizar archivo .env del servidor

Asegúrate que el `.env` en el servidor tenga:

```env
APP_URL=https://tu-dominio-real.com
# Sin barra final, usa HTTPS si tienes certificado SSL
```

### 5️⃣ Limpiar cachés en el servidor

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

### 6️⃣ Verificar symlink de storage (IMPORTANTE)

```bash
php artisan storage:link
```

Si da error "El enlace ya existe", verifica que apunte correctamente:

```bash
ls -la public/storage
# Debe apuntar a: ../storage/app/public
```

Si no existe o está roto:

```bash
rm -f public/storage
php artisan storage:link
```

### 7️⃣ Verificar permisos de carpetas

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache public/imagenes
# Ajusta www-data según tu servidor (puede ser nginx, apache, etc.)
```

### 8️⃣ Verificar configuración del servidor web

**Nginx:** El document root debe apuntar a `/ruta/proyecto/public`

```nginx
root /var/www/tu-proyecto/public;
```

**Apache:** El document root debe apuntar a `/ruta/proyecto/public`

```apache
DocumentRoot /var/www/tu-proyecto/public
```

---

## 🧪 VERIFICACIÓN

### Probar una imagen específica

En el navegador, abre:
```
https://tu-dominio.com/imagenes/tatama.jpg
```

Debe cargar la imagen. Si no carga, revisa permisos y document root.

### Verificar base de datos

```bash
php -r "require 'vendor/autoload.php'; \$app=require 'bootstrap/app.php'; \$kernel=\$app->make(Illuminate\Contracts\Console\Kernel::class); \$kernel->bootstrap(); \$p=App\Models\Place::first(); echo 'Raw: '.\$p->getRawOriginal('image').PHP_EOL; echo 'Accessor: '.\$p->image.PHP_EOL;"
```

Debe mostrar:
```
Raw: /imagenes/tatama.jpg
Accessor: /imagenes/tatama.jpg
```

✅ **Ambas deben ser rutas relativas** (no URLs con http://...)

---

## 📊 Archivos Modificados

1. ✅ `app/Models/Place.php` - Accessor devuelve rutas relativas
2. ✅ `app/Http/Controllers/Admin/PlaceAdminController.php` - Guarda rutas sin `asset()`
3. ✅ `migrate_images_to_relative.php` - Script de migración

### Archivos que NO necesitan cambios

- `app/Http/Controllers/API/AdminPlaceController.php` - ✅ Ya usa rutas relativas
- `app/Http/Controllers/API/CompanyPlaceController.php` - ✅ Ya usa rutas relativas

---

## 🔍 Diagnóstico si Persiste el Problema

### Revisar logs del servidor

```bash
tail -f storage/logs/laravel.log
```

### Ver configuración cacheada

```bash
php artisan config:show | grep APP_URL
```

### Probar en terminal del servidor

```bash
curl -I https://tu-dominio.com/imagenes/tatama.jpg
# Debe devolver 200 OK
```

### Revisar rutas en BD del servidor

```bash
mysql -u usuario -p nombre_bd -e "SELECT id, image FROM places LIMIT 5;"
```

Todas las imágenes deben comenzar con `/imagenes/` o `/storage/`, **nunca con http://**

---

## 💡 Diferencia Clave: Antes vs Ahora

### ❌ ANTES (Dependiente de APP_URL)
```php
// Modelo devolvía
return url('/imagenes/tatama.jpg');  // http://localhost:8000/imagenes/...

// Controlador guardaba
$url = asset('storage/' . $path);  // http://dominio/storage/...
```

### ✅ AHORA (Independiente de APP_URL)
```php
// Modelo devuelve
return '/imagenes/tatama.jpg';  // Ruta relativa

// Controlador guarda
$url = '/storage/' . $path;  // Ruta relativa
```

El **frontend** (navegador) construye automáticamente la URL completa usando el dominio actual.

---

## 🎯 Resultado Esperado

- ✅ Imágenes funcionan en local Y servidor con la misma configuración
- ✅ No depende de `APP_URL` para imágenes
- ✅ Cambiar de dominio no requiere actualizar base de datos
- ✅ Frontend recibe rutas relativas y las procesa correctamente

---

## 📞 Soporte Adicional

Si después de seguir estos pasos las imágenes siguen rotas:

1. Ejecuta todos los comandos de verificación arriba
2. Captura los outputs y logs
3. Revisa la consola del navegador (F12) para ver qué URL exacta está intentando cargar
4. Verifica que `/imagenes/` sea accesible públicamente desde el navegador

---

**Última actualización**: 5 de marzo de 2026
**Prioridad**: 🔴 URGENTE - Ejecutar inmediatamente en servidor
