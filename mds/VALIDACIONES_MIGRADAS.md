# Validaciones Migradas de Blade a Controllers

## ✅ Resumen

Todas las validaciones de los archivos `.blade.php` han sido migradas exitosamente a los controllers correspondientes. Los archivos blade ahora pueden ser eliminados y reemplazados por componentes JSX/React.

---

## 📋 Validaciones Implementadas por Controller

### 1. **LoginController** (`app/Http/Controllers/Auth/LoginController.php`)

**Validaciones de Login:**
```php
'name' => ['required', 'string'],
'password' => ['required', 'string'],
```

**Mensajes personalizados:**
- `name.required`: "El nombre de usuario o email es requerido."
- `password.required`: "La contraseña es requerida."
- `credentials`: "Las credenciales proporcionadas son incorrectas."

**Lógica adicional:**
- Validación de email vs username
- Verificación de contraseña con Hash::check
- Protección contra ataques de fuerza bruta

---

### 2. **RegisterController** (`app/Http/Controllers/Auth/RegisterController.php`)

**Validaciones de Registro:**
```php
'name' => 'required|string|max:255|unique:usuarios,name',
'email' => 'required|email|max:255|unique:usuarios,email',
'password' => 'required|string|min:6|confirmed',
```

**Mensajes personalizados:**
- `name.required`: "El nombre de usuario es obligatorio."
- `name.unique`: "Este nombre de usuario ya está en uso. Por favor elige otro."
- `email.required`: "El correo electrónico es obligatorio."
- `email.email`: "Debes proporcionar un correo electrónico válido."
- `email.unique`: "Este correo electrónico ya está registrado. Intenta iniciar sesión."
- `password.required`: "La contraseña es obligatoria."
- `password.min`: "La contraseña debe tener al menos 6 caracteres."
- `password.confirmed`: "Las contraseñas no coinciden. Por favor verifica."

**Reglas aplicadas:**
- Nombre único en la base de datos
- Email válido y único
- Contraseña mínimo 6 caracteres
- Confirmación de contraseña (password_confirmation)

---

### 3. **ProfileController** (`app/Http/Controllers/ProfileController.php`) - **NUEVO**

**Validaciones de actualización de perfil:**
```php
'name' => ['required', 'string', 'max:255', 'unique:usuarios,name,' . $user->id],
'email' => ['required', 'email', 'max:255', 'unique:usuarios,email,' . $user->id],
```

**Validaciones de cambio de contraseña:**
```php
'current_password' => ['required', 'string'],
'new_password' => ['required', 'string', 'min:6', 'confirmed'],
```

**Mensajes personalizados:**
- `name.required`: "El nombre de usuario es requerido."
- `name.unique`: "Este nombre de usuario ya está en uso."
- `email.required`: "El correo electrónico es requerido."
- `email.email`: "El correo electrónico debe ser válido."
- `email.unique`: "Este correo electrónico ya está en uso."
- `current_password.required`: "La contraseña actual es requerida."
- `new_password.required`: "La nueva contraseña es requerida."
- `new_password.min`: "La nueva contraseña debe tener al menos 6 caracteres."
- `new_password.confirmed`: "Las contraseñas no coinciden."

**Lógica adicional:**
- Verificación de contraseña actual
- Validación que la nueva contraseña sea diferente

---

### 4. **ReservationController** (`app/Http/Controllers/ReservationController.php`)

**Validaciones de Reserva:**
```php
'place_id' => 'required|exists:places,id',
'fecha_visita' => 'required|date|after_or_equal:today',
'hora_visita' => 'nullable|date_format:H:i',
'personas' => 'required|integer|min:1|max:50',
'telefono_contacto' => 'nullable|string|max:20',
'comentarios' => 'nullable|string',
'precio_total' => 'nullable|numeric|min:0',
```

**Reglas aplicadas:**
- Fecha de visita no puede ser en el pasado
- Mínimo 1 persona, máximo 50
- Place_id debe existir en la BD

---

### 5. **MessageController** (`app/Http/Controllers/MessageController.php`) - **NUEVO**

**Validaciones de Mensajes/Comentarios:**
```php
'message' => ['required', 'string', 'max:1000'],
```

**Mensajes personalizados:**
- `message.required`: "El mensaje no puede estar vacío."
- `message.max`: "El mensaje no puede exceder los 1000 caracteres."

---

### 6. **API Controllers** (`app/Http/Controllers/API/`)

#### **AuthController**
```php
// Registro
'name' => 'required|string|unique:usuarios,name',
'email' => 'nullable|email|unique:usuarios,email',
'password' => 'required|string|min:6|confirmed',

// Login
'name' => 'required|string',
'password' => 'required|string',
```

#### **PlaceController**
```php
'name' => 'required|string|max:255',
'description' => 'nullable|string',
'location' => 'nullable|string|max:255',
'image' => 'nullable|string|max:500',
```

#### **ReservationController**
```php
'place_id' => 'required|exists:places,id',
'fecha' => 'required|date|after_or_equal:today',
'personas' => 'required|integer|min:1|max:50',
'estado' => 'sometimes|string|in:pendiente,confirmada,cancelada',
```

#### **ReviewController**
```php
'place_id' => 'required|exists:places,id',
'rating' => 'required|integer|min:1|max:5',
'comment' => 'nullable|string|max:1000',
```

**Lógica adicional:**
- Validación de usuario único por lugar (solo 1 review por lugar)

#### **FavoriteController**
```php
'place_id' => 'required|exists:places,id',
```

**Lógica adicional:**
- Validación de favorito único (no duplicados)

---

## 🗑️ Archivos Blade que Ahora Pueden Eliminarse

Los siguientes archivos `.blade.php` ya no necesitan validaciones HTML/Blade y pueden ser reemplazados por componentes React/JSX:

### Archivos con Validaciones Migradas:
1. ✅ `resources/views/login.blade.php` → Usar `resources/js/react/login/page.jsx`
2. ✅ `resources/views/registro.blade.php` → Crear componente React de registro
3. ✅ `resources/views/configuracion.blade.php` → Crear componente React de perfil
4. ✅ `resources/views/comentarios.blade.php` → Crear componente React de comentarios

### Validaciones HTML Removidas:
- ❌ `required` en inputs (ahora en backend)
- ❌ `@error()` directives (ahora manejados por API)
- ❌ `old()` helpers (ahora manejados por React state)
- ❌ `{{ $errors->any() }}` (ahora manejados por API responses)

---

## 🔄 Nuevas Rutas Creadas

```php
// Rutas de perfil/configuración
Route::middleware('auth')->group(function () {
    Route::get('/configuracion', [ProfileController::class, 'show'])->name('configuracion');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

// Envío de mensajes/comentarios
Route::post('/mensajes', [MessageController::class, 'store'])->name('mensajes');
```

---

## 📝 Uso desde React/JSX

### Ejemplo de Login:
```javascript
const handleLogin = async (formData) => {
  try {
    const response = await fetch('/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify(formData)
    });
    
    if (!response.ok) {
      const errors = await response.json();
      // Manejar errores de validación
      setErrors(errors);
    }
  } catch (error) {
    console.error(error);
  }
};
```

### Ejemplo de Cambio de Contraseña:
```javascript
const handlePasswordChange = async (data) => {
  try {
    const response = await fetch('/perfil/password', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        current_password: data.currentPassword,
        new_password: data.newPassword,
        new_password_confirmation: data.confirmPassword
      })
    });
    
    const result = await response.json();
    if (response.ok) {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
  }
};
```

---

## ✨ Beneficios de la Migración

1. **Separación de Responsabilidades**: La lógica de validación está en el backend
2. **Seguridad Mejorada**: Todas las validaciones se ejecutan en el servidor
3. **API Ready**: Los endpoints pueden ser usados tanto por React como por apps móviles
4. **Mensajes Consistentes**: Todos los errores tienen mensajes personalizados
5. **Código Limpio**: Sin mezcla de PHP y HTML en las vistas

---

## 🚀 Próximos Pasos

1. ✅ Todas las validaciones están en los controllers
2. ⏭️ Crear componentes React para cada funcionalidad
3. ⏭️ Conectar los componentes React con los endpoints del backend
4. ⏭️ Eliminar los archivos `.blade.php` cuando los componentes React estén listos
5. ⏭️ Actualizar las rutas para que devuelvan JSON en lugar de vistas

---

## 🔐 Seguridad

Todas las validaciones incluyen:
- ✅ Validación en el servidor (nunca confiar en validación del cliente)
- ✅ Protección CSRF con `@csrf` token
- ✅ Sanitización de datos con Laravel Validator
- ✅ Hash de contraseñas con bcrypt
- ✅ Autorización de usuario (verificación de pertenencia de recursos)
- ✅ Validación de unicidad en BD
- ✅ Validación de integridad referencial (exists)

---

## 📞 Contacto y Soporte

Si necesitas agregar más validaciones o modificar las existentes, todos los controllers están en:
- `app/Http/Controllers/Auth/` - Autenticación
- `app/Http/Controllers/` - Funcionalidad principal
- `app/Http/Controllers/API/` - Endpoints de API

Cada controller tiene comentarios docblock explicando su funcionalidad.
