# Guía de Debugging para Login

## Problema Común: "Credenciales incorrectas"

Si el login no funciona, sigue estos pasos:

### 1. Verificar que el usuario existe en la base de datos

```bash
php artisan tinker
```

Luego ejecuta:
```php
$user = App\Models\Usuarios::first();
echo "Nombre: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";
echo "Password hash: " . $user->password . "\n";
```

### 2. Verificar los logs de Laravel

Revisa `storage/logs/laravel.log` para ver los intentos de login:
- Busca "Intento de login fallido" para ver qué está fallando
- Busca "Login exitoso" para confirmar logins exitosos

### 3. Verificar la petición desde React

Abre la consola del navegador (F12) y revisa:
- La petición a `/api/login`
- Los datos que se están enviando
- La respuesta del servidor

### 4. Probar directamente con Postman/Insomnia

```bash
POST http://localhost:8000/api/login
Content-Type: application/json

{
  "name": "tu_usuario",
  "password": "tu_contraseña"
}
```

### 5. Verificar que la contraseña esté hasheada

Si creaste un usuario manualmente, asegúrate de que la contraseña esté hasheada:

```php
use Illuminate\Support\Facades\Hash;
$password = Hash::make('tu_contraseña');
```

### 6. Verificar la búsqueda de usuario

El login busca por:
- `name` (si se envía `name`)
- `email` (si se envía `email`)

Asegúrate de que el campo que estás usando exista en la base de datos.

## Errores Comunes

1. **Usuario no encontrado**: Verifica que el nombre de usuario o email sea exacto (case-sensitive)
2. **Contraseña incorrecta**: Verifica que la contraseña esté correctamente hasheada en la BD
3. **Error de validación**: Verifica que estés enviando `name` o `email` y `password`

