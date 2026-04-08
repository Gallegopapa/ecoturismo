# 🎨 Guía: Personalizar Imágenes en Login y Registro

## Descripción del Nuevo Diseño

Se ha mejorado el login y registro con un **diseño de dos columnas**:
- **Izquierda**: Formulario de login/registro (mantiene la misma funcionalidad)
- **Derecha**: Espacio para imagen (gradient azul por defecto)

## Cómo Agregar tus Imágenes

### Opción 1: Reemplazar la Imagen en el Lado Derecho

#### Para el Login:
1. Abre: `resources/views/login.blade.php`
2. Busca la línea con:
   ```html
   <img src="{{ asset('imagenes/heroImage.jpg') }}" alt="Risaralda EcoTurismo" style="display: none;">
   ```
3. Reemplaza `imagenes/heroImage.jpg` con la ruta de tu imagen
4. Opcionalmente, cambia `style="display: none;"` a `style="display: block;"` para mostrar la imagen

#### Para el Registro:
1. Abre: `resources/views/registro.blade.php`
2. Realiza los mismos cambios

### Opción 2: Modificar el Gradient del Fondo

Si prefieres mantener el gradient azul/morado, puedes editarlo en:

Archivo: `public/css/login.css`

Busca la sección `.image-section` y modifica:
```css
.image-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    /* Cambiar a tus colores preferidos */
}
```

### Opción 3: Mezclar Imagen + Gradient

Para una imagen con overlay gradient:
1. En `login.blade.php` o `registro.blade.php`, quita `style="display: none;"` de la imagen
2. En `public/css/login.css`, modifica:
   ```css
   .image-section::before {
       content: '';
       position: absolute;
       /* ... resto del código */
   }
   ```

## Estructura de Archivos

```
public/
├── css/
│   └── login.css          ← Estilos del login y registro
├── js/
│   └── login.js           ← Funcionalidad (mostrar/ocultar contraseña)
└── imagenes/
    └── (tus imágenes aquí)

resources/views/
├── login.blade.php        ← Formulario de login
└── registro.blade.php     ← Formulario de registro
```

## Características Nuevas

✅ Diseño moderno de dos columnas
✅ Responsive (en móvil solo aparece el formulario)
✅ Gradients profesionales
✅ Animaciones suaves
✅ Manejo del ojo para mostrar/ocultar contraseña
✅ Validación de contraseñas en registro

## Responsive Design

- **Desktop (>1024px)**: Dos columnas (formulario + imagen)
- **Tablet (768px - 1024px)**: Solo formulario
- **Mobile (<768px)**: Solo formulario centrado

## Colores Utilizados

- 🟢 Verde primario: `#2ecc71`
- 🟢 Verde oscuro: `#27ae60`
- ⚫ Gris oscuro: `#2c3e50`
- ⚪ Blanco: `#ffffff`
- 🔵 Azul gradient: `#667eea to #764ba2`

## Notas

- El formulario **NO ha sido modificado**, solo mejorada su presentación visual
- La funcionalidad de autenticación se mantiene igual
- Puedes usar cualquier imagen (JPG, PNG, WebP, etc.)
- Se recomienda usar imágenes entre 400x600px para mejor rendimiento
