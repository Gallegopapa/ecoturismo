# Flujos de la Aplicacion - Estado Actual

Documento alineado con las rutas React y API activas en Marzo 2026.

## 1. Flujo de autenticacion

### Login
1. Usuario abre /login.
2. Frontend envia POST /api/login.
3. Backend retorna usuario y token.
4. Frontend guarda token en localStorage.
5. Usuario se redirige a /pagLogueados.

### Registro
1. Usuario completa formulario de registro.
2. Frontend envia POST /api/register.
3. Backend crea usuario y retorna token.
4. Frontend persiste sesion y redirige a /pagLogueados.

### Logout
1. Usuario ejecuta cierre de sesion.
2. Frontend envia POST /api/logout.
3. Se limpia token local.
4. Redireccion a /login o / segun flujo UI.

### Recuperacion de contrasena
1. Usuario solicita recuperacion.
2. Frontend envia POST /api/password/forgot.
3. Usuario aplica nueva contrasena con POST /api/password/reset.

## 2. Flujo de exploracion de lugares

1. Usuario entra a /lugares o a paginas tematicas.
2. Frontend consulta GET /api/places y/o GET /api/categories.
3. Usuario abre detalle en /lugares/:id.
4. Frontend consulta GET /api/places/{id}.
5. Se muestran datos, reseñas y acciones de reserva/favorito.

## 3. Flujo de ecohoteles

1. Usuario entra a /ecohoteles.
2. Frontend consulta GET /api/ecohotels.
3. Usuario abre /ecohoteles/:id.
4. Frontend consulta GET /api/ecohotels/{id}.

## 4. Flujo de reservas de usuario

1. Usuario autenticado inicia reserva desde detalle.
2. Frontend envia POST /api/reservations.
3. Usuario consulta historial en GET /api/reservations/my.
4. Usuario puede editar/cancelar via PUT/DELETE /api/reservations/{id}.

## 5. Flujo de reseñas

1. Frontend carga reseñas de lugar con GET /api/places/{id}/reviews.
2. Frontend carga reseñas de ecohotel con GET /api/ecohotels/{id}/reviews.
3. Usuario autenticado crea reseña con POST /api/reviews.
4. Usuario edita/elimina reseña con PUT/DELETE /api/reviews/{id}.

## 6. Flujo de favoritos

1. Usuario autenticado consulta favoritos con GET /api/favorites.
2. Agrega favorito con POST /api/favorites.
3. Verifica estado con GET /api/favorites/check/{placeId}.
4. Elimina con DELETE /api/favorites/{placeId}.

## 7. Flujo de perfil

1. Usuario consulta perfil con GET /api/profile.
2. Actualiza datos:
- Sin imagen: PUT /api/profile.
- Con imagen: POST /api/profile con FormData y _method=PUT.
3. Cambia clave con PUT /api/profile/password.
4. Elimina cuenta con DELETE /api/profile.

## 8. Flujo de contacto y mensajes

1. Formulario de contacto envia POST /api/contacts.
2. Mensajeria general envia POST /api/messages.
3. Admin consulta contactos con GET /api/contacts.

## 9. Flujo de empresa

Ruta UI principal: /company/dashboard

API principal:
- GET /api/company/places
- PUT/POST /api/company/places/{id}
- DELETE /api/company/places/{id}
- CRUD horarios por lugar: /api/company/places/{place}/schedules
- GET /api/company/reservations
- GET /api/company/reservations/stats
- POST /api/company/reservations/{id}/accept
- POST /api/company/reservations/{id}/reject
- POST /api/company/reservations/{id}/reopen

## 10. Flujo de administrador

Ruta UI principal: /admin

API principal:
- /api/admin/places (CRUD)
- /api/admin/ecohotels (CRUD)
- /api/admin/users (CRUD)
- GET /api/admin/reservations
- /api/admin/rejection-reasons (CRUD)
- /api/admin/places/{place}/schedules (CRUD)

## 11. Accesibilidad y traduccion

El arbol principal monta:
- AccessibilityProvider
- LanguageProvider
- AccessibilityPanel
- TranslationHelper

Esto habilita utilidades globales de accesibilidad y apoyo de idioma.

## Ultima actualizacion
Marzo 2026
