# Documentacion de API - Estado Actual (Marzo 2026)

Base URL local:
http://localhost:8000/api

La API usa tokens Bearer con Laravel Sanctum para rutas protegidas.

Headers recomendados:
- Accept: application/json
- Authorization: Bearer {token} (solo rutas protegidas)

## 1. Endpoints publicos

### Autenticacion
- POST /login
- POST /register
- POST /password/forgot
- POST /password/reset

### Perfil publico
- GET /profile/photo/{filename}

### Lugares
- GET /places
- GET /places/options
- GET /places/{place}
- GET /places/{place}/available-schedules
- GET /places/{place}/schedules

### Ecohoteles
- GET /ecohotels
- GET /ecohotels/{ecohotel}

### Categorias
- GET /categories
- GET /categories/{category}

### Reseñas publicas
- GET /reviews/all
- GET /places/{id}/reviews
- GET /ecohotels/{id}/reviews

### Razones de rechazo
- GET /rejection-reasons

### Mensajes y contacto
- POST /messages
- POST /contacts

## 2. Endpoints protegidos (auth:sanctum)

### Sesion
- POST /logout
- POST /logout-all
- GET /user
- GET /verify-token

### Perfil
- GET /profile
- POST /profile (FormData, usado cuando hay imagen)
- PUT /profile (JSON)
- PUT /profile/password
- DELETE /profile

### Reservas de usuario
- GET /reservations/my
- GET /reservations
- POST /reservations
- GET /reservations/{reservation}
- PUT /reservations/{reservation}
- DELETE /reservations/{reservation}

### Reseñas autenticadas
- POST /reviews
- PUT /reviews/{review}
- DELETE /reviews/{review}

### Favoritos
- GET /favorites
- GET /favorites/check/{placeId}
- POST /favorites
- DELETE /favorites/{placeId}

### Pagos (boceto)
- GET /payments
- POST /payments

### Contactos (solo admin)
- GET /contacts
- GET /contacts/{contact}

## 3. Modulo empresa (auth:sanctum)

Prefijo: /company

### Lugares de empresa
- GET /company/places
- GET /company/places/{place}
- POST /company/places/{place}
- PUT /company/places/{place}
- DELETE /company/places/{place}

### Horarios por lugar
- GET /company/places/{place}/schedules
- POST /company/places/{place}/schedules
- PUT /company/places/{place}/schedules/{schedule}
- DELETE /company/places/{place}/schedules/{schedule}

### Reservas de empresa
- GET /company/reservations
- GET /company/reservations/stats
- GET /company/reservations/{companyReservation}
- POST /company/reservations/{companyReservation}/accept
- POST /company/reservations/{companyReservation}/reject
- POST /company/reservations/{companyReservation}/reopen
- GET /company/reservations/place/{placeId}/stats

## 4. Modulo admin (auth:sanctum + admin)

Prefijo: /admin

### Lugares
- GET /admin/places
- GET /admin/places/{place}
- POST /admin/places
- POST /admin/places/{place}
- PUT /admin/places/{place}
- DELETE /admin/places/{place}

### Ecohoteles
- GET /admin/ecohotels
- POST /admin/ecohotels
- GET /admin/ecohotels/{ecohotel}
- PUT /admin/ecohotels/{ecohotel}
- DELETE /admin/ecohotels/{ecohotel}

### Usuarios
- GET /admin/users
- GET /admin/users/{user}
- POST /admin/users
- PUT /admin/users/{user}
- DELETE /admin/users/{user}

### Reservas globales
- GET /admin/reservations

### Razones de rechazo
- GET /admin/rejection-reasons
- POST /admin/rejection-reasons
- GET /admin/rejection-reasons/{reason}
- PUT /admin/rejection-reasons/{reason}
- DELETE /admin/rejection-reasons/{reason}

### Horarios de lugares
- GET /admin/places/{place}/schedules
- POST /admin/places/{place}/schedules
- PUT /admin/places/{place}/schedules/{schedule}
- DELETE /admin/places/{place}/schedules/{schedule}

## 5. Notas de integracion frontend

- El frontend usa baseURL relativa /api.
- Si el token expira (401), frontend limpia sesion local y redirige a /login.
- Perfil con foto usa FormData y _method=PUT para compatibilidad con Laravel.

## 6. Referencias de codigo

- Rutas API: routes/api.php
- Cliente frontend: resources/js/react/services/api.js
