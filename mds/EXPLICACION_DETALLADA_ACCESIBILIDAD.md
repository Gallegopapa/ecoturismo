# 📘 Explicación Detallada del Sistema de Accesibilidad e Idiomas

## 🎯 Arquitectura del Sistema

El sistema está dividido en **4 capas principales**:

1. **Capa de Contexto** (Estado Global)
2. **Capa de Presentación** (Componentes UI)
3. **Capa de Traducciones** (i18n)
4. **Capa de Estilos** (CSS)

---

## 1️⃣ CAPA DE CONTEXTO (Estado Global)

### 📁 `AccessibilityContext.jsx`

**¿Qué hace?**
- Gestiona el estado global de las configuraciones de accesibilidad
- Persiste las configuraciones en `localStorage`
- Aplica clases CSS al `<body>` según configuraciones activas

**Estados que maneja:**
```javascript
fontSize: 'normal' | 'large' | 'extra-large'  // Tamaño de texto
highContrast: boolean                           // Alto contraste ✓/✗
underlineLinks: boolean                         // Subrayar enlaces ✓/✗
grayscale: boolean                              // Escala de grises ✓/✗
```

**Funciones disponibles:**
```javascript
increaseFontSize()    // Aumenta el tamaño de texto (hasta extra-large)
decreaseFontSize()    // Disminuye el tamaño de texto (hasta normal)
toggleHighContrast()  // Activa/desactiva alto contraste
toggleUnderlineLinks()// Activa/desactiva subrayado de enlaces
toggleGrayscale()     // Activa/desactiva escala de grises
resetSettings()       // Restaura todo a valores por defecto
```

**¿Cómo funciona internamente?**

1. **Al montar el Provider:**
   - Lee `localStorage` buscando clave `accessibility-settings`
   - Si existe, restaura las configuraciones guardadas
   - Si no existe, usa valores por defecto

2. **Cuando cambia una configuración:**
   - Actualiza el estado con `useState`
   - Guarda automáticamente en `localStorage` (useEffect)
   - Aplica/remueve clases CSS del `<body>` (useEffect)

3. **Clases CSS aplicadas al body:**
   ```jsx
   body.font-large              // Texto al 112.5%
   body.font-extra-large        // Texto al 125%
   body.high-contrast           // Alto contraste activado
   body.underline-links         // Enlaces subrayados
   body.grayscale-mode          // Escala de grises
   ```

**Código clave explicado:**

```javascript
// Este useEffect escucha cambios en TODAS las configuraciones
useEffect(() => {
  const body = document.body;

  // Primero limpia TODAS las clases previas
  body.classList.remove('font-large', 'font-extra-large', 
                         'high-contrast', 'underline-links', 
                         'grayscale-mode');

  // Luego aplica solo las clases que están activas
  if (fontSize === 'large') {
    body.classList.add('font-large');
  } else if (fontSize === 'extra-large') {
    body.classList.add('font-extra-large');
  }

  if (highContrast) {
    body.classList.add('high-contrast');
  }

  // ... etc para otras configuraciones
}, [fontSize, highContrast, underlineLinks, grayscale]);
```

**¿Por qué usar Context en lugar de Redux?**
- No necesitamos librerías externas
- Context API es suficiente para este caso de uso
- Más simple y fácil de mantener

---

### 📁 `LanguageContext.jsx`

**¿Qué hace?**
- Gestiona el idioma actual de la aplicación
- Persiste el idioma en `localStorage`
- Actualiza el atributo `lang` del elemento `<html>`

**Estado que maneja:**
```javascript
language: 'es' | 'en'  // Idioma actual
```

**Funciones disponibles:**
```javascript
changeLanguage(newLanguage)  // Cambiar a un idioma específico
toggleLanguage()             // Alternar entre ES/EN
```

**¿Cómo funciona internamente?**

1. **Al montar el Provider:**
   - Lee `localStorage` buscando clave `app-language`
   - Si existe, restaura el idioma guardado
   - Si no existe, usa 'es' por defecto

2. **Cuando cambia el idioma:**
   - Actualiza el estado con `useState`
   - Guarda en `localStorage` (useEffect)
   - Actualiza `document.documentElement.lang` (useEffect)

**¿Por qué actualizar el atributo lang?**

```javascript
document.documentElement.lang = language;
```

Esto actualiza el atributo `lang` en el `<html>`:
```html
<html lang="es">  <!-- o lang="en" -->
```

**Beneficios:**
- Los lectores de pantalla saben en qué idioma leer
- Los motores de búsqueda entienden el idioma de la página
- Mejora la accesibilidad general

---

## 2️⃣ CAPA DE TRADUCCIONES (i18n)

### 📁 `translations.js`

**¿Qué es?**
Un objeto JavaScript que contiene todas las traducciones:

```javascript
export const translations = {
  es: {
    welcome: 'Bienvenido',
    save: 'Guardar',
    cancel: 'Cancelar',
    // ... más traducciones
  },
  en: {
    welcome: 'Welcome',
    save: 'Save',
    cancel: 'Cancel',
    // ... más traducciones
  }
};
```

**¿Por qué no usar i18next o react-intl?**
- Requisito del usuario: NO usar librerías externas
- Este enfoque es más simple y ligero
- Fácil de extender agregando más idiomas

**Función auxiliar:**

```javascript
export const getTranslation = (key, language) => {
  const translation = translations[language]?.[key];
  
  if (!translation) {
    console.warn(`Traducción no encontrada: ${key}`);
    return key; // Retorna la clave si no encuentra traducción
  }
  
  return translation;
};
```

**Manejo de errores:**
Si una clave no existe, retorna la misma clave:
```javascript
t('nonExistentKey') // → 'nonExistentKey'
```

Esto evita que la app crashee si falta una traducción.

---

### 📁 `useTranslation.js`

**¿Qué es?**
Un hook personalizado que combina el contexto de idioma con las traducciones.

```javascript
export const useTranslation = () => {
  const { language } = useLanguage();  // Obtiene idioma actual del contexto

  const t = (key) => {
    return getTranslation(key, language);  // Busca traducción
  };

  return { t, language };
};
```

**¿Por qué es útil?**
- Simplifica el uso en componentes
- Solo necesitas llamar `const { t } = useTranslation();`
- Automáticamente usa el idioma actual

**Uso en componentes:**

```jsx
import { useTranslation } from '../i18n/useTranslation';

const MyComponent = () => {
  const { t } = useTranslation();

  return (
    <div>
      <h1>{t('welcome')}</h1>
      <button>{t('save')}</button>
    </div>
  );
};
```

---

## 3️⃣ CAPA DE PRESENTACIÓN (UI)

### 📁 `AccessibilityPanel.jsx`

**Estructura del componente:**

```
AccessibilityPanel (componente principal)
├── Botón flotante (fijo, esquina inferior derecha)
├── Overlay (fondo oscuro cuando está abierto)
└── Panel lateral (slide-in desde la derecha)
    ├── Header (título + botón cerrar)
    └── Contenido
        ├── Controles de tamaño de texto
        ├── Toggle de alto contraste
        ├── Toggle de subrayar enlaces
        ├── Toggle de escala de grises
        ├── Selector de idioma
        └── Botón de reset
```

**Estados locales:**

```javascript
const [isOpen, setIsOpen] = useState(false);  // Panel abierto/cerrado
```

**Referencias (refs):**

```javascript
const buttonRef = useRef(null);          // Referencia al botón flotante
const panelRef = useRef(null);           // Referencia al panel
const firstFocusableRef = useRef(null);  // Primer elemento enfocable
```

**¿Para qué sirven las referencias?**

1. **buttonRef:** Para devolver el foco al botón al cerrar el panel
2. **panelRef:** Para hacer focus trap (mantener el foco dentro del panel)
3. **firstFocusableRef:** Para mover el foco al primer control al abrir

---

### **Accesibilidad con Teclado**

**1. Cerrar con Escape:**

```javascript
useEffect(() => {
  const handleEscape = (event) => {
    if (event.key === 'Escape' && isOpen) {
      closePanel();
    }
  };

  if (isOpen) {
    document.addEventListener('keydown', handleEscape);
  }

  return () => {
    document.removeEventListener('keydown', handleEscape);
  };
}, [isOpen]);
```

**¿Qué hace?**
- Cuando el panel está abierto, escucha la tecla Escape
- Si se presiona Escape, cierra el panel
- Limpia el listener cuando el panel se cierra

**2. Mover foco al abrir:**

```javascript
useEffect(() => {
  if (isOpen && firstFocusableRef.current) {
    firstFocusableRef.current.focus();
  }
}, [isOpen]);
```

**¿Qué hace?**
- Cuando el panel se abre, mueve el foco al primer botón
- Mejora la accesibilidad para navegación con teclado

**3. Focus Trap (atrapar el foco):**

```javascript
useEffect(() => {
  if (!isOpen || !panelRef.current) return;

  const handleTabKey = (event) => {
    if (event.key !== 'Tab') return;

    const focusableElements = panelRef.current.querySelectorAll(
      'button:not([disabled]), [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );

    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    // Si Shift + Tab en el primer elemento, ir al último
    if (event.shiftKey && document.activeElement === firstElement) {
      event.preventDefault();
      lastElement.focus();
    } 
    // Si Tab en el último elemento, ir al primero
    else if (!event.shiftKey && document.activeElement === lastElement) {
      event.preventDefault();
      firstElement.focus();
    }
  };

  document.addEventListener('keydown', handleTabKey);

  return () => {
    document.removeEventListener('keydown', handleTabKey);
  };
}, [isOpen]);
```

**¿Qué hace?**
- Mantiene el foco dentro del panel cuando está abierto
- Al presionar Tab en el último elemento, vuelve al primero
- Al presionar Shift+Tab en el primero, va al último
- Esto previene que el foco "escape" del panel

---

### **ARIA (Accessible Rich Internet Applications)**

**Atributos ARIA usados:**

1. **En el botón flotante:**
```jsx
<button
  aria-label={t('openAccessibilityPanel')}  // Descripción del botón
  aria-expanded={isOpen}                     // Indica si el panel está abierto
  aria-controls="accessibility-panel"        // ID del panel que controla
>
```

**¿Por qué?**
- `aria-label`: Los lectores de pantalla leen "Abrir panel de accesibilidad"
- `aria-expanded`: Indica el estado (true/false)
- `aria-controls`: Vincula el botón con el panel

2. **En el panel:**
```jsx
<aside
  id="accessibility-panel"
  role="dialog"                              // Define el rol como diálogo
  aria-modal="true"                          // Es un modal (bloquea contenido detrás)
  aria-labelledby="accessibility-panel-title" // Título que describe el panel
>
```

**¿Por qué?**
- `role="dialog"`: Los lectores de pantalla anuncian "diálogo abierto"
- `aria-modal="true"`: Indica que es modal (el contenido detrás está inactivo)
- `aria-labelledby`: Vincula con el título para descripción

3. **En botones toggle:**
```jsx
<button
  aria-pressed={highContrast}  // Indica si está presionado (activado)
  onClick={toggleHighContrast}
>
```

**¿Por qué?**
- `aria-pressed`: Los lectores de pantalla anuncian "botón presionado" o "botón no presionado"

---

## 4️⃣ CAPA DE ESTILOS (CSS)

### 📁 `AccessibilityPanel.css`

**Estilos del botón flotante:**

```css
.accessibility-floating-button {
  position: fixed;           /* Fijo en la ventana */
  bottom: 20px;              /* 20px desde abajo */
  right: 20px;               /* 20px desde la derecha */
  width: 56px;
  height: 56px;
  border-radius: 50%;        /* Circular */
  z-index: 9998;             /* Por encima de casi todo */
}
```

**¿Por qué z-index: 9998?**
- Debe estar por encima del contenido normal
- Pero por debajo del panel (z-index: 9999)

**Animación del panel:**

```css
.accessibility-panel {
  transform: translateX(100%);  /* Fuera de pantalla (derecha) */
  transition: transform 0.3s ease;
}

.accessibility-panel.open {
  transform: translateX(0);     /* En pantalla */
}
```

**¿Cómo funciona?**
1. Por defecto: panel está 100% a la derecha (invisible)
2. Al agregar clase `.open`: panel se mueve a posición 0 (visible)
3. `transition`: Anima suavemente en 0.3 segundos

---

### 📁 `accessibility.css` (Estilos Globales)

**Tamaños de texto:**

```css
body.font-large {
  font-size: 112.5%;  /* 18px si la base es 16px */
}

body.font-extra-large {
  font-size: 125%;    /* 20px si la base es 16px */
}
```

**¿Por qué usar porcentajes?**
- Respeta el tamaño de fuente base del navegador
- Escala proporcionalmente
- Mejor para accesibilidad

**Alto contraste:**

```css
body.high-contrast {
  background-color: #ffffff !important;
  color: #000000 !important;
}

body.high-contrast * {
  background-color: #ffffff !important;
  color: #000000 !important;
  border-color: #000000 !important;
}
```

**¿Por qué usar !important?**
- Forzar que se aplique sobre todos los estilos existentes
- Garantizar contraste real (blanco/negro puro)

**Enlaces en alto contraste:**

```css
body.high-contrast a {
  color: #0000ff !important;        /* Azul clásico */
  text-decoration: underline !important;
  font-weight: 600 !important;
}

body.high-contrast a:hover,
body.high-contrast a:focus {
  color: #cc0000 !important;        /* Rojo al hover */
  background-color: #ffff00 !important;  /* Fondo amarillo */
}
```

**¿Por qué estos colores?**
- Azul para enlaces: estándar web desde los 90s
- Amarillo al hover: máximo contraste
- Son colores seguros para daltonismo

**Escala de grises:**

```css
body.grayscale-mode {
  filter: grayscale(100%);
  -webkit-filter: grayscale(100%);
}

body.grayscale-mode .accessibility-floating-button,
body.grayscale-mode .accessibility-panel {
  filter: none;  /* Excluir el panel del filtro */
}
```

**¿Por qué excluir el panel?**
- Para que el usuario pueda ver el panel a color
- Y pueda desactivar el modo fácilmente

---

## 5️⃣ INTEGRACIÓN EN MAIN.JSX

### Antes:
```jsx
createRoot(document.getElementById("root")).render(
  <RouterProvider router={router} />
);
```

### Después:
```jsx
createRoot(document.getElementById("root")).render(
  <AccessibilityProvider>
    <LanguageProvider>
      <RouterProvider router={router} />
      <AccessibilityPanel />
    </LanguageProvider>
  </AccessibilityProvider>
);
```

**¿Por qué este orden?**

1. **AccessibilityProvider (exterior):**
   - Provee configuraciones de accesibilidad
   - Debe envolver todo

2. **LanguageProvider:**
   - Provee el idioma actual
   - Necesita acceso a AccessibilityContext

3. **RouterProvider:**
   - Maneja las rutas de la aplicación
   - Necesita acceso a ambos contextos

4. **AccessibilityPanel (dentro):**
   - Componente flotante que usa ambos contextos
   - Se renderiza en TODAS las páginas

**Ventaja de este enfoque:**
- El panel aparece en todas las páginas automáticamente
- Todos los componentes tienen acceso a los contextos
- No necesitas importar nada en cada página individual

---

## 🔄 Flujo de Datos Completo

### Ejemplo: Usuario aumenta el tamaño de texto

1. **Usuario hace clic en botón "A+"**
   ```jsx
   <button onClick={increaseFontSize}>
   ```

2. **Se ejecuta la función del contexto:**
   ```javascript
   const increaseFontSize = () => {
     if (fontSize === 'normal') {
       setFontSize('large');
     }
   };
   ```

3. **useState actualiza el estado:**
   ```javascript
   fontSize: 'normal' → 'large'
   ```

4. **useEffect detecta el cambio:**
   ```javascript
   useEffect(() => {
     // Guarda en localStorage
     localStorage.setItem('accessibility-settings', JSON.stringify({
       fontSize: 'large',
       // ... otras configuraciones
     }));
   }, [fontSize]);
   ```

5. **Otro useEffect actualiza el DOM:**
   ```javascript
   useEffect(() => {
     document.body.classList.add('font-large');
   }, [fontSize]);
   ```

6. **El CSS global se aplica:**
   ```css
   body.font-large {
     font-size: 112.5%;
   }
   ```

7. **Resultado: Todo el texto de la página crece al 112.5%**

---

## 📦 localStorage: Persistencia

### ¿Qué se guarda?

**Clave: `accessibility-settings`**
```json
{
  "fontSize": "large",
  "highContrast": false,
  "underlineLinks": true,
  "grayscale": false
}
```

**Clave: `app-language`**
```json
"es"
```

### ¿Cuándo se guarda?

1. **Al cambiar una configuración:** useEffect escucha cambios y guarda
2. **Se guarda automáticamente:** No necesitas llamar a ninguna función

### ¿Cuándo se carga?

1. **Al montar el Provider:** useEffect inicial lee localStorage
2. **Se carga una sola vez:** Al cargar la página

### ¿Qué pasa si localStorage está deshabilitado?

```javascript
try {
  localStorage.setItem('accessibility-settings', JSON.stringify(settings));
} catch (error) {
  console.error('Error al guardar configuraciones:', error);
}
```

- El sistema sigue funcionando
- Pero las configuraciones no persisten

---

## 🎨 Decisiones de Diseño

### ¿Por qué un botón flotante?

- **Siempre visible:** En todas las páginas
- **Fácil acceso:** No necesitas buscar en menús
- **No intrusivo:** Pequeño, en una esquina

### ¿Por qué un panel lateral?

- **Más espacio:** Para todos los controles
- **Mejor UX:** No ocupa espacio central
- **Animación suave:** Mejora la experiencia

### ¿Por qué usar clases en body?

- **Alcance global:** Afecta toda la página
- **Fácil de aplicar:** Solo agregar/quitar clases
- **Rendimiento:** No necesita re-renderizar React

---

## 🔒 Seguridad y Rendimiento

### ¿Es seguro usar localStorage?

**Sí, para este caso:**
- No guardamos datos sensibles
- Solo configuraciones de UI
- El usuario controla los datos

**No usar localStorage para:**
- Tokens de autenticación (usar httpOnly cookies)
- Contraseñas
- Información personal sensible

### ¿Afecta el rendimiento?

**No significativamente:**
- Los useEffect solo se ejecutan cuando cambian los valores
- Las operaciones del DOM son mínimas (agregar/quitar clases)
- localStorage es síncrono pero muy rápido

---

## ✅ Checklist de Accesibilidad

- ✅ Navegación con teclado
- ✅ Lectores de pantalla (ARIA)
- ✅ Focus visible
- ✅ Alto contraste
- ✅ Tamaños de texto ajustables
- ✅ Atributo lang actualizado
- ✅ Focus trap en panel
- ✅ Cerrar con Escape
- ✅ Sin dependencias externas

---

## 🚀 Próximos Pasos (Opcional)

Si quieres extender el sistema:

1. **Agregar más idiomas:**
   ```javascript
   const translations = {
     es: { /* ... */ },
     en: { /* ... */ },
     fr: { /* francés */ },
     pt: { /* portugués */ }
   };
   ```

2. **Agregar modo oscuro:**
   ```javascript
   const [darkMode, setDarkMode] = useState(false);
   // Aplicar clase body.dark-mode
   ```

3. **Agregar más configuraciones:**
   ```javascript
   const [animationsEnabled, setAnimationsEnabled] = useState(true);
   const [reduceMotion, setReduceMotion] = useState(false);
   ```

---

## 📝 Resumen Final

**Lo que se implementó:**
1. ✅ 2 Contextos (Accesibilidad + Idioma)
2. ✅ Sistema de traducciones (ES/EN)
3. ✅ Hook personalizado (useTranslation)
4. ✅ Componente de panel flotante
5. ✅ Estilos globales de accesibilidad
6. ✅ Persistencia en localStorage
7. ✅ Accesibilidad real con ARIA
8. ✅ Navegación con teclado
9. ✅ Sin librerías externas

**Lo que NO se rompió:**
- ❌ No se modificó ningún componente existente
- ❌ No se cambió el routing
- ❌ No se afectó la funcionalidad actual

**El sistema está listo para usar! 🎉**
