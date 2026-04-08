# 🧪 Guía de Prueba - Sistema de Accesibilidad e Idiomas

## ✅ Build Exitoso

```bash
✓ built in 9.25s
✓ 217 modules transformed
✓ Sin errores de compilación
```

---

## 🚀 Cómo Probar el Sistema

### 1️⃣ Iniciar el servidor

```bash
php artisan serve
```

Abre tu navegador en: `http://localhost:8000`

---

### 2️⃣ Verificar que el Botón Aparece

**¿Qué deberías ver?**
- ✅ Un botón **verde circular** en la esquina **inferior derecha**
- ✅ Con un ícono de persona (accesibilidad)
- ✅ Al pasar el mouse, el botón crece ligeramente

**Si no aparece:**
1. Abre la consola del navegador (F12)
2. Busca errores en rojo
3. Verifica que `npm run build` se ejecutó correctamente

---

### 3️⃣ Abrir el Panel

**Acciones:**
1. Haz clic en el botón verde flotante
2. Deberías ver un panel deslizarse desde la derecha
3. El fondo se oscurece (overlay)

**Alternativa con teclado:**
1. Presiona `Tab` repetidamente hasta llegar al botón
2. Presiona `Enter`
3. El panel se abre

---

### 4️⃣ Probar: Aumentar Tamaño de Texto

**Pasos:**
1. En el panel, haz clic en **"Aumentar tamaño de texto" (A+)**
2. Todo el texto de la página debería **crecer**
3. El indicador cambia a: **"Grande"**

**Probar de nuevo:**
1. Haz clic en **A+** otra vez
2. El texto crece aún más
3. El indicador cambia a: **"Extra grande"**

**Reducir:**
1. Haz clic en **"Disminuir tamaño de texto" (A-)**
2. El texto vuelve a tamaño normal
3. El indicador cambia a: **"Normal"**

**✅ Funciona si:** El texto de TODA la página crece/decrece

---

### 5️⃣ Probar: Alto Contraste

**Pasos:**
1. En el panel, haz clic en **"Alto contraste"**
2. La página completa debería cambiar a **blanco y negro**
3. Los colores desaparecen
4. El estado cambia a: **"Activado"** (badge verde)

**Desactivar:**
1. Haz clic de nuevo en **"Alto contraste"**
2. Los colores vuelven
3. El estado cambia a: **"Desactivado"** (badge gris)

**✅ Funciona si:** Toda la página se vuelve blanco/negro

---

### 6️⃣ Probar: Subrayar Enlaces

**Pasos:**
1. En el panel, haz clic en **"Subrayar enlaces"**
2. TODOS los enlaces de la página deberían tener **subrayado**
3. El estado cambia a: **"Activado"**

**✅ Funciona si:** Todos los links tienen línea debajo

---

### 7️⃣ Probar: Escala de Grises

**Pasos:**
1. En el panel, haz clic en **"Escala de grises"**
2. La página completa se vuelve **gris** (sin colores)
3. El panel de accesibilidad permanece a color
4. El estado cambia a: **"Activado"**

**✅ Funciona si:** Todo se ve en escala de grises excepto el panel

---

### 8️⃣ Probar: Cambio de Idioma

**Pasos:**
1. En el panel, busca la sección **"Idioma"**
2. Haz clic en el botón **"🇬🇧 English"**
3. TODOS los textos del panel cambian a inglés:
   - "Opciones de Accesibilidad" → "Accessibility Options"
   - "Aumentar tamaño de texto" → "Increase text size"
   - etc.

**Volver a español:**
1. Haz clic en **"🇪🇸 Español"**
2. Los textos vuelven a español

**✅ Funciona si:** Los textos del panel cambian de idioma

---

### 9️⃣ Probar: Persistencia (localStorage)

**Pasos:**
1. Activa varias opciones:
   - Aumenta el texto a "Grande"
   - Activa "Alto contraste"
   - Cambia a "English"
2. **Recarga la página** (F5)
3. Abre el panel de nuevo

**✅ Funciona si:**
- El texto sigue siendo grande
- El alto contraste sigue activo
- El panel está en inglés

---

### 🔟 Probar: Restablecer Configuración

**Pasos:**
1. Activa varias opciones (texto grande, contraste, etc.)
2. En el panel, haz clic en el botón rojo **"Restablecer configuración"**
3. TODAS las configuraciones vuelven a normal:
   - Texto: Normal
   - Alto contraste: Desactivado
   - Subrayar enlaces: Desactivado
   - Escala de grises: Desactivado
   - Idioma: Español (por defecto)

**✅ Funciona si:** Todo vuelve a valores por defecto

---

### 1️⃣1️⃣ Probar: Cerrar con Escape

**Pasos:**
1. Abre el panel (clic en botón flotante)
2. Presiona la tecla **Escape** (Esc)
3. El panel se cierra
4. El foco vuelve al botón flotante

**✅ Funciona si:** El panel se cierra al presionar Escape

---

### 1️⃣2️⃣ Probar: Navegación con Teclado

**Pasos:**
1. Con el panel cerrado, presiona **Tab** varias veces
2. El foco debería llegar al botón flotante (borde azul)
3. Presiona **Enter** → Panel se abre
4. Presiona **Tab** → El foco se mueve entre controles del panel
5. Al llegar al último botón, presiona **Tab** → Vuelve al primero
6. Presiona **Escape** → Panel se cierra

**✅ Funciona si:** Puedes navegar todo con teclado

---

### 1️⃣3️⃣ Probar: Overlay (Fondo Oscuro)

**Pasos:**
1. Abre el panel
2. Haz clic en el **fondo oscuro** (fuera del panel)
3. El panel se cierra

**✅ Funciona si:** Clic en overlay cierra el panel

---

### 1️⃣4️⃣ Probar: Responsive (Móvil)

**Pasos:**
1. Abre las DevTools (F12)
2. Activa modo responsive (Ctrl+Shift+M)
3. Selecciona un tamaño móvil (iPhone, Galaxy, etc.)
4. El botón flotante debe seguir visible
5. Al abrir el panel, debe ocupar toda la pantalla

**✅ Funciona si:** El panel se adapta a pantallas pequeñas

---

## 🎨 Verificar Estilos

### DevTools: Inspecccionar Body

**Pasos:**
1. Abre DevTools (F12)
2. Ve a la pestaña "Elements" o "Inspector"
3. Selecciona el elemento `<body>`
4. Activa opciones en el panel
5. Observa cómo se agregan clases al body:

```html
<body class="font-large high-contrast">
```

**Clases que deberías ver:**
- `font-large` → Texto grande
- `font-extra-large` → Texto extra grande
- `high-contrast` → Alto contraste activo
- `underline-links` → Enlaces subrayados activos
- `grayscale-mode` → Escala de grises activa

---

### DevTools: Verificar localStorage

**Pasos:**
1. Abre DevTools (F12)
2. Ve a la pestaña **"Application"** (Chrome) o **"Storage"** (Firefox)
3. En el menú izquierdo, expande **"Local Storage"**
4. Selecciona tu dominio (`http://localhost:8000`)
5. Busca las siguientes claves:

**Clave: `accessibility-settings`**
```json
{
  "fontSize": "large",
  "highContrast": true,
  "underlineLinks": false,
  "grayscale": false
}
```

**Clave: `app-language`**
```
"es"
```
o
```
"en"
```

**✅ Funciona si:** Las configuraciones se guardan en localStorage

---

### DevTools: Verificar atributo lang

**Pasos:**
1. Abre DevTools (F12)
2. Ve a la pestaña "Elements"
3. Selecciona el elemento `<html>`
4. Busca el atributo `lang`

**En español:**
```html
<html lang="es">
```

**En inglés:**
```html
<html lang="en">
```

**✅ Funciona si:** El atributo cambia al cambiar idioma

---

## 🐛 Troubleshooting

### ❌ El botón no aparece

**Solución:**
1. Verifica que ejecutaste `npm run build`
2. Recarga con Ctrl+Shift+R (limpiar caché)
3. Verifica la consola del navegador (F12)

### ❌ Los textos del panel están en inglés

**Esto es normal si:**
- Tu navegador tiene inglés como idioma por defecto
- O ya habías configurado inglés previamente

**Solución:**
- Haz clic en "🇪🇸 Español" en el panel

### ❌ Los estilos no se aplican

**Solución:**
1. Verifica que el archivo `accessibility.css` existe
2. Limpia caché del navegador: Ctrl+Shift+Del
3. Verifica en DevTools que el archivo CSS se carga

### ❌ El panel no se cierra con Escape

**Solución:**
1. Asegúrate de que el panel está abierto
2. Haz clic dentro del panel primero
3. Luego presiona Escape

---

## 📱 Pruebas en Móvil Real

### iOS / Android

1. Inicia el servidor con tu IP local:
   ```bash
   php artisan serve --host=0.0.0.0
   ```

2. En tu móvil, abre:
   ```
   http://TU_IP_LOCAL:8000
   ```

3. Deberías ver el botón flotante
4. Al hacer tap, el panel ocupa toda la pantalla

---

## ✅ Checklist Final de Pruebas

- [ ] Botón flotante aparece
- [ ] Panel se abre al hacer clic
- [ ] Panel se cierra con Escape
- [ ] Aumentar texto funciona
- [ ] Disminuir texto funciona
- [ ] Alto contraste funciona
- [ ] Subrayar enlaces funciona
- [ ] Escala de grises funciona
- [ ] Cambio de idioma ES/EN funciona
- [ ] Restablecer configuración funciona
- [ ] Configuraciones persisten al recargar
- [ ] Navegación con teclado funciona
- [ ] Cerrar con clic en overlay funciona
- [ ] Responsive en móvil funciona
- [ ] localStorage guarda configuraciones
- [ ] Atributo html lang se actualiza

---

## 🎉 Si Todas las Pruebas Pasan

**¡El sistema está funcionando perfectamente!**

Ahora puedes:
1. Usar el sistema en tu aplicación
2. Agregar más traducciones en `translations.js`
3. Personalizar los estilos
4. Integrar traducciones en tus componentes

---

## 📚 Recursos Adicionales

**Archivos de referencia creados:**
- `SISTEMA_ACCESIBILIDAD_README.md` → Documentación general
- `EXPLICACION_DETALLADA_ACCESIBILIDAD.md` → Explicación técnica
- `resources/js/react/examples/AccessibilityUsageExamples.jsx` → Ejemplos de uso

**¡Todo listo para usar! 🚀**
