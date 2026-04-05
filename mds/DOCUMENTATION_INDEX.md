# Indice de Documentacion - Risaralda EcoTurismo

Indice central de documentacion alineado con el estado actual del proyecto.

## Documentos principales

1. README.md
Resumen general del proyecto, modulos activos, rutas principales y roles.

2. INSTRUCCIONES_USO.md
Guia de uso de la aplicacion para usuario, empresa y admin.

3. FLOW.md
Flujos funcionales reales de autenticacion, lugares, reservas, reseñas, favoritos y paneles.

4. API_DOCUMENTATION.md
Referencia actualizada de endpoints publicos y protegidos.

5. ARCHITECTURE.md
Arquitectura Laravel + React y organizacion por capas.

6. DATABASE.md
Modelo de datos, tablas, relaciones y referencias de mantenimiento.

## Guia rapida por perfil

### Nuevo desarrollador
1. Leer README.md.
2. Leer INSTRUCCIONES_USO.md.
3. Revisar FLOW.md.
4. Consultar API_DOCUMENTATION.md.

### Frontend
- main routing: resources/js/react/main.jsx
- servicios API: resources/js/react/services/api.js
- estados globales: resources/js/react/context y resources/js/react/contexts

### Backend
- rutas web: routes/web.php
- rutas API: routes/api.php
- controladores: app/Http/Controllers

## Cobertura funcional documentada
- Home publico y home logueado.
- Lugares, detalle y categorias especiales.
- Ecohoteles y detalle.
- Mapa interactivo.
- Login, registro, logout, recuperacion de contrasena.
- Reservas usuario y gestion por empresa.
- Panel admin.
- Perfil de usuario con foto.
- Contacto y mensajes.
- Accesibilidad y traduccion.

## Ultima actualizacion
Marzo 2026
