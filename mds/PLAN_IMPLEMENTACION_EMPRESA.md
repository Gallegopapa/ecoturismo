# Plan de Implementación: Sistema de Gestión de Empresa/Lugar

## 🎯 Objetivo General
Crear un sistema donde cada lugar (empresa/ecoturismo) tenga su propio usuario/cuenta para gestionar las reservas que clientes realizan en su lugar, con opción de aceptar o rechazar reservas con motivos.

---

## 📊 Cambios de Base de Datos Necesarios

### 1. Agregar campo `tipo_usuario` a tabla `usuarios`
- **Campo**: `tipo_usuario` (enum: 'normal', 'empresa', 'admin')
- **Default**: 'normal'
- **Propósito**: Diferenciar tipos de usuarios

### 2. Nueva tabla: `company_reservations`
Esta tabla actúa como intermedia para gestionar el estado de las reservas desde el lado de la empresa.

```
- id (PK)
- reservation_id (FK -> reservations.id)
- company_user_id (FK -> usuarios.id) - Usuario de la empresa
- place_id (FK -> places.id)
- estado ('pendiente', 'aceptada', 'rechazada') - Default: 'pendiente'
- motivo_rechazo (nullable) - Razón si fue rechazada
- fecha_respuesta (nullable - datetime)
- created_at
- updated_at
```

### 3. Nueva tabla: `rejection_reasons`
Tabla de catálogo con razones predefinidas de rechazo.

```
- id (PK)
- code (string, unique) - 'no_disponibilidad', 'error_datos', 'capacidad_excedida', 'otro'
- descripcion (string)
- created_at
- updated_at
```

### 4. Nueva tabla: `place_company_users`
Relación entre lugares y usuarios de empresa (un lugar puede tener múltiples usuarios empresa, un usuario empresa puede gestionar múltiples lugares).

```
- id (PK)
- place_id (FK -> places.id)
- company_user_id (FK -> usuarios.id) - Usuario tipo 'empresa'
- rol ('gerente', 'recepcionista', 'admin') - Default: 'gerente'
- es_principal (boolean) - Default: false (el principal recibe notificaciones)
- created_at
- updated_at
```

---

## 🔄 Cambios en Modelos

### Usuarios.php
```php
// Agregar campos
- tipo_usuario: 'normal' | 'empresa' | 'admin'

// Agregar relaciones
- companyUsers() - hasMany PlaceCompanyUser
- placesManaged() - belongsToMany Place through PlaceCompanyUser
- companyReservations() - hasMany CompanyReservation
```

### Place.php
```php
// Agregar relaciones
- companyUsers() - belongsToMany Usuarios through PlaceCompanyUser
- companyReservations() - hasMany CompanyReservation
```

### Reservation.php
```php
// Agregar relaciones
- companyReservation() - hasOne CompanyReservation
```

### Nuevos Modelos
- **CompanyReservation.php** - Gestiona respuesta de empresa a reserva
- **RejectionReason.php** - Catálogo de razones de rechazo
- **PlaceCompanyUser.php** - Relación lugar-usuario empresa

---

## 🛣️ Cambios en Rutas API

### Admin - Gestión de Usuarios
```
POST   /admin/users
       - Crear usuario (normal, empresa, admin)
       - Validar: tipo_usuario requerido

PUT    /admin/users/{user}
       - Actualizar usuario incluyendo tipo_usuario

POST   /admin/users/{user}/assign-places
       - Asignar lugares a usuario empresa
       - Body: { places: [id1, id2], rol: 'gerente' }
```

### Admin - Gestión de Razones de Rechazo
```
GET    /admin/rejection-reasons
       - Listar todas las razones predefinidas

POST   /admin/rejection-reasons
       - Crear nueva razón (solo admin)

PUT    /admin/rejection-reasons/{reason}
       - Actualizar razón
```

### Usuario Empresa - Gestión de Reservas
```
GET    /company/reservations
       - Listar reservas de los lugares que gestiona
       - Filtros: estado, lugar, fecha

GET    /company/reservations/{reservation}
       - Ver detalles de una reserva

POST   /company/reservations/{reservation}/accept
       - Aceptar una reserva
       - Devuelve: confirmación, datos para enviar email

POST   /company/reservations/{reservation}/reject
       - Rechazar una reserva
       - Body: { motivo_id: int (opcional), comentario: string (opcional) }
       - Devuelve: confirmación, datos para enviar email
```

### Validaciones Adicionales
```
GET    /api/validations/phone
       - Validar formato de teléfono

GET    /api/validations/email-availability
       - Verificar si email está disponible (sin registrar)

POST   /api/validations/reservation-overlap
       - Validar si hay solapamiento de reservas en un horario
```

---

## 💻 Cambios en Controllers

### AdminUserController (Mejorado)
- Agregar lógica para crear usuarios tipo 'empresa'
- Agregar método `assignPlaces()` para asignar lugares a empresa
- Incluir `tipo_usuario` en validaciones y respuestas

### Nuevo: CompanyReservationController
```php
- index()          // Listar reservas de la empresa
- show()           // Ver detalle de reserva
- accept()         // Aceptar reserva
- reject()         // Rechazar reserva con motivo
```

### Nuevo: RejectionReasonController
```php
- index()          // Listar razones (público)
- store()          // Crear (admin)
- update()         // Actualizar (admin)
- destroy()        // Eliminar (admin)
```

### ReservationController (Mejorado)
- Cuando se crea una reserva, automáticamente crear registro en `company_reservations` con estado 'pendiente'

---

## ✅ Validaciones Adicionales Recomendadas

### 1. Validación de Teléfono
- Formato: +[país] [número] o formato local
- Rango: 7-15 dígitos
- Crear validación personalizada en `app/Rules/ValidPhone.php`

### 2. Validación de Superposición de Reservas
- Evitar que dos personas reserven el mismo lugar en el mismo horario
- Considerar duración de la actividad

### 3. Validación de Disponibilidad por Horario
- Verificar `place_schedules` para ver si está abierto en la fecha/hora
- Si no hay horario registrado, rechazar reserva

### 4. Validación de Capacidad
- Verificar que el número de personas no exceda la capacidad del lugar
- Agregar campo `capacidad_maxima` a tabla `places`

### 5. Validación de Email
- Verificar formato válido
- No permitir emails temporales (opcionales)

---

## 🎨 Componentes React Necesarios

### Panel Admin
1. **UserManagementPanel.jsx**
   - Tabla de usuarios
   - Botones: Crear, Editar, Eliminar
   - Modal para crear usuario (seleccionar tipo)

2. **CreateUserModal.jsx**
   - Formulario con tipos: Normal, Empresa, Admin
   - Si es Empresa: selector múltiple de lugares
   - Generación de contraseña automática
   - Mostrar credenciales al crear

3. **RejectionReasonsPanel.jsx**
   - Tabla de razones predefinidas
   - CRUD para razones

### Vista Empresa
1. **CompanyReservationsList.jsx**
   - Tabla de reservas pendientes
   - Filtros: estado, lugar, fecha
   - Acciones: Ver detalle, Aceptar, Rechazar

2. **ReservationDetailModal.jsx**
   - Mostrar datos completos de la reserva
   - Datos del cliente
   - Botones para aceptar/rechazar

3. **RejectReservationModal.jsx**
   - Selector de razón predefinida
   - Campo para comentario adicional
   - Confirmación antes de rechazar

4. **CompanyDashboard.jsx**
   - Resumen: Pendientes, Aceptadas, Rechazadas
   - Gráficos de estadísticas
   - Acceso rápido a gestión de reservas

---

## 📋 Orden de Implementación Recomendado

### Fase 1: Base de Datos
1. ✅ Crear migración para `tipo_usuario`
2. ✅ Crear migración para `rejection_reasons`
3. ✅ Crear migración para `company_reservations`
4. ✅ Crear migración para `place_company_users`

### Fase 2: Modelos
1. ✅ Actualizar `Usuarios` con `tipo_usuario`
2. ✅ Crear `CompanyReservation`
3. ✅ Crear `RejectionReason`
4. ✅ Crear `PlaceCompanyUser`
5. ✅ Actualizar relaciones en todos los modelos

### Fase 3: Validaciones
1. ✅ Crear `ValidPhone` rule
2. ✅ Crear método de validación de solapamiento
3. ✅ Crear método de validación de disponibilidad

### Fase 4: Controllers
1. ✅ Actualizar `AdminUserController`
2. ✅ Crear `CompanyReservationController`
3. ✅ Crear `RejectionReasonController`
4. ✅ Actualizar `ReservationController`

### Fase 5: Rutas
1. ✅ Actualizar rutas de admin
2. ✅ Crear rutas de empresa
3. ✅ Crear rutas de validaciones

### Fase 6: Frontend
1. ✅ Componentes de Panel Admin
2. ✅ Componentes de Vista Empresa

---

## 🔐 Consideraciones de Seguridad

- Usuarios 'empresa' solo ven sus propias reservas
- Middleware para verificar que usuario empresa gestiona ese lugar
- Logs de cambios de estado de reservas
- Notificaciones por email al aceptar/rechazar

---

## 📧 Flujo de Notificaciones

### Cuando se crea una reserva
→ Enviar email al usuario empresa principal notificando nueva reserva pendiente

### Cuando se acepta una reserva
→ Enviar email al cliente confirmando su reserva

### Cuando se rechaza una reserva
→ Enviar email al cliente con motivo del rechazo

---

## 💾 Datos Iniciales (Seeder)

```php
// Crear razones de rechazo predefinidas
'no_disponibilidad' => 'No hay disponibilidad en esa fecha',
'capacidad' => 'La capacidad ha sido excedida',
'error_datos' => 'Error o datos incompletos',
'mantenimiento' => 'En mantenimiento durante esa fecha',
'otro' => 'Otro motivo'
```

---

## ✨ Mejoras Futuras (No incluidas en esta fase)

- Sistema de notificaciones en tiempo real (WebSockets)
- Calendario visual de reservas
- Reportes y estadísticas avanzadas
- Integración con sistemas de pago
- Multi-idioma para notificaciones
- Sistema de permisos granulares (roles)

