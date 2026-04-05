✓ ARREGLO: Actualizar Imágenes en Lugares (Admin Panel)

## Problema Original
Cuando editabas un lugar, seleccionabas una imagen y presionabas "Actualizar", 
los cambios no se guardaban.

## Causas Identificadas
1. controlador Admin validaba imagen como STRING, no como archivo
2. No había lógica para guardar la imagen en storage
3. No había feedback visual del preview de imagen

## Soluciones Implementadas

### Backend: app/Http/Controllers/API/AdminPlaceController.php

✓ Método update() ahora:
  - Valida imagen como FILE (nullable|image|mimes|max)
  - Comprueba si hay una imagen seleccionada
  - Elimina la imagen anterior si existe
  - Guarda la nueva imagen en /storage/places/
  - Retorna mensaje de éxito + lugar actualizado
  - Sincroniza categorías y ecohoteles

### Frontend: resources/js/react/admin/PlacesAdmin.jsx

✓ Agregado preview visual:
  - Muestra imagen actual cuando editas un lugar
  - Muestra preview de la imagen seleccionada antes de guardar
  - Instrucciones sobre formatos soportados (JPG, PNG, WebP, GIF)
  - Máximo 5MB

### API Service: resources/js/react/services/api.js

✓ Método update() mejorado:
  - Verifica que imagen sea realmente un File object (no null/string)
  - Solo envía imagen si hay una nueva
  - Mantiene estructura correcta de FormData con _method=PUT

## Flujo Correcto Ahora

1. Haz click en "Editar" → se carga el lugar con su imagen actual
2. Selecciona una nueva imagen → ves preview inmediatamente  
3. Haz click en "Actualizar" → se guarda todo (datos + imagen)
4. Ves mensaje de éxito → lista se recarga con cambios
5. La imagen nueva aparece en la tabla

## Capacidades Soportadas

✓ Actualizar solo datos sin cambiar imagen
✓ Actualizar solo imagen sin cambiar datos
✓ Actualizar todo junto
✓ Formatos: JPG, PNG, WebP, GIF
✓ Tamaño máximo: 5MB
✓ Rutas: /storage/places/[timestamp]_[id].[ext]
