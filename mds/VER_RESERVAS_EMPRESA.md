# 📊 VER RESERVAS DE LA EMPRESA - Guía Rápida

## ✅ LA RUTA YA ESTÁ CREADA

Acabamos de agregar la ruta `/company/dashboard` donde el usuario empresa puede ver TODAS las reservas hechas en su lugar.

---

## 🚀 PASOS PARA VER LAS RESERVAS

### 1. **Inicia sesión como usuario empresa**
- Abre: `http://localhost:5173`
- Email: La que creaste para la empresa
- Contraseña: La que se generó

### 2. **Ve directamente al dashboard**
Opción A: Digita en el navegador:
```
http://localhost:5173/company/dashboard
```

Opción B: Si tu menú tiene un link, haz clic en él

### 3. **¡Verás el panel de la empresa!**

---

## 📋 QUÉ VES EN EL PANEL

### **Sección 1: Estadísticas Rápidas** (Arriba)
```
┌──────────────┬──────────────┬──────────────┐
│   Pendientes │   Aceptadas  │  Rechazadas  │
│      0       │      0       │      0       │
└──────────────┴──────────────┴──────────────┘
```

### **Sección 2: Filtros**
- Todos
- Pendientes
- Aceptadas
- Rechazadas

### **Sección 3: Tabla de Reservas**
Verás las columnas:
- **Cliente**: Nombre de quién hizo la reserva
- **Email**: Correo del cliente
- **Teléfono**: Contacto del cliente
- **Fecha de Visita**: Cuándo vendrá
- **Hora**: A qué hora
- **Personas**: Cuántas personas
- **Estado**: Pendiente / Aceptada / Rechazada
- **Acciones**: Botones para aceptar/rechazar

---

## ✅ CÓMO FUNCIONA

### **Si una reserva está PENDIENTE:**
- Verás dos botones:
  - 🟢 **Aceptar** → Reserva confirmada
  - 🔴 **Rechazar** → Abre modal para elegir razón

### **Si haces clic en ACEPTAR:**
```
✓ Estado cambia a: ACEPTADA
✓ Cliente recibe notificación (cuando implementes emails)
✓ Reserva aparece en sección "Aceptadas"
```

### **Si haces clic en RECHAZAR:**
```
→ Se abre modal para:
  1. Seleccionar razón de rechazo
  2. Agregar comentario (opcional)
  3. Confirmar
  
✓ Estado cambia a: RECHAZADA
✓ Cliente es notificado
✓ Reserva aparece en sección "Rechazadas"
```

---

## 🧪 PRUEBA COMPLETA

### Paso 1: Crear reserva como CLIENTE
1. Logout de la empresa
2. Login como cliente normal
3. Ve a un lugar
4. Haz una reserva para mañana
5. ✅ Reserva creada

### Paso 2: Ver en el panel EMPRESA
1. Logout del cliente
2. Login como usuario empresa
3. Ve a: `http://localhost:5173/company/dashboard`
4. **¡Deberías ver la reserva con estado "Pendiente"!**

### Paso 3: Aceptar la reserva
1. Busca la reserva en la tabla
2. Haz clic en **"Aceptar ✓"**
3. **Listo!** Estado cambia a "Aceptada"

### Paso 4: Prueba rechazar
1. Crea otra reserva como cliente
2. En el dashboard empresa, haz clic en **"Rechazar ✕"**
3. Selecciona una razón (ej: "No disponibilidad en esa fecha")
4. Agrega un comentario (opcional): "Completo ese día"
5. Confirma
6. **¡Rechazada!**

---

## ⚡ ATAJOS

| Acción | URL |
|--------|-----|
| Ver reservas | `http://localhost:5173/company/dashboard` |
| Login | `http://localhost:5173/login` |
| Inicio | `http://localhost:5173` |

---

## 📊 TABLA DE RESERVAS - Detalle

Cuando ves la tabla, tienes:

```
Nombre: Juan Perez
Email: juan@test.com
Teléfono: 3001234567
Fecha de Visita: 10 de febrero de 2026
Hora: 14:30
Personas: 4
Estado: [Pendiente]
Acciones: [Aceptar ✓] [Rechazar ✕]
```

---

## 🎯 RAZONES DE RECHAZO DISPONIBLES

Cuando rechazas, puedes elegir entre:

1. **No disponibilidad en esa fecha** → El lugar está lleno
2. **Mantenimiento programado** → Están haciendo reparaciones
3. **Condiciones climáticas adversas** → Lluvia, tormenta, etc.
4. **Exceso de capacidad** → Ya hay muchas reservas
5. **Cambios operacionales** → Cambios internos
6. **Otros motivos** → Abre espacio para comentario personalizado

---

## ⚠️ SI NO VES NINGUNA RESERVA

Verifica que:

1. ✓ Estés logueado como **usuario empresa**
2. ✓ Hayas creado al menos **una reserva como cliente**
3. ✓ La reserva sea **para el LUGAR que administras**
4. ✓ Las migraciones se ejecutaron: `php artisan migrate`
5. ✓ El seeder se ejecutó: `php artisan db:seed --class=RejectionReasonsSeeder`

---

## 🔍 EXPLICACIÓN TÉCNICA

### ¿Cómo sabe qué reservas mostrar?

Cuando un usuario empresa inicia sesión:
1. El sistema obtiene su `user_id`
2. Busca todos los `lugares` asignados a ese usuario
3. Filtra **solo** las reservas hechas en esos lugares
4. Las muestra en la tabla

**Ejemplo:**
```
Usuario: admin_pajaros
  → Lugares asignados: [Parque de Aves]
  → Ve SOLO reservas del "Parque de Aves"
  → NO ve reservas de "Laguna Transparente"
```

---

## 💬 FLUJO DE COMUNICACIÓN

```
CLIENTE
  ↓
Hace reserva en "Parque de Aves"
  ↓
Sistema crea:
  • Registro en "reservations"
  • Registro en "company_reservations" (Pendiente)
  ↓
EMPRESA (admin_pajaros)
  ↓
Ve la reserva en su dashboard
  ↓
Elige: Aceptar o Rechazar
  ↓
Status se actualiza
  ↓
Cliente recibe notificación (email pendiente)
```

---

## ✨ RESUMEN

| Elemento | Descripción |
|----------|-------------|
| **URL** | `/company/dashboard` |
| **Quién accede** | Usuarios tipo "Empresa" |
| **Qué ve** | Reservas de su lugar asignado |
| **Qué puede hacer** | Aceptar o Rechazar reservas |
| **Información mostrada** | Cliente, fecha, hora, personas, estado |
| **Siguiente paso** | Implementar envío de emails |

---

## 🎓 DETALLES IMPORTANTES

### Cada usuario empresa VE:
- ✅ SOLO su lugar asignado
- ✅ TODAS las reservas de ese lugar
- ✅ Filtrar por estado (pendiente, aceptada, rechazada)

### Cada usuario empresa PUEDE:
- ✅ Aceptar reservas (1 clic)
- ✅ Rechazar con motivo (modal)
- ✅ Ver detalles del cliente
- ✅ Ver estadísticas rápidas

### Cada usuario empresa NO PUEDE:
- ❌ Ver otros lugares
- ❌ Ver reservas de otros lugares
- ❌ Modificar datos del cliente
- ❌ Acceder a panel admin

---

## 🚀 SIGUIENTE PASO

Cuando todo funcione correctamente, implementa:
1. **Emails**: Notificaciones al cliente cuando se acepta/rechaza
2. **Auditoría**: Registrar quién aceptó/rechazó y cuándo
3. **Comentarios**: Que el cliente vea por qué se rechazó

---

**¿Ya lo probaste?** Ve a `http://localhost:5173/company/dashboard` ahora mismo 🎯
