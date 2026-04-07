# 🎯 CREAR USUARIO EMPRESA - Guía Completa

## ✅ AHORA FUNCIONA - Panel de Admin Integrado

Se acaba de integrar el **CreateUserModal** directamente en el panel de administración de usuarios. Ahora tienes un botón especial para crear usuarios de empresa.

---

## 🚀 Pasos para Crear un Usuario Empresa

### 1. **Ir al Panel de Admin**
- Abre tu aplicación: `http://localhost:5173`
- Login como administrador
- Ve a: **Panel Admin → Gestión de Usuarios**

### 2. **Haz clic en "Crear Usuario Empresa"**
Verás un botón azul grande arriba a la derecha que dice: **+ Crear Usuario Empresa**

### 3. **Modal se abrirá - Rellena los campos:**

#### **Información Básica**
```
Nombre de Usuario: empresa_xyz        (ej: "parque_natural_admin")
Email:             empresa@test.com   (ej: "admin@ecoparque.com")
Tipo de Usuario:   Empresa            (⚠️ IMPORTANTE: Selecciona EMPRESA)
Contraseña:        [dejar vacío]      (Se genera automáticamente)
```

#### **Selecciona el Lugar**
Una vez selecciones **"Empresa"** como tipo de usuario, aparecerá una nueva sección:

```
☑️ Parque Natural de Orellana
   Rol: [Gerente ▼]
   ☑️ Principal

☐ Laguna Amazónica
☐ Reserva Yacumama
```

**Lo que significa cada opción:**
- **Checkbox**: Selecciona si el usuario gestiona este lugar
- **Rol**: 
  - `Gerente` → Puede aceptar/rechazar reservas
  - `Recepcionista` → Puede ver pero no decidir
  - `Admin del Lugar` → Control total
- **Principal**: Marca SOLO UNA como "Principal" por lugar (es quien recibe notificaciones)

### 4. **El paso MÁS IMPORTANTE**
⚠️ **Selecciona EXACTAMENTE UN LUGAR** para este usuario.

Si necesitas que una empresa gestione múltiples lugares:
- Crea un usuario por lugar, O
- Crea un usuario con múltiples lugares asignados (avanzado)

### 5. **Clic en "Crear Usuario"**
Se mostrará la contraseña generada automáticamente.

**COPIA Y GUARDA ESTA CONTRASEÑA**. Es la única vez que la verás.

---

## 📋 Ejemplo Práctico

### Escenario: Tienes 3 lugares, cada uno necesita su empresa

**Lugar 1: "Parque de Aves"**
```
Nombre: admin_pajaros
Email: info@parquedeaves.com
Tipo: Empresa
Lugar: ✓ Parque de Aves (Rol: Gerente, Principal: ✓)
```

**Lugar 2: "Laguna Transparente"**
```
Nombre: admin_laguna
Email: info@lagunatransparente.com
Tipo: Empresa
Lugar: ✓ Laguna Transparente (Rol: Gerente, Principal: ✓)
```

**Lugar 3: "Volcán Activo"**
```
Nombre: admin_volcan
Email: info@volcanactivo.com
Tipo: Empresa
Lugar: ✓ Volcán Activo (Rol: Gerente, Principal: ✓)
```

---

## 🔄 Qué sucede después

### 1. **Contraseña Generada**
Recibirás una contraseña como esta:
```
K@j9mX#2pLq4vB$8nT1
```

### 2. **La empresa recibe credenciales**
Comparte con la empresa:
```
Email: info@parquedeaves.com
Contraseña: K@j9mX#2pLq4vB$8nT1
```

### 3. **Empresa inicia sesión**
- Van a `http://localhost:5173`
- Usan sus credenciales
- Se redirigen a su **Panel de Empresa**

### 4. **En el Panel de Empresa ven:**
- ✅ Solo las reservas de SU lugar
- ✅ Botones para Aceptar/Rechazar
- ✅ Estadísticas (pendientes, aceptadas, rechazadas)
- ✅ Detalles del cliente

---

## ✅ Verificar que Funcionó

### En la tabla de usuarios verás:

| Nombre | Email | Tipo | Lugares Asignados |
|--------|-------|------|-------------------|
| admin_pajaros | info@parquedeaves.com | 🔵 Empresa | Parque de Aves |
| juan | juan@test.com | 🟢 Cliente | - |
| admin | admin@test.com | 🔴 Admin | - |

### Colores en el tipo:
- 🟢 **Verde** = Cliente (Usuario Normal)
- 🔵 **Azul** = Empresa
- 🔴 **Rojo** = Administrador

---

## ⚡ Probar el Flujo Completo

### Paso 1: Crear usuario empresa
✅ Ya lo hiciste (ver arriba)

### Paso 2: Como CLIENTE, hacer una reserva
1. Logout como admin
2. Crea/login como cliente normal
3. Busca "Parque de Aves"
4. Haz una reserva para mañana a las 3 PM

### Paso 3: Como EMPRESA, gestionar la reserva
1. Logout como cliente
2. Login con credenciales empresa (admin_pajaros / contraseña)
3. Deberías ver tu **Panel de Empresa**
4. Verás la reserva con estado **"Pendiente"**
5. Haz clic en **"Aceptar ✓"** o **"Rechazar ✕"**

---

## ❌ Si algo no funciona

### "No aparece el botón 'Crear Usuario Empresa'"
→ Asegúrate de estar logueado como **Admin**
→ Revisa que estés en **Panel Admin → Gestión de Usuarios**

### "El modal no muestra los lugares"
→ Las migraciones no se ejecutaron correctamente
```bash
php artisan migrate
```

### "Al crear usuario, no puedo seleccionar lugares"
→ Selecciona **"Empresa"** como tipo de usuario
→ Los lugares aparecerán debajo automáticamente

### "Creé el usuario pero no veo lugares asignados"
→ El lugar no estaba seleccionado al crear
→ Borra el usuario y crea uno nuevo
→ Selecciona al menos UN lugar

### "El usuario empresa no ve las reservas de su lugar"
→ Ejecuta las migraciones:
```bash
php artisan migrate
php artisan db:seed --class=RejectionReasonsSeeder
```

---

## 🛠️ Tabla: Flujo de Estados

```
CLIENTE HACE RESERVA
        ↓
Sistema crea registro en "company_reservations"
(Estado: "Pendiente")
        ↓
EMPRESA ve en su panel
        ↓
Empresa elige: Aceptar o Rechazar
        ↓
Si ACEPTA:  → Estado: "Aceptada"    → Reserva confirmada
Si RECHAZA: → Estado: "Rechazada"   → Cliente notificado de motivo
```

---

## 📊 Estadísticas en el Panel

El usuario empresa verá:

```
┌──────────────┬──────────────┬──────────────┐
│   Pendientes │   Aceptadas  │  Rechazadas  │
│      5       │      12      │      2       │
└──────────────┴──────────────┴──────────────┘
```

---

## 🎓 Puntos Importantes

### Cada usuario empresa:
- ✅ VE SOLO su lugar asignado
- ✅ VE SOLO las reservas de ese lugar
- ✅ Puede ACEPTAR o RECHAZAR
- ✅ NO puede ver otros lugares

### Si un lugar tiene 2 usuarios empresa:
- Ambos VEN la misma reserva
- Ambos pueden ACEPTAR/RECHAZAR
- El que actúe primero define el estado

### Si un usuario empresa tiene 2 lugares:
- VE ambos lugares
- VE reservas de ambos
- Puede aceptar/rechazar en cada uno

---

## 📞 Ejemplo de Uso Real

### Situación
Tienes un **Ecoparque con 2 áreas**: Sección Aves y Sección Reptiles

### Solución
```
Usuario 1: maria_aves
  - Email: maria@ecoparque.com
  - Lugar: Sección Aves
  - Rol: Gerente

Usuario 2: carlos_reptiles
  - Email: carlos@ecoparque.com
  - Lugar: Sección Reptiles
  - Rol: Gerente
```

María ve reservas de aves, Carlos ve reservas de reptiles.

O si prefieres un usuario central:

```
Usuario: admin_ecoparque
  - Email: admin@ecoparque.com
  - Lugar 1: Sección Aves (Gerente, Principal)
  - Lugar 2: Sección Reptiles (Gerente)
```

Admin_ecoparque ve TODO.

---

## ✨ Resumen

| Acción | Dónde | Resultado |
|--------|-------|-----------|
| **Crear usuario** | Panel Admin → Usuarios | Usuario empresa creado |
| **Recibe contraseña** | Modal de creación | Mostrada en pantalla |
| **Empresa se loga** | Home de app | Ve su panel de empresa |
| **Cliente hace reserva** | Página de lugar | Se notifica a empresa |
| **Empresa acepta/rechaza** | Panel empresa | Se actualiza estado |
| **Cliente es notificado** | Email (cuando implementes) | Confirma su reserva |

---

## 🚀 Siguiente Paso

1. ✅ Crear usuario empresa (esto)
2. ⏳ [Ver NEXT_STEPS_EMPRESA.md] para:
   - Crear ruta `/company/dashboard`
   - Enviar emails de confirmación
   - Agregar auditoría
   - Deployment a producción

---

**¿Listo?** Haz clic en "Crear Usuario Empresa" ahora mismo. 🎯
