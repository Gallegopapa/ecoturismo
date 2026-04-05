# Solución: Sistema de Horarios y Reservas

## 🔍 Problema Identificado

Tu base de datos no tiene la tabla `place_schedules` o está vacía, pero el código de validación de reservas **ya está completo y funcionando correctamente**. El sistema tiene:

✅ **Validaciones implementadas:**
- Verifica que el lugar esté abierto el día seleccionado
- Valida que la hora esté dentro del horario de atención
- **Evita conflictos de reservas** (cada reserva dura 2 horas, no se pueden solapar)
- Sugiere horarios alternativos cuando hay conflictos

✅ **Código completo:**
- Migración: `database/migrations/2026_01_12_162646_create_place_schedules_table.php`
- Modelo: `app/Models/PlaceSchedule.php`
- Controlador: `app/Http/Controllers/API/PlaceScheduleController.php`
- Validaciones en: `app/Http/Controllers/API/ReservationController.php`

## 🛠️ Solución Rápida

### Opción 1: Script Automático (Recomendado)

Ejecuta el script que verifica y corrige todo automáticamente:

```bash
php scripts/fix_schedules.php
```

Este script:
1. ✅ Verifica si la tabla existe
2. ✅ Ejecuta la migración si falta
3. ✅ Agrega horarios de ejemplo a lugares sin horarios

### Opción 2: Manual (Paso a Paso)

#### Paso 1: Ejecutar la migración

```bash
php artisan migrate
```

O específicamente la migración de horarios:

```bash
php artisan migrate --path=database/migrations/2026_01_12_162646_create_place_schedules_table.php
```

#### Paso 2: Verificar el estado

```bash
php scripts/check_migrations.php
```

#### Paso 3: Agregar horarios a lugares existentes

**Opción A: Usar el Seeder**

```bash
php artisan db:seed --class=PlaceScheduleSeeder
```

**Opción B: Desde el Panel de Administración**

1. Inicia sesión como administrador
2. Ve a `/admin/panel`
3. Selecciona un lugar
4. Agrega horarios manualmente desde la interfaz

## 📋 Horarios por Defecto

El seeder agrega estos horarios a cada lugar:

- **Lunes a Viernes**: 08:00 - 18:00
- **Sábado**: 08:00 - 16:00
- **Domingo**: 09:00 - 15:00

Puedes modificar estos horarios desde el panel de administración después.

## 🔐 Validaciones del Sistema de Reservas

El sistema valida automáticamente:

1. **Día disponible**: Verifica que el lugar esté abierto el día seleccionado
2. **Hora válida**: La hora debe estar dentro del horario de atención
3. **Sin conflictos**: Cada reserva dura 2 horas. Si ya hay una reserva de 10:00-12:00, no se puede reservar:
   - 09:00-11:00 ❌ (se solapa)
   - 10:00-12:00 ❌ (mismo horario)
   - 11:00-13:00 ❌ (se solapa)
   - 08:00-10:00 ✅ (no se solapa)
   - 12:00-14:00 ✅ (no se solapa)

4. **Sugerencias**: Si hay conflicto, el sistema sugiere horarios alternativos (2 horas antes o después)

## 🧪 Verificar que Funciona

### 1. Verificar que la tabla existe y tiene datos:

```bash
php scripts/check_migrations.php
```

### 2. Probar desde el frontend:

1. Ve a cualquier lugar (`/lugares/{id}`)
2. Deberías ver la sección "📅 Horarios y Disponibilidad"
3. Haz clic en "Reservar Visita"
4. Selecciona una fecha y hora
5. El sistema debería validar automáticamente

### 3. Probar desde la API:

```bash
# Ver horarios de un lugar
GET /api/places/{place_id}/schedules

# Ver horarios disponibles para una fecha específica
GET /api/places/{place_id}/available-schedules?fecha=2026-02-10
```

## 📝 Estructura de la Tabla

La tabla `place_schedules` tiene:

- `id`: ID único
- `place_id`: ID del lugar (foreign key)
- `dia_semana`: Día de la semana (lunes, martes, miercoles, jueves, viernes, sabado, domingo)
- `hora_inicio`: Hora de inicio (formato HH:mm, ej: "08:00")
- `hora_fin`: Hora de fin (formato HH:mm, ej: "18:00")
- `activo`: Si el horario está activo (boolean)
- `created_at`, `updated_at`: Timestamps

## 🎯 Próximos Pasos

1. ✅ Ejecutar `php scripts/fix_schedules.php`
2. ✅ Verificar que los horarios se muestran en el frontend
3. ✅ Probar hacer una reserva
4. ✅ Modificar horarios desde el panel de administración según necesites

## ⚠️ Notas Importantes

- **No elimines la tabla** `place_schedules` una vez creada, o perderás todos los horarios configurados
- Los horarios pueden ser modificados desde el panel de administración en cualquier momento
- Si un lugar no tiene horarios, **no se podrán hacer reservas** (el sistema lo valida)
- Cada reserva tiene una duración de **2 horas** (configurado en el código de validación)

## 🐛 Si Algo Falla

1. Verifica que la migración se ejecutó: `php scripts/check_migrations.php`
2. Verifica que hay lugares en la base de datos: `php artisan tinker` → `Place::count()`
3. Verifica que hay horarios: `PlaceSchedule::count()`
4. Revisa los logs: `storage/logs/laravel.log`

---

**¿Necesitas ayuda?** Revisa los archivos:
- `app/Http/Controllers/API/ReservationController.php` (líneas 87-213) - Validaciones
- `app/Models/PlaceSchedule.php` - Modelo de horarios
- `app/Http/Controllers/API/PlaceScheduleController.php` - CRUD de horarios
