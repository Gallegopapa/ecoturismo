# 🔧 Solución para Errores de Migración

## Problema Común

Si tu compañero recibe un error al ejecutar `php artisan migrate` o le dice "no hay nada para migrar", puede ser porque:

1. La migración ya está registrada en la tabla `migrations` pero la tabla `place_schedules` no existe realmente
2. Hay un error en la migración (foreign key, enum, etc.)
3. La migración no se detecta correctamente

## ✅ Solución Automática (Recomendada)

Usa el comando personalizado que maneja todos estos casos:

```bash
php artisan schedules:fix
```

Este comando:
- ✅ Verifica si la tabla existe realmente
- ✅ Intenta ejecutar la migración si falta
- ✅ Si la migración falla, crea la tabla directamente
- ✅ Agrega horarios a lugares sin horarios
- ✅ Funciona incluso si la migración ya está "ejecutada" pero la tabla no existe

## 🔍 Solución Manual (Paso a Paso)

### Paso 1: Verificar el Estado

```bash
# Ver estado de migraciones
php artisan migrate:status

# Verificar si la tabla existe realmente
php artisan tinker
```

En tinker:
```php
use Illuminate\Support\Facades\Schema;
Schema::hasTable('place_schedules')  // Debe devolver true o false
```

### Paso 2: Si la Tabla NO Existe

#### Opción A: Ejecutar el Comando Personalizado
```bash
php artisan schedules:fix
```

#### Opción B: Crear la Tabla Manualmente

Si el comando falla, puedes crear la tabla directamente desde tinker:

```bash
php artisan tinker
```

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Schema::create('place_schedules', function ($table) {
    $table->id();
    $table->unsignedBigInteger('place_id');
    $table->enum('dia_semana', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
    $table->time('hora_inicio');
    $table->time('hora_fin');
    $table->boolean('activo')->default(true);
    $table->timestamps();

    $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
    $table->index(['place_id', 'dia_semana']);
});

// Marcar la migración como ejecutada (opcional)
DB::table('migrations')->insert([
    'migration' => '2026_01_12_162646_create_place_schedules_table',
    'batch' => DB::table('migrations')->max('batch') + 1
]);
```

### Paso 3: Agregar Horarios

Después de crear la tabla, agrega los horarios:

```bash
php artisan db:seed --class=PlaceScheduleSeeder
```

O usa el comando personalizado que hace todo:

```bash
php artisan schedules:fix
```

## 🐛 Errores Comunes y Soluciones

### Error: "Table 'place_schedules' already exists"

**Solución**: La tabla ya existe. Solo necesitas agregar horarios:

```bash
php artisan db:seed --class=PlaceScheduleSeeder
```

### Error: "Foreign key constraint fails"

**Solución**: Verifica que la tabla `places` existe:

```bash
php artisan tinker
```

```php
use Illuminate\Support\Facades\Schema;
Schema::hasTable('places')  // Debe ser true
```

### Error: "No migrations to run"

**Solución**: La migración está marcada como ejecutada pero la tabla no existe. Usa:

```bash
php artisan schedules:fix
```

Este comando verifica si la tabla existe realmente y la crea si falta.

### Error: "Enum value not valid"

**Solución**: Verifica que estás usando MySQL 5.7+ o MariaDB 10.2+. El enum debe tener estos valores exactos:

```php
['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']
```

## 📋 Verificación Final

Después de ejecutar cualquier solución, verifica que todo funciona:

```bash
php artisan tinker
```

```php
use App\Models\Place;
use App\Models\PlaceSchedule;

// Verificar que la tabla existe
Schema::hasTable('place_schedules')

// Contar horarios
PlaceSchedule::count()

// Ver lugares con horarios
Place::whereHas('schedules')->count()

// Ver un lugar con sus horarios
$place = Place::with('schedules')->first();
$place->schedules;
```

## 🎯 Comando Todo-en-Uno

El comando `php artisan schedules:fix` maneja todos estos casos automáticamente:

```bash
php artisan schedules:fix
```

Este es el método más seguro y recomendado.

---

**Nota**: Si sigues teniendo problemas, comparte el mensaje de error exacto para poder ayudarte mejor.
