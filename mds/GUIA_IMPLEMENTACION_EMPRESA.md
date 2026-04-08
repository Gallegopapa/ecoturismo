# 🚀 Instrucciones de Implementación - Sistema de Gestión Empresa/Lugar

## Resumen de Cambios

Se ha implementado un **sistema completo de gestión de reservas desde la perspectiva de la empresa/lugar**, donde cada lugar puede tener usuarios dedicados para aceptar/rechazar reservas de clientes.

---

## 📋 Checklist de Implementación

### ✅ Backend (Completado)

#### Fase 1: Migraciones de Base de Datos
- [x] `2026_02_09_000001_add_tipo_usuario_to_usuarios.php` - Agregar campo `tipo_usuario` a tabla usuarios
- [x] `2026_02_09_000002_create_rejection_reasons_table.php` - Crear tabla de razones de rechazo
- [x] `2026_02_09_000003_create_company_reservations_table.php` - Crear tabla de respuestas de empresa
- [x] `2026_02_09_000004_create_place_company_users_table.php` - Crear tabla de asignación lugar-empresa

#### Fase 2: Modelos Eloquent
- [x] `app/Models/Usuarios.php` - Actualizar con relaciones empresa y tipo_usuario
- [x] `app/Models/CompanyReservation.php` - Nuevo modelo para respuestas de empresa
- [x] `app/Models/RejectionReason.php` - Nuevo modelo para razones de rechazo
- [x] `app/Models/PlaceCompanyUser.php` - Nuevo modelo para asignaciones
- [x] `app/Models/Reservation.php` - Agregar relación con CompanyReservation
- [x] `app/Models/Place.php` - Agregar relaciones con usuarios empresa

#### Fase 3: Validaciones
- [x] `app/Rules/ValidPhone.php` - Validar formato de teléfono
- [x] `app/Services/ReservationValidationService.php` - Servicio de validaciones de reservas

#### Fase 4: Controllers
- [x] `app/Http/Controllers/API/AdminUserController.php` - Actualizado para tipo_usuario
- [x] `app/Http/Controllers/API/CompanyReservationController.php` - Nuevo controller
- [x] `app/Http/Controllers/API/RejectionReasonController.php` - Nuevo controller
- [x] `app/Observers/ReservationObserver.php` - Observer para crear company_reservations

#### Fase 5: Rutas
- [x] `routes/api.php` - Agregar nuevas rutas para company y admin

#### Fase 6: Seeders
- [x] `database/seeders/RejectionReasonsSeeder.php` - Crear razones predefinidas

### ✅ Frontend (Completado)

#### Componentes React
- [x] `resources/js/react/admin/CreateUserModal.jsx` - Modal para crear usuarios empresa
- [x] `resources/js/react/admin/AdminModals.css` - Estilos para modales
- [x] `resources/js/react/admin/RejectReservationModal.jsx` - Modal para rechazar reservas
- [x] `resources/js/react/admin/CompanyDashboard.jsx` - Dashboard para empresas
- [x] `resources/js/react/admin/CompanyDashboard.css` - Estilos para dashboard

#### Servicios
- [x] `resources/js/react/services/api.js` - Actualizar con nuevos endpoints

---

## 🔧 Pasos para Ejecutar las Migraciones

### 1. Ejecutar las migraciones en el orden correcto

```bash
cd c:\Users\juanj\Desktop\Laravel_Ecoturismo

# Ejecutar todas las migraciones pendientes
php artisan migrate

# O ejecutar una por una (opcional)
php artisan migrate --step

# Si necesitas rollback
php artisan migrate:rollback
```

### 2. Ejecutar los seeders

```bash
# Ejecutar el seeder de razones de rechazo
php artisan db:seed --class=RejectionReasonsSeeder

# O ejecutar todos los seeders
php artisan db:seed
```

---

## 📱 Uso del Sistema

### Para el Admin

#### Crear usuario tipo Empresa

1. Ir a Panel Admin → Usuarios
2. Hacer clic en "Crear Nuevo Usuario"
3. Completar formulario:
   - **Nombre de usuario**: ej. `ecoturismo_santa_rosa`
   - **Email**: ej. `info@ecoturismo.com`
   - **Tipo de usuario**: Seleccionar "Usuario Empresa"
   - **Lugares**: Seleccionar los lugares que gestiona
   - **Rol**: Gerente, Recepcionista o Admin del lugar
   - **Marcar Principal**: Indica quién recibe las notificaciones

4. La contraseña se genera automáticamente (mostrar al crear)
5. El admin pasa las credenciales a la empresa por fuera del sistema

---

### Para la Empresa (Usuario tipo Empresa)

1. **Acceder a Mi Panel de Reservas**
   - URL: `http://localhost:5173/company/dashboard` (o la ruta configurada)
   - Login con credenciales proporcionadas por el admin

2. **Ver Reservas Pendientes**
   - Todas las reservas de los lugares asignados aparecen aquí
   - Filtrar por estado: Pendiente, Aceptada, Rechazada

3. **Aceptar una Reserva**
   - Clic en botón "✓ Aceptar"
   - Se envía confirmación al cliente (futura notificación por email)

4. **Rechazar una Reserva**
   - Clic en botón "✕ Rechazar"
   - Se abre modal con:
     - **Razón predefinida**: Seleccionar de lista (ej: No disponibilidad, Capacidad excedida)
     - **Comentario adicional**: Campo opcional para detalles
   - Se envía notificación al cliente (futura notificación por email)

---

## 🔌 Endpoints de API

### Públicos (sin autenticación)

```
GET  /api/rejection-reasons
     → Obtener todas las razones de rechazo
```

### Protegidos - Usuario Empresa

```
GET    /api/company/reservations
       Parámetros: estado=pendiente|aceptada|rechazada
       → Listar reservas del usuario empresa

GET    /api/company/reservations/{id}
       → Ver detalles de una reserva

POST   /api/company/reservations/{id}/accept
       → Aceptar una reserva

POST   /api/company/reservations/{id}/reject
       Body: {
         rejection_reason_id: int,
         comentario: string (opcional)
       }
       → Rechazar una reserva

GET    /api/company/reservations/place/{placeId}/stats
       → Obtener estadísticas de un lugar
```

### Admin

```
GET    /api/admin/users
       → Listar todos los usuarios (actualizado con tipo_usuario)

POST   /api/admin/users
       Body: {
         name: string,
         email: string,
         password: string (opcional),
         tipo_usuario: 'normal|empresa|admin',
         lugares: [{place_id, rol, es_principal}] (si es empresa)
       }
       → Crear usuario

PUT    /api/admin/users/{id}
       → Actualizar usuario

GET    /api/admin/rejection-reasons
       → Listar razones de rechazo

POST   /api/admin/rejection-reasons
       Body: { code, descripcion, detalles }
       → Crear razón

PUT    /api/admin/rejection-reasons/{id}
       → Actualizar razón

DELETE /api/admin/rejection-reasons/{id}
       → Eliminar razón
```

---

## 🗄️ Estructura de Datos

### Tabla `usuarios` (modificada)
```sql
- id
- name
- email
- password
- tipo_usuario: ENUM('normal', 'empresa', 'admin') [NEW]
- is_admin: BOOLEAN
- ...otras columnas
```

### Tabla `company_reservations` [NEW]
```sql
- id
- reservation_id (FK → reservations)
- company_user_id (FK → usuarios)
- place_id (FK → places)
- rejection_reason_id (FK → rejection_reasons) [nullable]
- estado: ENUM('pendiente', 'aceptada', 'rechazada')
- comentario_rechazo: TEXT [nullable]
- fecha_respuesta: TIMESTAMP [nullable]
- created_at, updated_at
```

### Tabla `rejection_reasons` [NEW]
```sql
- id
- code: VARCHAR(50) UNIQUE
- descripcion: VARCHAR(255)
- detalles: TEXT [nullable]
- created_at, updated_at
```

### Tabla `place_company_users` [NEW]
```sql
- id
- place_id (FK → places)
- company_user_id (FK → usuarios)
- rol: ENUM('gerente', 'recepcionista', 'admin')
- es_principal: BOOLEAN
- created_at, updated_at
```

---

## 🎯 Flujo de una Reserva

```
1. Cliente crea reserva (estado: pendiente)
   ↓
2. Se crea registro automático en company_reservations
   (estado: pendiente, se asigna al usuario principal del lugar)
   ↓
3. Usuario empresa recibe notificación:
   - Aceptar: Se confirma al cliente
   - Rechazar: Se notifica motivo del rechazo
   ↓
4. Estado final: aceptada o rechazada
```

---

## ⚙️ Configuración Necesaria

### 1. Asegurar que el `AppServiceProvider` está registrando el observer

Verificar en `app/Providers/AppServiceProvider.php`:
```php
use App\Models\Reservation;
use App\Observers\ReservationObserver;

public function boot(): void {
    Reservation::observe(ReservationObserver::class);
}
```

### 2. Middleware de Admin

Asegurar que existe middleware `admin` en `app/Http/Middleware/` (ya debería existir)

### 3. Variables de Entorno

Si necesita configuración especial, añadir a `.env`:
```
COMPANY_RESERVATION_ENABLED=true
```

---

## 🧪 Testing

### Probar Crear Usuario Empresa

```bash
curl -X POST http://localhost:8000/api/admin/users \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "empresa_test",
    "email": "empresa@test.com",
    "tipo_usuario": "empresa",
    "lugares": [
      {"place_id": 1, "rol": "gerente", "es_principal": true}
    ]
  }'
```

### Probar Aceptar Reserva

```bash
curl -X POST http://localhost:8000/api/company/reservations/1/accept \
  -H "Authorization: Bearer COMPANY_TOKEN" \
  -H "Content-Type: application/json"
```

### Probar Rechazar Reserva

```bash
curl -X POST http://localhost:8000/api/company/reservations/1/reject \
  -H "Authorization: Bearer COMPANY_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "rejection_reason_id": 1,
    "comentario": "No hay disponibilidad en esa fecha"
  }'
```

---

## 🚨 Troubleshooting

### Error: "Specified key was too long"
→ Ya está manejado en `AppServiceProvider` con `Schema::defaultStringLength(191)`

### Error: "No tienes lugares asignados"
→ El usuario empresa necesita tener lugares asignados. Ir a Admin → Actualizar usuario.

### Las notificaciones por email no se envían
→ Están comentadas con `// TODO: Enviar email...` en los controllers. Implementar según tu sistema de emails.

### El usuario empresa no ve sus reservas
→ Verificar que:
1. El usuario tiene tipo_usuario = 'empresa'
2. El usuario está asignado al lugar
3. Hay reservas para ese lugar (estado pendiente/aceptada/rechazada)

---

## 📝 Próximos Pasos (Opcional)

1. **Notificaciones por Email**
   - Implementar Mailable para confirmación de reserva
   - Implementar Mailable para rechazo de reserva

2. **Notificaciones en Tiempo Real**
   - Agregar WebSockets con Laravel Reverb o Pusher
   - Notificar a empresa cuando se crea nueva reserva

3. **Reportes**
   - Reporte de reservas por período
   - Estadísticas de aceptación/rechazo

4. **Roles Granulares**
   - Permiso específico para cada acción (ver/aceptar/rechazar)
   - Auditoría de cambios

5. **Portal Público para Empresa**
   - Página dedicada para que la empresa edite su información
   - Sección de disponibilidad/horarios

---

## 📞 Soporte

Si encuentras algún problema:
1. Verifica que todas las migraciones se ejecutaron correctamente: `php artisan migrate:status`
2. Revisa los logs: `storage/logs/laravel.log`
3. Asegúrate de que las rutas están correctas: `php artisan route:list`
4. Verifica que el token es válido y tiene permisos correctos

