# Risaralda EcoTurismo

Plataforma web para promocion y gestion de destinos ecoturisticos en Risaralda, Colombia.
Stack principal: Laravel 12 + React 19 + Vite + Sanctum.

## Estado Actual de la Pagina (Marzo 2026)

### Modulos activos
- Autenticacion con token (login, registro, logout, recuperacion de contrasena).
- Exploracion de lugares y ecohoteles con paginas de detalle.
- Reservas de usuarios y gestion de reservas por empresa.
- Sistema de reseñas para lugares y ecohoteles.
- Favoritos de usuario.
- Perfil de usuario con actualizacion de foto.
- Panel admin para lugares, ecohoteles, usuarios, reservas y razones de rechazo.
- Panel empresa para gestion de lugares, horarios y reservas.
- Mapa interactivo.
- Contacto y mensajeria.
- Panel de accesibilidad y helper de traduccion.

### Rutas principales de frontend (React Router)
- / (home)
- /login
- /pagLogueados
- /lugares
- /lugares/:id
- /ecohoteles
- /ecohoteles/:id
- /mapa
- /contact, /contacto, /contact2, /contacto2
- /comments, /comentarios
- /comments2, /comentarios2
- /paraisosAcuaticos
- /lugaresMontanosos
- /parquesYMas
- /territoriosDelCafe
- /company/dashboard
- /admin
- /cookies
- /terminos-de-uso
- /politica-de-privacidad
- /CopyrightTotal

## Tecnologias

### Backend
- Laravel 12
- Sanctum
- PHP 8.2+
- SQLite (default, configurable)

### Frontend
- React 19
- React Router DOM
- Axios
- Leaflet / React-Leaflet
- Vite

## Puesta en marcha rapida

### Requisitos
- PHP 8.2+
- Composer
- Node.js 18+
- npm

### Instalacion
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Desarrollo
```bash
php artisan serve
npm run dev
```

Aplicacion: http://localhost:8000

## Documentacion recomendada
- DOCUMENTATION_INDEX.md
- API_DOCUMENTATION.md
- FLOW.md
- ARCHITECTURE.md
- DATABASE.md
- INSTRUCCIONES_USO.md

## Roles

### Usuario
- Navega lugares y ecohoteles.
- Crea reservas.
- Gestiona favoritos.
- Publica/edita/elimina reseñas.
- Gestiona su perfil.

### Empresa
- Gestiona sus lugares.
- Administra horarios por lugar.
- Acepta/rechaza/reabre reservas.
- Consulta estadisticas de reservas.

### Administrador
- CRUD de lugares y ecohoteles.
- CRUD de usuarios.
- Gestion de reservas global.
- CRUD de razones de rechazo.
- Consulta de mensajes/contactos.

## Notas importantes
- Frontend usa API relativa /api para funcionar en distintos dominios.
- Para actualizacion de perfil con imagen se usa POST /api/profile con FormData y _method=PUT.
- Existe compatibilidad de rutas web POST de autenticacion para formularios legacy.
