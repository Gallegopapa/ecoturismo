# Arquitectura del Sistema - Risaralda EcoTurismo

## 📐 Visión General

Risaralda EcoTurismo es una aplicación web full-stack que utiliza una arquitectura de **separación de frontend y backend**, comunicándose a través de una API REST.

```
┌─────────────────┐         HTTP/REST API         ┌─────────────────┐
│                 │◄──────────────────────────────►│                 │
│   React Frontend │         (Laravel Sanctum)      │  Laravel Backend│
│   (Vite + React) │                                │   (PHP + MySQL) │
│                 │                                │                 │
└─────────────────┘                                └─────────────────┘
```

## 🏗 Arquitectura del Backend

### Estructura MVC

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── API/              # Controladores de API REST
│   │   ├── Admin/            # Controladores de panel admin
│   │   └── Auth/             # Controladores de autenticación web
│   └── Middleware/
│       ├── AdminMiddleware   # Verificación de permisos admin
│       └── Authenticate     # Autenticación Sanctum
├── Models/                   # Modelos Eloquent ORM
└── Providers/                # Service Providers
```

### Flujo de Peticiones API

```
Cliente (React)
    ↓
Axios Interceptor (agrega token)
    ↓
routes/api.php
    ↓
Middleware: auth:sanctum
    ↓
Controller (API)
    ↓
Model (Eloquent)
    ↓
Database (SQLite/MySQL)
    ↓
Response JSON
```

### Autenticación

**Laravel Sanctum** se utiliza para autenticación basada en tokens:

1. Usuario hace login → Recibe token
2. Token se almacena en `localStorage` (frontend)
3. Token se envía en header `Authorization: Bearer {token}`
4. Middleware `auth:sanctum` valida el token
5. Token expira después de 30 días

## 🎨 Arquitectura del Frontend

### Estructura React

```
resources/js/react/
├── components/          # Componentes reutilizables
│   ├── Header/
│   ├── Footer/
│   ├── Cards/
│   └── ReservationModal/
├── context/            # Context API (AuthContext)
├── services/           # Servicios API (api.js)
├── admin/              # Panel de administración
├── places/             # Páginas de lugares
├── login/              # Autenticación
├── contact/            # Formulario de contacto
└── map/                # Mapa interactivo
```

### Flujo de Datos Frontend

```
Componente React
    ↓
useAuth() Hook (Context)
    ↓
services/api.js (Axios)
    ↓
Interceptor (agrega token)
    ↓
API Laravel
    ↓
Response → Estado del Componente
```

### Context API

**AuthContext** maneja el estado global de autenticación:

```javascript
{
  user: Usuario actual,
  isAuthenticated: boolean,
  login: función,
  logout: función,
  register: función
}
```

## 🗄 Base de Datos

### Modelos Principales

```
usuarios
├── id
├── name
├── email
├── password
├── telefono
├── foto_perfil
├── is_admin
└── fecha_registro

places
├── id
├── name
├── description
├── location
├── image
├── latitude
└── longitude

reservations
├── id
├── user_id → usuarios
├── place_id → places
├── fecha_visita
├── hora_visita
├── personas
└── estado

reviews
├── id
├── user_id → usuarios
├── place_id → places
├── rating
└── comment

favorites
├── id
├── user_id → usuarios
└── place_id → places

contacts
├── id
├── name
├── email
├── phone
├── message
└── user_id → usuarios (nullable)

categories
├── id
├── name
├── description
└── slug

category_place (pivot)
├── category_id
└── place_id
```

### Relaciones

- **Usuario** → tiene muchas **Reservas**, **Reseñas**, **Favoritos**
- **Lugar** → pertenece a muchas **Categorías** (many-to-many)
- **Lugar** → tiene muchas **Reseñas**, **Reservas**
- **Reserva** → pertenece a un **Usuario** y un **Lugar**

## 🔄 Flujos Principales

### 1. Flujo de Autenticación

```
Usuario → Login Form
    ↓
POST /api/login
    ↓
AuthController::login()
    ↓
Validar credenciales
    ↓
Generar token Sanctum
    ↓
Response: { user, token }
    ↓
Guardar token en localStorage
    ↓
Actualizar AuthContext
    ↓
Redirigir a /pagLogueados
```

### 2. Flujo de Reserva

```
Usuario → Selecciona lugar
    ↓
Click "Reservar Visita"
    ↓
Abrir ReservationModal
    ↓
Llenar formulario
    ↓
POST /api/reservations
    ↓
ReservationController::store()
    ↓
Validar datos
    ↓
Crear reserva en BD
    ↓
Response: { reservation }
    ↓
Cerrar modal
    ↓
Mostrar mensaje de éxito
```

### 3. Flujo de Contacto

```
Usuario → Formulario de contacto
    ↓
Llenar datos (name, email, phone, message)
    ↓
POST /api/contacts
    ↓
ContactController::store()
    ↓
Validar datos
    ↓
Crear contacto en BD (con user_id si está autenticado)
    ↓
Response: { message, data }
    ↓
Mostrar mensaje de éxito
    ↓
Limpiar formulario
```

### 4. Flujo de Administración

```
Admin → Panel de administración
    ↓
Ver lista de lugares/usuarios/reservas
    ↓
Crear/Editar/Eliminar
    ↓
POST/PUT/DELETE /api/admin/*
    ↓
AdminController (con middleware admin)
    ↓
Validar permisos (is_admin = true)
    ↓
Procesar operación
    ↓
Response
    ↓
Actualizar UI
```

## 🔐 Seguridad

### Middleware

1. **auth:sanctum**: Verifica token válido
2. **admin**: Verifica que el usuario sea administrador

### Validación

- **Backend**: Validación en controladores usando `Validator`
- **Frontend**: Validación en componentes antes de enviar

### Protección CSRF

- Sanctum maneja CSRF automáticamente para SPA
- Tokens en headers, no en cookies

## 📦 Servicios y Utilidades

### API Service (`services/api.js`)

Centraliza todas las llamadas a la API:

```javascript
- authService: login, register, logout
- placesService: CRUD de lugares
- reservationsService: CRUD de reservas
- reviewsService: CRUD de reseñas
- favoritesService: CRUD de favoritos
- contactsService: Envío de contactos
- profileService: Gestión de perfil
- adminService: Operaciones de admin
```

### Interceptores Axios

- **Request**: Agrega token automáticamente
- **Response**: Maneja errores 401 (logout automático)

## 🗺 Mapa Interactivo

### Tecnología

- **Leaflet**: Biblioteca de mapas
- **React-Leaflet**: Wrapper para React

### Flujo

```
MapPage
    ↓
Cargar lugares con coordenadas
    ↓
Renderizar marcadores en mapa
    ↓
Click en marcador
    ↓
Mostrar popup con información
    ↓
Navegar a página de detalles
```

## 🎯 Patrones de Diseño Utilizados

1. **MVC**: Separación de responsabilidades
2. **Repository Pattern**: Modelos Eloquent actúan como repositorios
3. **Service Layer**: Servicios API en frontend
4. **Context API**: Estado global de autenticación
5. **Component Composition**: Componentes reutilizables

## 📊 Rendimiento

### Optimizaciones

- **Lazy Loading**: Componentes cargados bajo demanda
- **Code Splitting**: Vite divide el código automáticamente
- **Caching**: Laravel cache para configuraciones
- **Eager Loading**: Relaciones cargadas eficientemente

### Base de Datos

- **Índices**: En claves foráneas y campos de búsqueda
- **Relaciones**: Optimizadas con `with()`

## 🚀 Escalabilidad

### Posibles Mejoras

1. **Caché Redis**: Para sesiones y datos frecuentes
2. **Queue System**: Para emails y tareas pesadas
3. **CDN**: Para imágenes y assets estáticos
4. **API Rate Limiting**: Limitar peticiones por usuario
5. **Database Sharding**: Si crece mucho el volumen

---

**Última actualización**: Diciembre 2025

