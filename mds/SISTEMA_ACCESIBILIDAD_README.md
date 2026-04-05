# Sistema de Accesibilidad e Internacionalización (i18n)

## 📋 Descripción General

Este sistema implementa funcionalidades completas de accesibilidad y cambio de idioma (Español/Inglés) para la aplicación React sin usar librerías externas.

---

## 📁 Estructura de Archivos

```
resources/js/react/
├── contexts/+{}
│   ├── AccessibilityContext.jsx   # Contexto de configuraciones de accesibilidad
│   └── LanguageContext.jsx        # Contexto de idioma
├── i18n/
│   ├── translations.js            # Archivo de traducciones (ES/EN)
│   └── useTranslation.js          # Hook personalizado para traducciones
├── components/
│   └── AccessibilityPanel/
│       ├── AccessibilityPanel.jsx # Componente principal del panel
│       └── AccessibilityPanel.css # Estilos del panel
├── styles/
│   └── accessibility.css          # Estilos globales de accesibilidad
└── main.jsx                       # Punto de entrada (actualizado)
```

---

## 🔧 Funcionalidades Implementadas

### ✅ Panel de Accesibilidad

- **Botón flotante**: Fijo en esquina inferior derecha
- **Panel lateral**: Se desliza desde la derecha al hacer clic
- **Opciones disponibles**:
  - 🔍 Aumentar tamaño de texto (3 niveles: Normal, Grande, Extra grande)
  - 🔍 Disminuir tamaño de texto
  - 🎨 Alto contraste (activar/desactivar)
  - 🔗 Subrayar enlaces
  - ⚫ Escala de grises
  - 🔄 Restablecer configuración
  - 🌍 Selector de idioma (Español/Inglés)

### ✅ Persistencia

- Todas las configuraciones se guardan en `localStorage`
- Se restauran automáticamente al recargar la página
- El idioma seleccionado también persiste

### ✅ Accesibilidad Real

- **ARIA labels** en botones y controles
- **role="dialog"** en el panel
- **aria-expanded** en botón flotante
- **Cierre con tecla Escape**
- **Focus trap** dentro del panel cuando está abierto
- Actualización automática del atributo `<html lang="">` según idioma

### ✅ Aplicación de Estilos

- Los estilos se aplican agregando/quitando clases al `<body>`:
  - `font-large`: Texto al 112.5%
  - `font-extra-large`: Texto al 125%
  - `high-contrast`: Alto contraste
  - `underline-links`: Subrayado de enlaces
  - `grayscale-mode`: Escala de grises

---

## 🚀 Cómo Usar las Traducciones en Tus Componentes

### Ejemplo 1: Usar el hook `useTranslation`

```jsx
import { useTranslation } from '../i18n/useTranslation';

const MyComponent = () => {
  const { t, language } = useTranslation();

  return (
    <div>
      <h1>{t('welcome')}</h1>
      <p>Idioma actual: {language}</p>
      <button>{t('save')}</button>
      <button>{t('cancel')}</button>
    </div>
  );
};
```

### Ejemplo 2: Cambiar idioma programáticamente

```jsx
import { useLanguage } from '../contexts/LanguageContext';
import { useTranslation } from '../i18n/useTranslation';

const LanguageSwitcher = () => {
  const { language, changeLanguage, toggleLanguage } = useLanguage();
  const { t } = useTranslation();

  return (
    <div>
      <p>{t('selectLanguage')}: {language}</p>
      
      {/* Cambiar a idioma específico */}
      <button onClick={() => changeLanguage('es')}>
        {t('spanish')}
      </button>
      <button onClick={() => changeLanguage('en')}>
        {t('english')}
      </button>

      {/* Alternar entre idiomas */}
      <button onClick={toggleLanguage}>
        🌍 Cambiar idioma
      </button>
    </div>
  );
};
```

### Ejemplo 3: Controlar accesibilidad desde otro componente

```jsx
import { useAccessibility } from '../contexts/AccessibilityContext';

const MyAccessibleComponent = () => {
  const { 
    fontSize, 
    highContrast, 
    increaseFontSize,
    toggleHighContrast 
  } = useAccessibility();

  return (
    <div>
      <p>Tamaño de fuente actual: {fontSize}</p>
      <p>Alto contraste: {highContrast ? 'Activo' : 'Inactivo'}</p>
      
      <button onClick={increaseFontSize}>
        Aumentar texto
      </button>
      <button onClick={toggleHighContrast}>
        Alternar contraste
      </button>
    </div>
  );
};
```

---

## 📝 Agregar Nuevas Traducciones

Edita el archivo `resources/js/react/i18n/translations.js`:

```javascript
export const translations = {
  es: {
    // Agregar aquí tus nuevas traducciones en español
    myNewKey: 'Mi nuevo texto',
    anotherKey: 'Otro texto',
    welcomeMessage: 'Bienvenido a nuestra aplicación',
  },
  en: {
    // Agregar las mismas claves en inglés
    myNewKey: 'My new text',
    anotherKey: 'Another text',
    welcomeMessage: 'Welcome to our application',
  }
};
```

Luego úsalas en cualquier componente:

```jsx
const { t } = useTranslation();
<h1>{t('myNewKey')}</h1>
<p>{t('welcomeMessage')}</p>
```

---

## 🎨 Personalizar Estilos de Accesibilidad

### Modificar colores del panel

Edita `resources/js/react/components/AccessibilityPanel/AccessibilityPanel.css`:

```css
.accessibility-floating-button {
  background: linear-gradient(135deg, #tu-color-1 0%, #tu-color-2 100%);
}

.panel-header {
  background: linear-gradient(135deg, #tu-color-1 0%, #tu-color-2 100%);
}
```

### Modificar comportamiento de alto contraste

Edita `resources/js/react/styles/accessibility.css`:

```css
body.high-contrast {
  background-color: #tu-fondo !important;
  color: #tu-texto !important;
}
```

---

## 🔍 Testing / Verificación

### 1. Verificar que el panel aparece

- Abre tu aplicación
- Deberías ver un botón verde flotante en la esquina inferior derecha
- Haz clic → El panel se abre desde la derecha

### 2. Probar tamaño de texto

- Haz clic en "Aumentar tamaño de texto" → Todo el texto de la página crece
- Haz clic en "Disminuir tamaño de texto" → El texto vuelve a tamaño normal

### 3. Probar alto contraste

- Activa "Alto contraste" → La página cambia a blanco/negro
- Desactiva → Vuelve a colores normales

### 4. Probar cambio de idioma

- Cambia de Español a Inglés → Todos los textos del panel cambian
- Recarga la página → El idioma seleccionado persiste

### 5. Probar persistencia

- Configura varias opciones (texto grande, contraste, etc.)
- Recarga la página
- Verifica que las configuraciones se mantienen

### 6. Probar accesibilidad con teclado

- Con el panel cerrado, presiona Tab hasta llegar al botón flotante
- Presiona Enter → Panel se abre
- Presiona Escape → Panel se cierra
- El foco vuelve al botón flotante

---

## 📱 Responsive

El sistema es completamente responsive:

- **Desktop**: Panel de 360px de ancho
- **Móvil**: Panel ocupa 100% del ancho de pantalla
- Botón flotante se ajusta en tamaño según pantalla

---

## 🌐 Soporte de Navegadores

Funciona en:
- ✅ Chrome/Edge (últimas versiones)
- ✅ Firefox (últimas versiones)
- ✅ Safari (últimas versiones)
- ✅ Navegadores móviles (iOS/Android)

---

## 🎯 Notas Importantes

1. **No se usan librerías externas**: Todo está implementado con React puro
2. **No se hacen llamadas a APIs**: El sistema funciona 100% en el cliente
3. **localStorage**: Asegúrate de que tu aplicación tenga acceso a localStorage
4. **Integración limpia**: No modifica componentes existentes
5. **Fácil de extender**: Agrega más idiomas o configuraciones editando los archivos correspondientes

---

## 🐛 Troubleshooting

### El panel no aparece

- Verifica que `main.jsx` incluye los providers y el componente
- Verifica que los estilos CSS se importan correctamente
- Abre la consola del navegador y busca errores

### Las traducciones no funcionan

- Verifica que la clave existe en `translations.js` para ambos idiomas
- Verifica que estás usando el hook `useTranslation()` dentro de un componente envuelto por `LanguageProvider`

### Los estilos no se aplican

- Verifica que el archivo `accessibility.css` se importa en `main.jsx`
- Abre las DevTools y verifica que las clases se agregan al `<body>`
- Limpia la caché del navegador

### El idioma no persiste

- Verifica que localStorage está habilitado en el navegador
- Abre DevTools → Application → Local Storage → Verifica que existe la clave `app-language`

---

## 📦 Archivos Críticos

**NO ELIMINAR:**
- `contexts/AccessibilityContext.jsx`
- `contexts/LanguageContext.jsx`
- `i18n/translations.js`
- `i18n/useTranslation.js`
- `styles/accessibility.css`
- `components/AccessibilityPanel/`

---

## ✅ Sistema Completamente Integrado

✔️ Contextos creados  
✔️ Traducciones configuradas  
✔️ Panel de accesibilidad funcional  
✔️ Estilos globales aplicados  
✔️ Persistencia con localStorage  
✔️ Accesibilidad con ARIA y teclado  
✔️ Sin dependencias externas  
✔️ Documentación completa  

**¡El sistema está listo para usar! 🎉**
