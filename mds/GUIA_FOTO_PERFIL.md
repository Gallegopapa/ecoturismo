# Guía de Carga de Foto de Perfil

## ¿Cómo usar?

### Paso 1: Ir a /perfil
Deberías ver un círculo con tu foto de perfil actual.

### Paso 2: Click en "Subir Foto"
Se abre el explorador de archivos. Selecciona una imagen JPG, PNG o similar.

### Paso 3: Esperá a que aparezca el preview
Debería aparecer:
- La nueva foto en el círculo (tomada de tu computadora)
- Un texto debajo que dice "Nueva foto seleccionada: {nombre del archivo}"

### Paso 4: Click en "Guardar Cambios"
El botón debería mostrar "Guardando..." mientras procesa.

### Paso 5: Verifica que pasó
- Debería aparecer un mensaje verde "Perfil actualizado exitosamente"
- Tu nueva foto debería quedar guardada en el círculo
- El header superior debería actualizar tu avatar también

---

## Si NO funciona - Pasos de Diagnóstico

### 1. Abre la Consola del Navegador
- Presiona **F12** o **Ctrl+Shift+I** (o Cmd+Option+I en Mac)
- Ve a la pestaña **"Console"**

### 2. Intenta cargar una foto nuevamente
Observa que salgan los logs (mensajes con emojis):
- `🟢 Componente PerfilPage montado` - El componente se cargó
- `🔄 handleChange ejecutado` - El input file se activó
- `✅ Archivo detectado` - El archivo se capturó
- `📝 formData actualizado` - Los datos se guardaron
- `✅ FileReader terminó` - El preview se generó

### 3. Haz click en "Guardar Cambios"
Debería ver:
- `=== INICIO handleSubmit ===` - Comenzó el guardado
- `📤 FormData + FILE:` - Se construyó el FormData
- `✅ Respuesta del servidor:` - Vino respuesta del backend

---

## Errores Comunes

### Error: "El archivo debe ser una imagen"
**Causa:** Seleccionaste un archivo que no es imagen (PDF, TXT, etc.)
**Solución:** Selecciona JPG, PNG, GIF o WebP

### Error: "La imagen no puede exceder 5MB"
**Causa:** La imagen es muy grande
**Solución:** Comprímela o usa una más pequeña

### Error: "El nombre de usuario solo puede contener letras, números y guiones bajos"
**Causa:** Tu usuario actual tiene caracteres inválidos legacy
**Solución:** Debería permitir solo subir foto sin validar nombre

### No aparece preview al seleccionar
**Causa:** FileReader no se ejecutó
**Solución:** Verifica Console (F12) para ver si aparece el log `✅ Archivo detectado`

### Foto se guarda pero no se muestra
**Causa:** URL de imagen incorrecta
**Solución:** Verifica que `/storage/profiles/` exista y tenga permisos

---

## Archivos Modificados

✅ `app/Http/Controllers/API/ProfileController.php` - Validación condicional de nombre
✅ `app/Models/Usuarios.php` - Accessor de foto devuelve ruta relativa
✅ `resources/js/react/perfil/page.jsx` - Lógica de carga
✅ `resources/js/react/services/api.js` - Construcción de FormData

---

## Flujo Técnico Resumido

```
Usuario selecciona foto
    ↓
handleChange() captura File
    ↓
formData.foto_perfil = File
    ↓
FileReader genera DataURL para preview
    ↓
Usuario hace click "Guardar"
    ↓
handleSubmit() construye objeto con {name, email, telefono, foto_perfil}
    ↓
profileService.update() convierte a FormData (porque hay File)
    ↓
api.post('/profile', formData)
    ↓
Interceptor remueve Content-Type para que navegador lo establezca
    ↓
Backend recibe FormData
    ↓
Valida foto (imagen, mimes, tamaño)
    ↓
Guarda en storage/app/public/profiles/
    ↓
Copia a public/storage/ (fallback)
    ↓
Devuelve URL: /storage/profiles/{timestamp}_{uniqid}.jpg
    ↓
Frontend recibe respuesta.user.foto_perfil
    ↓
updateUser() actualiza contexto
    ↓
setPreviewImage() actualiza preview
    ↓
Componente Header2 ve cambio en user.foto_perfil
    ↓
Avatar en header se actualiza
```

---

## ¿Todo sigue fallando?

1. **Abre Console (F12)** y copia TODOS los logs que ves
2. **Haz una captura de pantalla** del error
3. **Verifica que /perfil esté accesible** (no da 404)
4. **Revisa permisos** de `storage/app/public/profiles/` (debe tener 755)
5. **Verifica que el symlink exista**: `public/storage` → `../storage/app/public`
