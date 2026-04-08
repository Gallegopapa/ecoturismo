# Próximos Pasos para el Backend - EcoTurismo Backend

## 📋 Resumen del Estado Actual

### ✅ Completado:
- ✅ Autenticación (Web + API con tokens)
- ✅ Sistema de roles (Admin/Usuario)
- ✅ CRUD de Lugares (con subida de imágenes)
- ✅ Vistas públicas de lugares
- ✅ Panel de administración
- ✅ API RESTful para lugares y reservas

---

## 🎯 Próximos Pasos Recomendados (Prioridad)

### 1. **Sistema de Reservas Completo** 🔴 ALTA PRIORIDAD
**Objetivo:** Permitir que los usuarios reserven lugares para visitas.

**Tareas:**
- [ ] Mejorar modelo `Reservation` con campos adicionales:
  - `fecha_reserva` (cuándo se hizo la reserva)
  - `fecha_visita` (cuándo se visitará el lugar)
  - `hora_visita` (hora de la visita)
  - `numero_personas` (ya existe como `personas`)
  - `telefono_contacto`
  - `comentarios` (notas adicionales)
  - `precio_total` (si aplica)
- [ ] Validar disponibilidad de fechas (evitar sobre-reservas)
- [ ] Vista web para que usuarios vean sus reservas
- [ ] Vista admin para gestionar reservas
- [ ] Notificaciones por email cuando se crea/cancela una reserva
- [ ] Endpoint API para cancelar reservas

**Archivos a crear/modificar:**
- `app/Http/Controllers/ReservationController.php` (mejorar)
- `app/Http/Controllers/Admin/ReservationAdminController.php` (nuevo)
- `resources/views/reservations/index.blade.php` (nuevo)
- `resources/views/admin/reservations.blade.php` (nuevo)
- `database/migrations/xxxx_improve_reservations_table.php` (nuevo)

---

### 2. **Sistema de Comentarios/Reseñas** 🟡 MEDIA PRIORIDAD
**Objetivo:** Permitir que usuarios dejen reseñas y calificaciones de lugares.

**Tareas:**
- [ ] Crear modelo `Review` con:
  - `user_id` (usuario que comenta)
  - `place_id` (lugar comentado)
  - `rating` (1-5 estrellas)
  - `comment` (texto del comentario)
  - `fecha_comentario`
- [ ] Validar que un usuario solo pueda comentar una vez por lugar
- [ ] Endpoints API para crear/editar/eliminar comentarios
- [ ] Vista para mostrar comentarios en detalle de lugar
- [ ] Panel admin para moderar comentarios
- [ ] Calcular rating promedio por lugar

**Archivos a crear:**
- `app/Models/Review.php`
- `app/Http/Controllers/ReviewController.php`
- `app/Http/Controllers/API/ReviewController.php`
- `database/migrations/xxxx_create_reviews_table.php`
- `resources/views/place/show.blade.php` (agregar sección de comentarios)

---

### 3. **Sistema de Categorías/Tags para Lugares** 🟡 MEDIA PRIORIDAD
**Objetivo:** Organizar lugares por categorías (acuáticos, montañosos, cafeteros, etc.)

**Tareas:**
- [ ] Crear modelo `Category` con:
  - `name` (nombre de categoría)
  - `slug` (URL amigable)
  - `description`
  - `icon` (opcional)
- [ ] Relación muchos a muchos entre `Place` y `Category`
- [ ] Filtrar lugares por categoría en vista pública
- [ ] Endpoint API para obtener lugares por categoría
- [ ] Panel admin para gestionar categorías

**Archivos a crear:**
- `app/Models/Category.php`
- `app/Http/Controllers/CategoryController.php`
- `database/migrations/xxxx_create_categories_table.php`
- `database/migrations/xxxx_create_category_place_table.php` (tabla pivot)

---

### 4. **Sistema de Favoritos** 🟢 BAJA PRIORIDAD
**Objetivo:** Permitir que usuarios marquen lugares como favoritos.

**Tareas:**
- [ ] Crear tabla pivot `favorites` (user_id, place_id)
- [ ] Endpoints API para agregar/eliminar favoritos
- [ ] Vista para mostrar favoritos del usuario
- [ ] Contador de favoritos por lugar

**Archivos a crear:**
- `database/migrations/xxxx_create_favorites_table.php`
- `app/Http/Controllers/API/FavoriteController.php`

---

### 5. **Mejoras en el Panel Admin** 🟡 MEDIA PRIORIDAD
**Objetivo:** Hacer el panel más completo y funcional.

**Tareas:**
- [ ] Dashboard con estadísticas:
  - Total de lugares
  - Total de usuarios
  - Total de reservas
  - Reservas pendientes
  - Lugares más visitados
- [ ] Gestión de usuarios (listar, editar, eliminar)
- [ ] Exportar datos a Excel/CSV
- [ ] Búsqueda y filtros avanzados

**Archivos a crear:**
- `app/Http/Controllers/Admin/DashboardController.php`
- `resources/views/admin/dashboard.blade.php`
- `app/Http/Controllers/Admin/UserAdminController.php`

---

### 6. **Sistema de Notificaciones** 🟢 BAJA PRIORIDAD
**Objetivo:** Notificar a usuarios sobre eventos importantes.

**Tareas:**
- [ ] Integrar Laravel Notifications
- [ ] Notificaciones por email:
  - Confirmación de reserva
  - Recordatorio de reserva (1 día antes)
  - Cancelación de reserva
- [ ] Notificaciones en la aplicación (si hay frontend React)

**Archivos a crear:**
- `app/Notifications/ReservationConfirmed.php`
- `app/Notifications/ReservationReminder.php`

---

### 7. **API Mejorada y Documentación** 🟡 MEDIA PRIORIDAD
**Objetivo:** Mejorar la API y su documentación para el frontend React.

**Tareas:**
- [ ] Agregar paginación a todos los endpoints de listado
- [ ] Agregar filtros y búsqueda en endpoints
- [ ] Mejorar respuestas de error (códigos HTTP correctos)
- [ ] Agregar rate limiting
- [ ] Documentar con Swagger/OpenAPI
- [ ] Versionar la API (`/api/v1/...`)

**Archivos a modificar:**
- `routes/api.php` (agregar versionado)
- `API_DOCUMENTATION.md` (actualizar)
- Crear `app/Http/Resources/` (API Resources para respuestas consistentes)

---

### 8. **Sistema de Pagos (Opcional)** 🔴 ALTA PRIORIDAD (si aplica)
**Objetivo:** Integrar pasarela de pagos para reservas pagadas.

**Tareas:**
- [ ] Integrar pasarela de pagos (Stripe, PayPal, o pasarela local)
- [ ] Modelo `Payment` para registrar transacciones
- [ ] Webhooks para confirmar pagos
- [ ] Actualizar estado de reserva según pago

**Archivos a crear:**
- `app/Http/Controllers/PaymentController.php`
- `app/Models/Payment.php`
- `database/migrations/xxxx_create_payments_table.php`

---

## 📝 Notas Importantes

### Orden Recomendado de Implementación:
1. **Sistema de Reservas Completo** (esencial para el negocio)
2. **Sistema de Comentarios** (mejora la experiencia)
3. **Categorías/Tags** (organización)
4. **Mejoras en Panel Admin** (facilita gestión)
5. **API Mejorada** (preparación para React)
6. Resto según necesidades

### Consideraciones Técnicas:
- Todas las nuevas tablas deben tener `timestamps()` excepto si se especifica lo contrario
- Usar `$fillable` en todos los modelos
- Validar todos los inputs
- Usar Form Requests para validaciones complejas
- Mantener consistencia en respuestas JSON de la API

### Testing:
- Considerar agregar tests unitarios para lógica crítica
- Tests de integración para endpoints API
- Tests de feature para flujos completos

---

## 🚀 Comandos Útiles

```bash
# Crear migración
php artisan make:migration create_reviews_table

# Crear modelo con migración y controlador
php artisan make:model Review -mcr

# Crear controlador de recursos (API)
php artisan make:controller API/ReviewController --api

# Crear Form Request
php artisan make:request StoreReviewRequest

# Ejecutar migraciones
php artisan migrate

# Crear seeder
php artisan make:seeder ReviewSeeder
```

---

**Última actualización:** {{ date('Y-m-d') }}
**Versión del proyecto:** 1.0.0

