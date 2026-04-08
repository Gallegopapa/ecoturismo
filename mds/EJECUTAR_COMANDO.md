# 🚀 Comandos para Ejecutar

Como PHP no está en tu PATH de Windows, necesitas ejecutar estos comandos manualmente. Aquí tienes las opciones:

## Opción 1: Usar el Comando Artisan Personalizado (Recomendado)

He creado un comando personalizado que hace todo automáticamente. Ejecuta:

```bash
php artisan schedules:fix
```

Este comando:
1. ✅ Verifica si la tabla existe
2. ✅ Ejecuta la migración si falta
3. ✅ Agrega horarios a lugares sin horarios

## Opción 2: Ejecutar Manualmente (Paso a Paso)

Si prefieres hacerlo paso a paso:

### Paso 1: Ejecutar la migración
```bash
php artisan migrate --path=database/migrations/2026_01_12_162646_create_place_schedules_table.php
```

### Paso 2: Agregar horarios a lugares existentes
```bash
php artisan db:seed --class=PlaceScheduleSeeder
```

## Opción 3: Si PHP no está en el PATH

Si `php` no funciona directamente, busca la ruta de PHP en tu sistema:

### XAMPP:
```bash
C:\xampp\php\php.exe artisan schedules:fix
```

### Laragon:
```bash
C:\laragon\bin\php\php-8.2.x\php.exe artisan schedules:fix
```

### WAMP:
```bash
C:\wamp64\bin\php\php8.2.x\php.exe artisan schedules:fix
```

### O busca PHP en tu sistema:
1. Abre el Explorador de Archivos
2. Busca `php.exe` en tu disco C:
3. Copia la ruta completa
4. Usa esa ruta en lugar de `php`

## Verificar que Funcionó

Después de ejecutar el comando, puedes verificar:

```bash
php artisan tinker
```

Luego en tinker:
```php
PlaceSchedule::count()  // Debería mostrar el número de horarios creados
Place::whereHas('schedules')->count()  // Debería mostrar lugares con horarios
```

## 📝 Nota

Si tienes problemas para encontrar PHP, también puedes:
1. Abrir tu terminal desde donde normalmente ejecutas `php artisan serve`
2. Usar esa misma terminal para ejecutar `php artisan schedules:fix`
