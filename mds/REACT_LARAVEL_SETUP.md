# Guía de Configuración React + Laravel

## ✅ Lo que ya está configurado

### 1. Servicio de API (`resources/js/react/services/api.js`)
- ✅ Axios configurado con base URL
- ✅ Interceptor para agregar tokens automáticamente
- ✅ Manejo de errores 401 (redirige al login)
- ✅ Todos los servicios listos (auth, places, reservations, reviews, etc.)

### 2. Contexto de Autenticación (`resources/js/react/context/AuthContext.jsx`)
- ✅ Manejo de estado de usuario
- ✅ Funciones de login, register, logout
- ✅ Verificación automática de token al cargar
- ✅ Persistencia en localStorage

### 3. Componentes
- ✅ Login/Registro actualizado
- ✅ Rutas protegidas
- ✅ Header con logout

## 🚀 Cómo usar

### 1. Variables de entorno (opcional)
Crea un archivo `.env` en la raíz del proyecto React o agrega a tu `.env` de Laravel:
```env
VITE_API_URL=http://localhost:8000/api
```

### 2. Ejemplo de uso en componentes

```jsx
import { useAuth } from '../context/AuthContext';
import { placesService } from '../services/api';

function MyComponent() {
  const { user, isAuthenticated, logout } = useAuth();
  const [places, setPlaces] = useState([]);

  useEffect(() => {
    if (isAuthenticated) {
      // Cargar lugares
      placesService.getAll()
        .then(data => setPlaces(data))
        .catch(error => console.error(error));
    }
  }, [isAuthenticated]);

  return (
    <div>
      {isAuthenticated ? (
        <p>Hola, {user.name}</p>
      ) : (
        <p>No estás autenticado</p>
      )}
    </div>
  );
}
```

### 3. Rutas protegidas

```jsx
import { ProtectedRoute } from './components/ProtectedRoute';

// Ruta normal protegida
<ProtectedRoute>
  <MyComponent />
</ProtectedRoute>

// Ruta que requiere admin
<ProtectedRoute requireAdmin={true}>
  <AdminComponent />
</ProtectedRoute>
```

## 🔧 Configuración de CORS

Asegúrate de que en `config/sanctum.php` estén configurados los dominios:

```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,localhost:5173,127.0.0.1,127.0.0.1:8000,::1',
    Sanctum::currentApplicationUrlWithPort(),
))),
```

## 📝 Flujo de autenticación

1. Usuario hace login → `authService.login()`
2. Se guarda token en localStorage
3. Token se agrega automáticamente a todas las peticiones
4. Si token expira (401) → se limpia y redirige a login
5. Al recargar página → se verifica el token automáticamente

## 🐛 Solución de problemas

### Error: CORS
- Verifica que `SANCTUM_STATEFUL_DOMAINS` incluya tu puerto de React
- Asegúrate de que `withCredentials: true` esté en la configuración de axios

### Error: Token no se envía
- Verifica que el token esté en localStorage: `localStorage.getItem('token')`
- Revisa la consola del navegador para ver los headers de las peticiones

### Error: 401 Unauthorized
- El token puede haber expirado
- Intenta hacer login de nuevo
- Verifica que el token se esté guardando correctamente

## 📚 Servicios disponibles

- `authService` - login, register, logout, verifyToken, getCurrentUser
- `placesService` - getAll, getById, create, update, delete
- `reservationsService` - getMyReservations, create, update, delete
- `reviewsService` - getByPlace, create, delete
- `favoritesService` - getAll, add, remove
- `categoriesService` - getAll, getById
- `profileService` - get, update, changePassword
- `messagesService` - send

Todos los servicios están en `resources/js/react/services/api.js`

