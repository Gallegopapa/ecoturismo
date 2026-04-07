# 🚀 Guía de Inicio - Laravel con React (SPA)

## ✅ Configuración Completada

El proyecto ha sido configurado para usar **React como frontend principal** en lugar de las vistas Blade. Ahora tienes una **Single Page Application (SPA)** completamente funcional.

---

## 📁 Estructura del Proyecto

```
Laravel_Ecoturismo/
├── resources/
│   ├── js/
│   │   ├── app.js              # Punto de entrada principal
│   │   ├── App.jsx             # Componente raíz antiguo (puede eliminarse)
│   │   └── react/
│   │       ├── App.jsx         # Componente principal de React
│   │       ├── main.jsx        # Configuración de rutas (ya no se usa)
│   │       ├── index.css       # Estilos globales
│   │       ├── components/     # Componentes reutilizables
│   │       ├── login/          # Página de login
│   │       ├── perfil/         # Página de perfil
│   │       ├── settings/       # Página de configuración
│   │       ├── places/         # Página de lugares
│   │       ├── comments/       # Página de comentarios
│   │       └── contact/        # Página de contacto
│   └── views/
│       └── app.blade.php       # Vista maestra que monta React
├── routes/
│   ├── web.php                 # Rutas web (API + React fallback)
│   └── api.php                 # Rutas API
└── vite.config.js              # Configuración de Vite con React
```

---

## 🎯 Cómo Funciona

### 1. **Vista Maestra** (`resources/views/app.blade.php`)
Esta es la ÚNICA vista Blade que se sirve. Contiene:
- El `<div id="root"></div>` donde React se monta
- El CSRF token para las peticiones
- Datos del usuario autenticado inyectados como JavaScript

### 2. **Punto de Entrada** (`resources/js/app.js`)
- Importa React y React Router
- Define todas las rutas de la aplicación
- Monta la aplicación React en el div `#root`

### 3. **Componentes React** (`resources/js/react/`)
Todos los componentes JSX están organizados por funcionalidad:
- `App.jsx` - Página principal
- `login/page.jsx` - Login/Registro
- `perfil/page.jsx` - Perfil de usuario
- `settings/Page.jsx` - Configuración
- etc.

### 4. **Rutas** (`routes/web.php`)
Las rutas están organizadas así:
1. **Rutas de autenticación** (POST) - `/login`, `/registro`, `/logout`
2. **Rutas API** (GET, POST, PUT, DELETE) - `/reservas`, `/favoritos`, `/perfil`, etc.
3. **Fallback a React** (GET) - Todas las demás rutas sirven `app.blade.php`

---

## 🚀 Comandos para Iniciar el Proyecto

### 1️⃣ Instalar Dependencias

```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node.js
npm install
```

### 2️⃣ Configurar el Entorno

```bash
# Copiar el archivo de entorno (si no existe)
copy .env.example .env

# Generar la clave de la aplicación
php artisan key:generate

# Ejecutar las migraciones
php artisan migrate

# (Opcional) Ejecutar los seeders
php artisan db:seed
```

### 3️⃣ Iniciar el Servidor de Desarrollo

**Opción A: Usar dos terminales separadas**

Terminal 1 - Backend (Laravel):
```bash
php artisan serve
```

Terminal 2 - Frontend (Vite):
```bash
npm run dev
```

**Opción B: Usar un solo comando** (recomendado)

Primero, actualiza tu `package.json`:
```json
"scripts": {
    "dev": "vite",
    "build": "vite build",
    "serve": "concurrently \"php artisan serve\" \"npm run dev\""
}
```

Luego ejecuta:
```bash
npm run serve
```

### 4️⃣ Acceder a la Aplicación

- **Frontend React**: http://localhost:8000
- **Backend API**: http://localhost:8000/api/*

---

## 🔄 Flujo de Navegación

### Primera Carga
1. Usuario accede a `http://localhost:8000/`
2. Laravel sirve `app.blade.php`
3. Vite carga `app.js`
4. React Router monta el componente correspondiente a la ruta

### Navegación Interna
1. Usuario hace clic en un enlace (ej: `/lugares`)
2. React Router intercepta la navegación
3. NO se recarga la página
4. Se muestra el componente correspondiente

### Llamadas API
1. Componente React hace una petición (ej: `POST /reservas`)
2. Laravel procesa la petición en el controller
3. Valida los datos
4. Retorna JSON
5. React actualiza la UI

---

## 📡 Rutas Disponibles

### Rutas de React (GET - Frontend)
```
/                      → App.jsx (Página principal)
/login                 → login/page.jsx
/registro              → login/page.jsx (mismo componente)
/lugares               → places/page.jsx
/comentarios           → comments/page.jsx
/comentarios2          → comments2/page.jsx
/contacto              → contact/Contacto.jsx
/contacto2             → contact/Contacto.jsx
/configuracion         → settings/Page.jsx
/perfil                → perfil/page.jsx
/pagLogueados          → pagLogueados.jsx
/paraisosAcuaticos     → places2/paraisosAcuaticos/page.jsx
/lugaresMontanosos     → places2/lugaresMontanosos/page.jsx
/parquesYMas           → places2/parquesYMas/page.jsx
/territoriosDelCafe    → places2/territoriosDelCafe/page.jsx
```

### Rutas de API (Backend)
```
POST   /login              → Iniciar sesión
POST   /logout             → Cerrar sesión
POST   /registro           → Registrar usuario
GET    /reservas           → Listar reservas
POST   /reservas           → Crear reserva
DELETE /reservas/{id}      → Eliminar reserva
POST   /favoritos          → Agregar favorito
DELETE /favoritos/{id}     → Eliminar favorito
POST   /reviews            → Crear reseña
DELETE /reviews/{id}       → Eliminar reseña
PUT    /perfil             → Actualizar perfil
PUT    /perfil/password    → Cambiar contraseña
POST   /mensajes           → Enviar mensaje
```

---

## 🔐 Autenticación

### Acceso a Datos del Usuario en React

```javascript
// En cualquier componente React
const user = window.Laravel?.user;
const isAdmin = window.Laravel?.isAdmin;

// IMPORTANTE: Nunca loguear datos del usuario en la consola
// Usa el AuthContext en su lugar
if (user) {
  // BIEN: Usar el contexto
  const { user, isAuthenticated } = useAuth();
  // NO BIEN: No loguear datos sensibles
  // console.log('Usuario:', user); ← ¡NUNCA HACER ESTO!
}
```

### Hacer Peticiones Autenticadas

```javascript
// Obtener CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// Petición POST
const response = await fetch('/reservas', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': csrfToken
  },
  credentials: 'same-origin',
  body: JSON.stringify(data)
});
```

---

## 🎨 Estilos

Los estilos están en:
- `resources/js/react/index.css` - Estilos globales
- `resources/js/react/pagLogueados.css` - Estilos específicos
- `resources/css/app.css` - Estilos de Tailwind

---

## 🔧 Desarrollo

### Agregar una Nueva Página

1. **Crear el componente**:
```jsx
// resources/js/react/miPagina/page.jsx
import React from 'react';

const MiPagina = () => {
  return (
    <div>
      <h1>Mi Nueva Página</h1>
    </div>
  );
};

export default MiPagina;
```

2. **Agregar la ruta en `app.js`**:
```javascript
import MiPagina from './react/miPagina/page.jsx';

const router = createBrowserRouter([
  // ... rutas existentes
  {
    path: '/mi-pagina',
    element: <MiPagina />,
  },
]);
```

3. **Listo!** Ya puedes acceder a `http://localhost:8000/mi-pagina`

### Agregar un Endpoint API

1. **Crear el método en el controller**
2. **Agregar la ruta en `routes/web.php` o `routes/api.php`**
3. **Consumirlo desde React con fetch/axios**

---

## 🐛 Solución de Problemas

### El servidor no inicia
```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Reiniciar servidor
php artisan serve
```

### Los cambios de React no se ven
```bash
# Detener Vite (Ctrl+C)
# Limpiar node_modules y reinstalar
rm -rf node_modules
npm install
npm run dev
```

### Error 404 en las rutas
- Asegúrate de que el servidor Laravel esté corriendo
- Verifica que la ruta esté definida en `app.js`
- El fallback catch-all debe estar al final de `web.php`

### Errores de CSRF
- Verifica que el token esté en el `<head>` de `app.blade.php`
- Asegúrate de incluir el token en las peticiones POST

---

## 📦 Compilar para Producción

```bash
# Compilar assets de React optimizados
npm run build

# Los archivos compilados estarán en public/build/
```

---

## 📚 Siguiente Paso

1. ✅ Proyecto configurado
2. ✅ Rutas de React funcionando
3. ✅ Validaciones en backend
4. ⏭️ **Conectar componentes React con las APIs del backend**
5. ⏭️ Eliminar archivos `.blade.php` que ya no se usan

Revisa:
- [VALIDACIONES_MIGRADAS.md](VALIDACIONES_MIGRADAS.md) - Todas las validaciones
- [GUIA_INTEGRACION_REACT.md](GUIA_INTEGRACION_REACT.md) - Ejemplos de código React

---

## ✨ ¡Todo Listo!

Tu proyecto ahora es una SPA moderna con:
- ✅ React como frontend
- ✅ Laravel como backend API
- ✅ Validaciones en el servidor
- ✅ Rutas manejadas por React Router
- ✅ Hot Module Replacement (HMR) con Vite

🎉 **¡Disfruta desarrollando!**
