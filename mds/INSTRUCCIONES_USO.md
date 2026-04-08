# Instrucciones de Uso - Estado Real de la Aplicacion

Guia de uso alineada con las pantallas y flujos disponibles actualmente.

## 1. Navegacion principal

### Publico
- Inicio: /
- Lugares: /lugares
- Ecohoteles: /ecohoteles
- Mapa: /mapa
- Contacto: /contacto
- Comentarios: /comentarios y /comentarios2
- Legales: /cookies, /terminos-de-uso, /politica-de-privacidad

### Autenticacion
- Login: /login
- Al iniciar sesion, el usuario se redirige a /pagLogueados.

## 2. Flujo de usuario

### Explorar lugares
1. Entrar a /lugares.
2. Abrir un detalle en /lugares/:id.
3. Ver descripcion, reseñas y opciones de reserva.

### Crear reserva
1. Iniciar sesion.
2. Abrir detalle de lugar.
3. Completar formulario de reserva.
4. Confirmar envio.

### Gestionar favoritos
1. Iniciar sesion.
2. Marcar o desmarcar desde cards o detalle.
3. Consultar favoritos desde su seccion de usuario (si aplica en la vista activa).

### Gestionar perfil
1. Ir a la pantalla de perfil/configuracion.
2. Actualizar datos.
3. Si sube foto, la app envia FormData al endpoint de perfil.

## 3. Flujo de empresa

Ruta principal: /company/dashboard

Funciones disponibles:
- Ver lugares asociados a la empresa.
- Editar informacion de lugares.
- Gestionar horarios por lugar.
- Revisar reservas.
- Aceptar, rechazar o reabrir reservas.

## 4. Flujo de administrador

Ruta principal: /admin

Funciones disponibles:
- Lugares: crear, editar, eliminar.
- Ecohoteles: crear, editar, eliminar.
- Usuarios: crear, editar, eliminar.
- Reservas: consulta global.
- Razones de rechazo: CRUD.

## 5. Accesibilidad e idioma

La aplicacion incluye:
- Panel de accesibilidad global.
- Helper de traduccion.

Ambos componentes se montan globalmente en el arbol principal de React.

## 6. Endpoints usados por la pagina

La mayoria de acciones del frontend consumen rutas bajo /api.
Ver detalle completo en API_DOCUMENTATION.md.

## 7. Solucion de problemas rapida

- Error 401: validar token en localStorage y sesion vigente.
- Si expira token, la app limpia sesion y redirige a /login.
- Si falla carga de imagen de perfil, confirmar uso de /api/profile/photo/{filename}.

## Ultima actualizacion
Marzo 2026
